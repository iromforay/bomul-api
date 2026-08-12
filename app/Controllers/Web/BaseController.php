<?php

namespace App\Controllers\Web;

use App\Libraries\AT_Result;
use App\Service\BasicService;
use App\Service\MenuService;
use App\Models\MemberModel;
use CodeIgniter\Controller;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

class BaseController extends Controller
{
    private $body;
    private $sub_body;
    private $code;
    private $title;
    private $description = '';
    public function __construct()
    {
        header('Access-Control-Allow-Origin: *' );
        header('Access-Control-Max-Age: 86400');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Requested-With, Authorization');
        header('Access-Control-Allow-Credentials: true');
//dd(SITE);
        $this->get_header();
        $this->body();
        return $this;
    }

    /**
     * @param mixed $code
     */
    public function set_code($code): void
    {
        $this->code = $code;
    }

    /**
     * @return mixed
     */
    public function get_code()
    {
        return $this->code;
    }

    public function get_body()
    {
        return $this->body;
    }

    /**
     * @return mixed
     */
    public function getSubBody()
    {
        return $this->sub_body;
    }


    /**
     * @return mixed
     */
    public function get_title()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function set_title($title): void
    {
        $this->title = $title;
    }

    // <meta name="Description">/og:description용 - 미설정 시 header.php가 site_name 기반 기본값을 씀
    public function set_description($description): void
    {
        $this->description = $description;
    }



    public function get_header()
    {
      /*  $this->session_id = $_SERVER['HTTP_AUTHORIZATION']??"";
        $SHOP_CODE = str_replace("Bearer ","",$this->session_id);

        define('SHOP_CODE', $SHOP_CODE); //상점코드 - help 아이디*/

        $this->set_domain();
        $this->check_service_status();
        return $this;
    }

    // member.service(OPERATING/USER_CLOSED/ALL_CLOSED) 확인 후 사용자웹 접속 차단.
    // USER_CLOSED/ALL_CLOSED 둘 다 사용자웹은 막는다(사용자기준 차단여부는 동일, 관리자bo 접속가능
    // 여부만 다름 - 그건 bo쪽 로그인에서 별도 체크). member row가 없거나(레거시 상점) 그 외 값은
    // 운영중으로 간주해 통과시킨다.
    // DB를 매 요청마다 조회하면 부하가 크니 fid당 5초 file cache로 완화 - 상태변경 반영이 최대 5초
    // 지연될 수 있지만 즉시성 요구 대비 부하 절감이 더 중요하다고 판단.
    public function check_service_status()
    {
        $fid = SHOP_CODE;
        $cacheKey = 'member_service_' . $fid;

        $status = cache()->get($cacheKey);
        if ($status === null) {
            $db = db_connect('default');
            $model = model(MemberModel::class, true, $db);
            $status = $model->getServiceByFid($fid) ?? '';
            cache()->save($cacheKey, $status, 5);
        }

        if (in_array($status, ['USER_CLOSED', 'ALL_CLOSED'], true)) {
            $this->render_error_page('error/service_closed');
        }

        return $this;
    }
    public function set_domain()
    {
        // fid.bomul.co.kr / fid.bomul.ai.kr 의 첫 서브도메인을 fid(=SHOP_CODE)로 사용한다.
        // 로컬 개발(localhost 등)이나 서브도메인 없는 apex 접속처럼 fid를 알 수 없을 때만
        // "foray"로 폴백한다.
        $host = strtolower(explode(':', $_SERVER['HTTP_HOST'] ?? '')[0]);
        $parts = explode('.', $host);
        $shop_code = (count($parts) >= 4) ? $parts[0] : "foray";

        if (!$this->tenant_db_exists($shop_code)) {
            $this->render_error_page('error/invalid_url');
        }

        $this->session_id = $shop_code;
        if(!defined("SHOP_CODE")) define('SHOP_CODE', $shop_code);
        return $this;
    }

    // 존재하지 않는 상점/서비스 종료 상점 접속 시 안내 HTML을 그대로 내려주고 종료한다. 상태코드는
    // 그대로 200 - 404/503을 쓰면 브라우저/모니터링 도구/CDN이 "서버 오류"로 인식해서 안내 문구를
    // 못 보여주거나 장애로 오탐하는 문제가 있었다. 그래서 정상 응답(200)으로 안내 페이지 자체를
    // 그대로 내려서 사용자가 "주소가 틀렸다" / "서비스가 종료됐다"를 화면으로 바로 구분하게 한다.
    private function render_error_page(string $view): void
    {
        echo view($view);
        exit;
    }

