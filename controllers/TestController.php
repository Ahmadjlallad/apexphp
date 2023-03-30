<?php

namespace Apex\controllers;

use Apex\models\Categories;
use Apex\models\Options;
use Apex\models\User;
use Apex\src\Controller\Controller;
use Apex\src\Request;
use Apex\src\Response;
use Rakit\Validation\RuleNotFoundException;

class TestController extends Controller
{
    public function queryTest(Request $request, Response $response)
    {
        $request->sessionValidate($request->input('category_id'), ['category_id' => 'string']);
        $category = Categories::select()->firstWhere(['category_id' => 1]);
//        $category = Categories::create();
        dd($category->options);
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