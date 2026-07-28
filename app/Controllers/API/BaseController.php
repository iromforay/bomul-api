<?php

namespace App\Controllers\API;

use App\Libraries\AT_Result;
use App\Service\BasicService;
use App\Service\LoginService;
use App\Service\MenuService;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;


abstract class BaseController extends Controller
{
    private $debug = 0;
    protected $session_id;
    public function __construct()
    {
        header('Access-Control-Allow-Origin: *' );
        header('Access-Control-Max-Age: 86400');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Requested-With, Authorization');

        $this->layout="/layout/default";
        $this->session_id = $_SERVER['HTTP_AUTHORIZATION']??"";
        $this->session_id = str_replace("Bearer ","",$this->session_id);

        // SHOP_CODE는 여기서 직접 정의하지 않음 - Admin과 동일하게 BasicService(CI_Service)가
        // login_session 테이블에서 토큰과 매칭되는 fid(shop_code)를 조회해서 정의하도록 위임
        $this->check_service_status();
        $this->set_domain();
        return $this;
    }

    public function check_service_status()
    {
        if($this->session_id !="") {
            $log=$this->getMonoLog("api");
            $BasicService = new BasicService($log, $this->session_id); // 토큰 -> login_session 조회 -> SHOP_CODE 정의
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
        return $this;
    }

    public function set_debug($debug=0)
    {
        $this->debug=$debug;
        return $this;
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
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);


        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = \Config\Services::session();
    }

    public function view($data)
    {
        if($this->debug){
                echo "<pre>";
                print_r($data);
                echo "</pre>";
                exit;
            }
       echo AT_Result::toJson($data);
       exit;
    }
    /*
    * $page_num : 현재페이지 (기본 :1)
    * $total_items : 전체게시물수
    * $block_size : 페이지 블럭사이즈(기본:10)
    * $limit : 페이지당 게시물수 (기본:10)
    * */
    public function calPaging($page_num=1, $total_items, $block_size = 10, $limit = 5)
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
}