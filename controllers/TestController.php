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

    public function get(Request $request, Response $response): Response
    {
        return $this->view('test.get');
    }

    /**
     * @throws RuleNotFoundException
     */
    public function post(Request $request): Response
    {
        $user = User::create($this->request->input());
        $validate = $request->sessionValidate($this->request->input(), [
            'password' => 'required|min:6',
            'email' => [
                'required',
                'email',
                validator('unique')->model(User::class)->column('email')
            ],
            'confirm_password' => 'required|same:password',
            'birth_date' => 'required|date'
        ]);
        if ($validate && $user->save() && $user->login()) {
            app()->session->setFlash('success', 'test');
            return $this->response->redirect('/');
        }
        return $this->response->redirect('test.get')->with('register-errors', $user->errorBag->all());
    }
}