    // fid(서브도메인)와 동일한 이름의 테넌트 DB가 실제로 존재하는지 확인 - TenantProvisioner::provision()이
    // 상점마다 `CREATE DATABASE {fid}`로 만들어두는 그 DB. 없는 상점코드로 접속하면(오타, 삭제된 상점 등)
    // 여기서 걸러 404를 낸다 - 그대로 두면 뒤의 로직이 존재하지 않는 DB에 setDatabase()를 시도하다 죽는다.
    // 매 요청 information_schema 조회는 부하가 있어 fid당 5초 file cache로 완화한다.
    private function tenant_db_exists(string $fid): bool
    {
        $cacheKey = 'tenant_db_exists_' . $fid;
        $exists = cache()->get($cacheKey);
        if ($exists === null) {
            $db = db_connect('default');
            $sql = "SELECT SCHEMA_NAME FROM information_schema.SCHEMATA WHERE SCHEMA_NAME = " . $db->escape($fid) . " LIMIT 1";
            $row = $db->query($sql)->getRowArray();
            $exists = $row !== null;
            cache()->save($cacheKey, $exists, 5);
        }
        return (bool) $exists;
    }
    public function view(string $layout="" ,string $name = "", array $data = [], array $options = [])
    {
        $data['common']= $this->body; // 공통 변수값 입력.
        $data['sub']= $this->sub_body; // 전용 변수값 입력.
        $data['common']['title']= $this->title; //헤더영역내 title
        $data['common']['description']= $this->description; //meta description / og:description
        if($layout=="") $layout="default";
        else $layout=$layout ;

        $layout_dir= VIEW."/layout/".$layout;
        if(! file_exists($layout_dir)){
            $layout="layout/default";
        }

        $data['FRESH_TIME'] = isset($data['FRESH_TIME'])? DATE('YmdHis') : FRESH_TIME;
        $data['common']['favicon'] = $data['common']['favicon']?? RS_IMG."/header/favicon.png";

        $temp_layout = array(
            'header_menu' =>view("common/header_menu",$data),
            'header'=>view("common/header",$data),
            'content' => view($name, $data,$options),
            'footer'=>view("common/footer",$data)
        );
        echo  view($layout,$temp_layout,$options);

    }
    public function getMonoLog($log_name="api")
    {
        if(defined('SHOP_CODE')){
            $file_name = SHOP_CODE."_log_".date("Ymd").".log";
        }
        else if(!defined('FID')){
            $file_name = "COMMON_log_".date("Ymd").".log";
        }else{
            $file_name = FID."_log_".date("Ymd").".log";
        }

        $log = new Logger($log_name);

        $log->pushHandler(new StreamHandler(LOG_DIR.'/'.date("Ym")."/".$file_name, Logger::DEBUG));

        return $log;
    }
    public function body()
    {
        //{"SUCCESS":"TRUE","MSG":"\uc131\uacf5\uc785\ub2c8\ub2e4.","RECORD":{"row":{"name":"\uacf5\uc9c0\uc0ac\ud56d","code":"notice","bgcolor":"#de0d0d","transparent":"29","title":null,"title_show":null,"bgcolor_show":null},"img":null,"basic":{"site_name":"foray","logo_show":"Y","menu_back_color":"#006eff","menu_back_transparent":"50","menu_bg_color":"#e0e0e0","menu_bg_transparent":null,"main_menu_color":"#000000","notice_show":"Y","around_show":"Y","charge_show":"Y","review_show":"Y","vod_show":"N","map_show":"Y","link_menu_show":"Y","naver_reserve":"","naver_reserve_show":"Y","kakao_channel":"","kakao_channel_show":"Y","company_name":"25\uc2dc \ubd80\ub3d9\uc0b0","footer_copy":"","logo_url":"http:\/\/api.bomul.ai.kr\/pds\/\ub85c\uace0","sub_menu_color":"#000000","sns_link":[]}}}
        //API/Notice
        $row = $this->listing($this->code);
        $menu_no = $row['RECORD']['row']['menu_no']??0;

        if(empty($row['RECORD']['img'])) {
            $img = $this->getImage($menu_no, $this->code);
        }else $img = $row['RECORD']['img'];

        if($row['SUCCESS'] == "TRUE"){
            $this->sub_body =  $row['RECORD']['row'];
            $this->sub_body['widthConClass'] = "";
            $this->sub_body['widthConClass'] = $this->sub_body['bgcolor_show'] === 'N' ? 'sub-bg-color' : 'sub-bg-image';
//dd($img);
            if(count($img)<=0) { //등록된 이미지가 없다면 기본 이미지 제공.
                $img['0']['full_url'] = RS_IMG."/demo/menu/sub_title.png";
            }
            $this->sub_body['img'] = $img;

            $body = $row['RECORD']['basic'];
            $body['menu_back_transparent'] = (int)$body['menu_back_transparent']/100;
            $body['sec_bgcolor'] = $row['RECORD']['row']['bgcolor'];
            $body['main_transparent'] = (100-$row['RECORD']['row']['transparent'])/100;

        }else{
            $body = array(
                "main_menu_color"=>"#fff",
                "sub_menu_color"=>"#E72D2B",
                "sec_bgcolor"=>"#f5f5f5",
                "menu_back_color"=>"#fff",
                "menu_back_transparent"=>"0",
                "main_transparent"=>"0",
                "company_name" =>"보이는 매물",
                "footer_copy" =>"Copyrightⓒ 2023 보이는 매물 All rights reserved"
            );
        }
        $body['base_url']    = strtok($_SERVER['REQUEST_URI'] ?? '', '?'); //현재 페이지 - 공통
        $body['code']    = $this->code;//현재 페이지  코드 - 공통

        $this->body = $body;
        return $this;

    }
    public function get_paging($total,$page=1,$limit=10)
    {
        $pager_links = $this->calPaging($page,$total,10, $limit);
        return $pager_links;
    }
    public function getImage($menu_no,$code)
    {
        $img_model = model('App\Models\ImgModel',true,$this->db);
        $image= array();
        // 각 메뉴별 등록가능 이미지가 고정 (notice,charge,review,map,vod,property 는 각 1장씩 )
        if(in_array($code,["main","around"])){ //main,around(메임,둘러보기는 다수 이미지 등록 가능
            $image_row = $img_model->get_image($menu_no,"menu",0);
        }else{
            $image_row = $img_model->get_image($menu_no,"menu",1);
        }

        foreach ($image_row as $k => $v) {
            $image[$k]['img_no'] = $v['img_no'];
            $image[$k]['full_url'] = SITE_PDS . $v['img_dir'] . $v['img_name'];
        }

        return $image;

    }
    public function calPaging($page_num=1, $total_items = 0, $block_size = 10, $limit = 5)
    {

        $items_per_page=$limit; //페이지당 게시물수
        $total_pages=0; //젠체 페이지수

        $total_pages = ceil($total_items / $items_per_page);

        // 페이지 블럭의 시작 번호와 끝 번호 계산
        $block_start = max(1, $page_num - $block_size);
        $block_end = min($total_pages, $page_num + $block_size);

        $item_num_start = $total_items - ($page_num-1)*$limit ;
        $paging = array(
            'item_num_start' =>$item_num_start,
            'now_page' =>$page_num,
            'total_items' => $total_items,
            'total_page'=>$total_pages,
            'page_per_items' =>$items_per_page,
            'block_start' => $block_start,
            'block_end'=>$block_end
        );
        return $paging;
    }
    public function listing($code)
    {
        // 공통 함수로 변경시  ( main,around,charge,map,notice,reveiew,vod 에 영향을 줌.
        // 각 controller 로 이관후 처리 하도록. written foray
        $BasicService = new BasicService(null,null);
        $basic_data = $BasicService->get_by_basic();


        $log = null;
        $service = new MenuService($log,null);


        if($code =="button") $data=$service->getButton_menu();
        elseif ($code=="map"){
            $data=$service->getLocation();
        }
        else $data = $service->getBycode($code);

        $data['RECORD']['basic'] = $basic_data;

        return $data;

        //위치 정보 설정값.

    }
}