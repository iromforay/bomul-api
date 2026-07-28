<?php

namespace App\Controllers\Web;

use App\Controllers\Web\BaseController;
use App\Service\PropertyService;

class Property extends BaseController
{
    private $log;

    public function __construct()
    {
        $this->set_code("property");
        parent::__construct();
        $this->log = $this->getMonoLog($this->get_code());

        return $this;
    }

    public function getindex()
    {
        $this->set_title("매물정보");

        $page = (int) ($this->request->getGet('page') ?? 1);
        if ($page < 1) $page = 1;
        $limit = 10;

        // 공개 웹에는 노출설정된, 비공개 상태가 아닌 매물만
        $where = ["display_yn = 1", "status != 5"];

        $service = new PropertyService($this->log, $this->session_id);
        $rows = $service->get_list($page,$limit,$where);
        $total = $rows['total'];
        $paging = $this->get_paging($total,$page,$limit);

        $data = array(
            'active'=> $this->get_code(),
            'rows'  => $rows['rows'],
            'paging'=> $paging
        );

        $this->view("",'Property/index',$data);
    }

    public function getview($property_no)
    {
        $property_no = (int) $property_no;

        $service = new PropertyService($this->log, $this->session_id);
        $rows = $service->getbyid($property_no);
        $row = $rows[0] ?? null;

        if (!$row || $row['display_yn']!=1 || $row['status']==5) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $service->viewcount($property_no);

        $this->set_title($row['property_name']);

        $data = array(
            'active'=> $this->get_code(),
            'row'   => $row
        );

        $this->view("",'Property/view',$data);
    }
}
