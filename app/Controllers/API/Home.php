<?php

namespace App\Controllers\API;

class Home extends BaseController
{
    public function getindex()
    {
        return view("/Api/index");
    }
}