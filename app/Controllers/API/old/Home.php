<?php

namespace App\Controllers\API\old;

use App\Controllers\API\BaseController;
use function App\Controllers\API\view;

class Home extends BaseController
{
    public function getindex()
    {
        return view("/Api/index");
    }
}