<?php
namespace App\Service;
use App\Models\BaModel;

class TestService
{
    private $model;
    public function __construct()
    {
        //$db= db_connect("default");
       // $db =  db_connect("login");
     //   $db->setDatabase("HELP");

       // $this->model = model('App\Models\TestModel',true,$db);
    }

    public function get($idx)
    {

        d("Service");
        $this->model->get_by_id($idx);

    }
    public function test()
    {
        echo "service test"."<BR>";
        $db = db_connect("default");
        $this->models = new BaModel($db);
    }
}