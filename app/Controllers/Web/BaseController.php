<?php

namespace App\Controllers\Web;

use CodeIgniter\Controller;

class BaseController extends Controller
{
    public function __construct()
    {
        header('Access-Control-Allow-Origin: *' );
        header('Access-Control-Max-Age: 86400');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Requested-With, Authorization');
        header('Access-Control-Allow-Credentials: true');

        return $this;
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

}