<?php

namespace Apex\controllers;

use Apex\models\User;
use Apex\src\App;
use Apex\src\Controller\Controller;
use Apex\src\Request;
use Rakit\Validation\RuleNotFoundException;

class AuthController extends Controller
{
    public function showLogin(): bool|string
    {
//        $user = User::select()
//            ->where('id', '=', 1)
//            ->where(['id' => [1, 2, 3]]);
        $user = User::select()->firstWhere('id', '=', 1);
//        $user->save();
//            ->where([
//                ['id', '=', 1],
//            ]);
//        $user->name = 'ahmad joj';
//        $user->save();
        dd($user);

        return $this->view('login');
    }

    public function storeLogin(): bool|string
    {
        return $this->view('login');
    }

    /**
     * @throws RuleNotFoundException
     */
    public function register(Request $request): bool|string
    {
        $user = User::create();
        if ($this->request->isPost()) {
            $user->fill($this->request->input());
            $v = ['password' => 'required|min:6', 'email' => ['required', 'email', validator('unique')->model(User::class)->column('email')], 'confirm_password' => 'required|same:password'];
            $validate = $this->request->validate($this->request->input(), $v);
            if (!$validate->fails()) {
                $user->save();
                App::getInstance()->session->setFlash('success', 'test');
                return $this->response->redirect('/');
            }
            $user->errorMessage->margeErrorBadges($validate->errors());
        }
        return $this->view('register', ['user' => $user]);
    }
}