<?php

namespace App\Models;

use App\Models\CI_Model;

class PropertyModel extends CI_Model
{
    private $code;
    public function __construct($dbs)
    {
        parent::__construct($dbs,"land","land_no");

        return $this;
    }
}