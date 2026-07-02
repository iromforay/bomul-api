<?php

namespace App\Controllers\Old;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use function App\Controllers\view;


/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    protected $shop_code;
    protected $request;
    protected $layout;
    public function __construct()
    {
        $this->layout="/layout/default";
        define('SHOP_CODE', 'demo'); //상점코드 - help 아이디

        return $this;
    }
    //페이지 캐쉬

    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */


    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = [];



    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * Constructor.
     */

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);


        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = \Config\Services::session();
    }

    public function view(string $layout="" ,string $name, array $data = [], array $options = [])
    {
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
    public function alert_msg($MSG)
    {

        echo "<script type='text/javascript'>";
        echo "	alert('" . $MSG . "');";
        echo "</script>";

        exit;
    }

    public function _redirect(  $URL = '' )
    {

        echo "<script type='text/javascript'>";

        if( empty($URL) ){
            echo "	location.href = window.history.back();";
        }else{
            echo "	location.href = '" . $URL . "';";
        }
        echo "</script>";

        exit;
    }
    public function _warning_redirect( $MSG, $URL = '' )
    {

        echo "<script type='text/javascript'>";
        echo "	alert('" . $MSG . "');";
        if( empty($URL) ){
            echo "	location.href = window.history.back();";
        }else{
            echo "	location.href = '" . $URL . "';";
        }
        echo "</script>";

        exit;
    }
}
