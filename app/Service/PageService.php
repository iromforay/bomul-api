<?php

namespace App\Service;

use App\Libraries\Aes;
use App\Libraries\AT_Result;

class PageService extends CI_Service
{
    protected $code;
    protected $model;

    /**
     * @param $model
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = model('App\Models\PageModel',true,$this->db);
        return $this;
    }
    public function getbyid($post_no)
    {
        $row = $this->model->findbyid($post_no);
        return $row;
    }
    public function getBycode($code)
    {
        $data['row'] = $row =  $this->model->findbycode($code);
        if(isset($row['pager_no'])) {
            $data['data'] = $this->model->findBypager_no($row['pager_no']);
            return AT_Result::toSuccessData("",$data);
        } else{
            return  AT_Result::toFailData("존재하지 않는 페이지 입니다.");
        }

    }
}