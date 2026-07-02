<?php

namespace App\Controllers;

class Helloworld extends BaseController
{
    public function getIndex()
    {
        return 'Hello World!';
    }
    public function getcomment()
    {
        return  'I am not flat!';
        exit;
    }

}