<?php

namespace Apex\controllers;

use Apex\models\User;
use Apex\src\App;
use Apex\src\Controller\Controller;
use Apex\src\Request;
use Apex\src\Response;

class TestController extends Controller
{
    public function queryTest(Request $request, Response $response, User $user1)
    {
        dd($user1);
        $user = User::select()->where([['id' => 1], ['name' => 'test' ], ['id', '<', 'test', 'or'], ['id' => [1, 2, 3]]]);
        dd($user);
    }
}