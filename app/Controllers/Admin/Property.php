<?php

namespace App\Controllers\Admin;

use App\Libraries\AT_Result;
use App\Service\PropertyService;

class Property extends BaseController
{
    private $log;
    private $service;

    public function __construct()
    {
        parent::__construct();
        $this->is_auth();
        $this->log = $this->getMonoLog("property_api");
        $this->service = new PropertyService($this->log, $this->session_id);

        $this->set_debug(0);
        return $this;
    }

    public function getList()
    {
        $this->log->info("[REV] property listing : ".json_encode($_GET, JSON_UNESCAPED_UNICODE));
        $page  = $this->request->getGet('page') ?? 1;
        $limit = $this->request->getGet('limit') ?? 10;
        $debug = $this->request->getGet('debug') ?? 0;

        $rows = $this->service->get_list($page, $limit);
        $pager_links = $this->calPaging($page, $rows['total'], 10, $limit);

        $data = array(
            'rows'  => $rows['rows'],
            'pager' => $pager_links
        );

        $oResult = AT_Result::toSuccessData("", $data);
        $this->log->info("[RES] property listing > result : ".($oResult['SUCCESS'] ? "T" : "F"));
        $this->set_debug($debug)->view($oResult);
    }

    /*
    * $page_num : 현재페이지 (기본 :1)
    * $total_items : 전체매물수
    * $block_size : 페이지 블럭사이즈(기본:10)
    * $limit : 페이지당 매물수 (기본:10)
    * */
    public function calPaging($page_num = 1, $total_items, $block_size = 10, $limit = 5)
    {
        $items_per_page = $limit;
        $total_pages = ceil($total_items / $items_per_page);

        $block_start = max(1, $page_num - $block_size);
        $block_end = min($total_pages, $page_num + $block_size);

        $item_num_start = $total_items - ($page_num - 1) * $limit;

        return array(
            'item_num_start' => $item_num_start,
            'now_page'       => $page_num,
            'total_items'    => $total_items,
            'total_page'     => $total_pages,
            'page_per_items' => $items_per_page,
            'block_start'    => $block_start,
            'block_end'      => $block_end
        );
    }

    public function getDetail()
    {
        $this->log->info("[REV] property detail : ".json_encode($_GET, JSON_UNESCAPED_UNICODE));
        $property_no = $this->request->getGet('property_no');

        $rows = $this->service->get_detail($property_no);

        $oResult = AT_Result::toSuccessData("", $rows);
        $this->set_debug($this->get_debug())->view($oResult);
        //
    }

    public function save()
    {

    }

    /*preflight*/
    public function optionsList()
    {
        return $this;
    }
}
