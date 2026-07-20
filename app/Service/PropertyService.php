<?php

namespace App\Service;

class PropertyService extends CI_Service
{
    private $model;
    private $log;

    public function __construct($log, $session_id)
    {
        parent::__construct($session_id);
        $this->log = $log;
        $this->model = model('App\Models\PropertyModel', true, $this->db);

        return $this;
    }

    public function get_list($page = 1, $limit = 10)
    {
        $total = $this->model->get_count();
        $start = ($page - 1) * $limit;
        $rows = $this->model->get_list($start, $limit);

        return ['rows' => $rows, 'total' => $total];
    }

    public function get_detail($property_no)
    {
        $rows = $this->model->findbyid($property_no);
        return $rows;
    }
}
