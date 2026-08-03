<?php

namespace App\Controllers\Admin;

use App\Libraries\AT_Result;
use App\Libraries\NaverGeocoder;
use App\Service\CI_Service;
use CodeIgniter\Controller;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

/*
 * 네이버 지도 API(지오코딩 + 정적 이미지) - 주소 검색 시 좌표/행정구역 정보와 지도 이미지를 반환한다.
 * BO에서 매물 등록 시 주소 입력 보조용으로 쓸 예정이나, 아직 전용 FE 화면이 없어서
 * app/Controllers/Web/MapSearchTest.php에 임시 테스트 페이지를 별도로 만들어뒀다.
 *
 * 실제 네이버 API 호출은 App\Libraries\NaverGeocoder에 모아뒀다 - PropertyService::save_property()
 * 도 매물 저장 시 주소→위경도 자동 등록에 같은 로직을 쓰기 때문 (중복 방지).
 *
 * 정적 지도 이미지는 네이버 API 키가 필요해서 FE가 직접 그 URL을 호출할 수 없다(키가 노출됨) -
 * 그래서 getImage()가 서버에서 대신 요청해 이미지 바이트를 그대로 프록시한다.
 *
 * Admin\BaseController를 상속하지 않고 Controller를 직접 상속하는 이유(Track.php와 같은 이유):
 * BaseController::is_auth()가 클래스 전체(생성자)에 걸리는 구조라 postAddress()/getImage()를
 * 서로 다른 인증 정책으로 두기 애매하다 - getImage()는 <img src="...">로 그대로 박아 쓰는
 * 엔드포인트라 브라우저가 Authorization 헤더를 실어보낼 방법이 없다(커스텀 헤더 불가). 위경도만
 * 받아서 네이버 지도 타일을 그대로 보여줄 뿐 민감정보가 없어서 인증 없이 열어도 된다고 판단했다.
 * 그래서 이 컨트롤러만 예외로 취급하고, postAddress()에만 자체 인증 체크를 건다.
 */
class Map extends Controller
{
    private $log;

    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Max-Age: 86400');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Requested-With, Authorization');

        $this->log = $this->getMonoLog('map_api');
        return $this;
    }

    public function postAddress()
    {
        $this->checkAuth();

        $address = $this->request->getVar('address');
        if (empty($address)) {
            echo AT_Result::toJson(AT_Result::toFailData('address는 필수입니다.'));
            return;
        }

        // FE 표시용(도로명/지번 등 상세정보)으로는 원본 네이버 응답이 필요해서 geocode()가 아니라
        // 여기선 직접 API를 한 번 더 호출한다 - NaverGeocoder::geocode()는 lat/lng만 반환해서
        // 이 화면에 필요한 addressElements 등 상세 정보가 없다.
        $url = 'https://naveropenapi.apigw.ntruss.com/map-geocode/v2/geocode?query=' . urlencode($address);
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_HTTPHEADER => [
                'X-NCP-APIGW-API-KEY-ID: ' . ($_ENV['map.ClientID'] ?? ''),
                'X-NCP-APIGW-API-KEY: ' . ($_ENV['map.Secret'] ?? ''),
            ],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
        ]);
        $body = curl_exec($ch);
        curl_close($ch);
        $geo = json_decode($body, true) ?? [];

        if (($geo['status'] ?? '') !== 'OK' || empty($geo['addresses'])) {
            $this->log->info('[REV] map address 검색결과 없음 address=' . $address);
            echo AT_Result::toJson(AT_Result::toFailData('검색 결과가 없습니다.'));
            return;
        }

        $first = $geo['addresses'][0];
        $lat = $first['y'];
        $lng = $first['x'];

        // site_url()은 안 씀 - Config\App::$baseURL이 .env에서 안 덮어써져서(app.baseURL 키가
        // 아니라 site_url 키로 들어가 있어 무시됨) localhost:8080 기본값이 나간다. 이 엔드포인트는
        // 항상 api.bomul.ai.kr에 있으니 직접 고정한다.
        $imageUrl = 'http://api.bomul.ai.kr/admin/map/image?lat=' . urlencode($lat) . '&lng=' . urlencode($lng);

        echo AT_Result::toJson(AT_Result::toSuccessData("", [
            'address'   => $first,
            'lat'       => $lat,
            'lng'       => $lng,
            'image_url' => $imageUrl,
        ]));
    }

    // 정적 지도 이미지 프록시 (postAddress가 내려준 image_url이 이걸 가리킴). 인증 없음 - 위 참고.
    public function getImage()
    {
        $lat = $this->request->getVar('lat');
        $lng = $this->request->getVar('lng');
        if (empty($lat) || empty($lng)) {
            return $this->response->setStatusCode(400);
        }

        $image = NaverGeocoder::fetchStaticMapImage($lat, $lng);
        if ($image === null) {
            return $this->response->setStatusCode(502);
        }

        return $this->response->setContentType('image/png')->setBody($image);
    }

    // Admin\BaseController::is_auth()와 동일한 로직 - Bearer 토큰 → login_session에서 fid 조회.
    // BaseController를 안 쓰기로 해서 이 컨트롤러 안에서만 자체적으로 갖춘다.
    private function checkAuth()
    {
        $sessionId = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        $sessionId = str_replace('Bearer ', '', $sessionId);

        if ($sessionId === '') {
            echo AT_Result::toFailJson('로그인후 이용가능합니다.', 'NEED_LOGIN');
            exit;
        }

        new CI_Service($sessionId);

        if (!defined('SHOP_CODE') || SHOP_CODE == '') {
            echo AT_Result::toFailJson('잘못된 접근입니다.', 'INVALID_TOKEN');
            exit;
        }
    }

    public function getMonoLog($log_name = 'map')
    {
        $file_name = date('Ymd') . '_' . $log_name . '.log';

        $log = new Logger($log_name);
        $log->pushHandler(new StreamHandler(LOG_DIR . '/' . date('Ym') . '/' . $file_name, Logger::DEBUG));

        return $log;
    }
}
