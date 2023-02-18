<?php

namespace Apex\controllers;

use Apex\models\User;
use Apex\src\App;
use Apex\src\Controller\Controller;
use Apex\src\Request;
use Apex\src\Response;
use Rakit\Validation\RuleNotFoundException;

class TestController extends Controller
{
    public function queryTest(Request $request, Response $response)
    {
        $user = \Apex\models\User::select()->makeVisible(['password']);
        $user->where(['id' => [1, 10]]);
        $user->where([['mojo' => ['bojo', '10']], ['ahmd' => 'joj'], ['id', '<', 5555]]);
        $user->where(['id' => 5]);
        dd($user);
    }
}