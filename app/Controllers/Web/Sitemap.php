<?php

namespace App\Controllers\Web;

use App\Models\BasicModel;
use App\Service\Api\PostService;
use App\Service\PropertyService;
use CodeIgniter\Controller;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

/*
 * /sitemap.xml - 검색엔진(네이버 Yeti 등)에 이 사이트의 크롤링 가능한 URL 전체를 알려준다.
 * 중개사마다 매물/공지 내용이 다르므로 정적 파일이 아니라 그 중개사 DB를 조회해 매 요청마다
 * 동적으로 생성한다.
 *
 * Web\BaseController를 상속하지 않은 이유: Track.php와 동일 - get_header()/body()가 크롤러
 * 요청마다 불필요한 DB 조회를 추가하게 된다. SHOP_CODE는 Host 헤더에서 직접 최소 해석한다
 * (서브도메인 = fid, Cron\Analytics와 동일한 방식).
 *
 * 공개 목록(Property::getindex, Notice::getindex)과 동일한 노출 조건만 포함한다 -
 * 비공개/삭제 매물처럼 실제로 안 보이는 페이지를 sitemap에 올리면 안 되므로.
 */
class Sitemap extends Controller
{
    private $log;

    public function getIndex()
    {
        $shopCode = $this->resolveShopCodeFromHost();
        if ($shopCode !== '' && !defined('SHOP_CODE')) {
            define('SHOP_CODE', $shopCode);
        }

        $this->log = $this->getMonoLog('sitemap');

        $host = $_SERVER['HTTP_HOST'] ?? '';
        $base = 'http://' . $host;

        // 관리자가 basic 설정에서 꺼둔 메뉴는 sitemap에도 안 실어야 한다 (Admin\Menu가 다루는
        // property_show/notice_show/charge_show/review_show/around_show/map_show 그 플래그).
        $show = $this->getBasicShowFlags();

        $urls = [$base . '/']; // 홈은 항상 포함
        foreach (['property', 'notice', 'charge', 'review', 'around', 'map'] as $path) {
            if (($show[$path . '_show'] ?? 'Y') === 'Y') {
                $urls[] = $base . '/' . $path;
            }
        }

        // 매물 상세 - property_show가 꺼져있으면 섹션 자체가 없는 것이니 상세도 스킵.
        // 켜져있으면 Web\Property::getindex()와 동일한 노출 조건(display_yn=1, status!=5)만 포함.
        if (($show['property_show'] ?? 'Y') === 'Y') {
            try {
                $propertyService = new PropertyService($this->log, '');
                $properties = $propertyService->get_list(1, 10000, ["display_yn = 1", "status != 5"]);
                foreach ($properties['rows'] as $row) {
                    $urls[] = $base . '/property/view/' . $row['property_no'];
                }
            } catch (\Throwable $e) {
                $this->log->error('[REV] sitemap property 조회 실패: ' . $e->getMessage());
            }
        }

        // 공지 상세 - notice_show가 꺼져있으면 스킵. 켜져있으면 Web\Notice::getindex()와
        // 동일하게 별도 노출조건 없음.
        if (($show['notice_show'] ?? 'Y') === 'Y') {
            try {
                $postService = new PostService($this->log);
                $notices = $postService->set_code('notice')->get_list(1, 10000, []);
                foreach ($notices['rows'] as $row) {
                    $urls[] = $base . '/notice/view/' . $row['board_no'];
                }
            } catch (\Throwable $e) {
                $this->log->error('[REV] sitemap notice 조회 실패: ' . $e->getMessage());
            }
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($urls as $url) {
            $xml .= '  <url><loc>' . htmlspecialchars($url, ENT_XML1) . '</loc></url>' . "\n";
        }
        $xml .= '</urlset>';

        return $this->response->setContentType('application/xml')->setBody($xml);
    }

    // basic 테이블(관리자 노출설정)의 *_show 플래그를 조회. 실패해도 sitemap 자체는 죽지 않게
    // 기본값(전부 노출)으로 폴백한다 - 크롤러 요청에서 이런 부가정보 하나 때문에 500 내는 것보단
    // 관대하게(과다노출) 처리하는 쪽이 낫다고 판단.
    private function getBasicShowFlags()
    {
        try {
            $db = db_connect('default');
            $db->setDatabase(SHOP_CODE);
            $basicModel = model(BasicModel::class, true, $db);
            $rows = $basicModel->findbyid(1);
            return $rows[0] ?? [];
        } catch (\Throwable $e) {
            $this->log->error('[REV] sitemap basic 설정 조회 실패: ' . $e->getMessage());
            return [];
        }
    }

    // Host 헤더의 첫 세그먼트 = fid = shop_code (예: foray.bomul.co.kr → foray)
    private function resolveShopCodeFromHost()
    {
        $host = $_SERVER['HTTP_HOST'] ?? '';
        $parts = explode('.', $host);
        return $parts[0] ?? '';
    }

    private function getMonoLog($log_name = 'sitemap')
    {
        $file_name = date('Ymd') . '_' . $log_name . '.log';

        $log = new Logger($log_name);
        $log->pushHandler(new StreamHandler(LOG_DIR . '/' . date('Ym') . '/' . $file_name, Logger::DEBUG));

        return $log;
    }
}
