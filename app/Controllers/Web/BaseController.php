<?php

namespace App\Controllers\Web;

use App\Service\BasicService;
use App\Service\MenuService;
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

       // $this->check_service_status();
        $this->set_domain();
        return $this;
    }
    public function check_service_status()
    {
        if($this->session_id !="") {
            $log=$this->getMonoLog("api");
            $BasicService = new BasicService($log, SHOP_CODE);
            $status = $BasicService->get_service_status(SHOP_CODE);

            $log->info("[REV] SHOP_CODE : ".SHOP_CODE." status : ".$status);
            if($status){
                return $this;
            }else{
                return $this;
                // echo AT_Result::toFailJson("서비스 만료","SERVICE_EXPIRED");
                exit;
            }
        }else{
            echo "상태 확인 안됨";
        }
    }
    public function set_domain()
    {
        // fid.bomul.co.kr / fid.bomul.ai.kr 의 첫 서브도메인을 fid(=SHOP_CODE)로 사용한다.
        // 로컬 개발(localhost 등)이나 서브도메인 없는 apex 접속처럼 fid를 알 수 없을 때만
        // "foray"로 폴백한다.
        $host = strtolower(explode(':', $_SERVER['HTTP_HOST'] ?? '')[0]);
        $parts = explode('.', $host);
        $shop_code = (count($parts) >= 4) ? $parts[0] : "foray";

        $this->session_id = $shop_code;
        if(!defined("SHOP_CODE")) define('SHOP_CODE', $shop_code);
        return $this;
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
        //{"SUCCESS":"TRUE","MSG":"\uc131\uacf5\uc785\ub2c8\ub2e4.","RECORD":{"row":{"name":"\uacf5\uc9c0\uc0ac\ud56d","code":"notice","bgcolor":"#de0d0d","transparent":"29","title":null,"title_show":null,"bgcolor_show":null},"img":null,"basic":{"site_name":"foray","logo_show":"Y","menu_back_color":"#006eff","menu_back_transparent":"50","menu_bg_color":"#e0e0e0","menu_bg_transparent":null,"main_menu_color":"#000000","notice_show":"Y","around_show":"Y","charge_show":"Y","review_show":"Y","vod_show":"N","map_show":"Y","link_menu_show":"Y","naver_reserve":"","naver_reserve_show":"Y","kakao_channel":"","kakao_channel_show":"Y","company_name":"25\uc2dc \ubd80\ub3d9\uc0b0","footer_copy":"","logo_url":"http:\/\/api.bomul.co.kr\/pds\/\ub85c\uace0","sub_menu_color":"#000000","sns_link":[]}}}
        //API/Notice
        $row = $this->listing($this->code);

        if($row['SUCCESS'] == "TRUE"){
            $this->sub_body =  $row['RECORD']['row'];
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