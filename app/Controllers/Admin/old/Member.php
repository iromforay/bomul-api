<?php


use App\Controllers\Old\BaseController;
use App\Service\BoardService;

class Member extends BaseController
{
    private  $vdir;
    private $service;

    /**
     */
    public function __construct()
    {
        parent::__construct();
        $this->vdir="Admin/Boards/";
        $this->service = new BoardService();
        return $this;
    }

    public function getindex()
    {
        $this->getList();
        return $this;
    }
}