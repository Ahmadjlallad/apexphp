<?php

namespace Apex\controllers;

use Apex\models\User;
use Apex\src\Controller\Controller;

class TestController extends Controller
{
    public function queryTest()
    {
        $user = User::select()->where([['id' => 1], ['name' => 'test' ], ['id', '<', 'test', 'or'], ['id' => [1, 2, 3]]]);
        dd($user);
    }
}