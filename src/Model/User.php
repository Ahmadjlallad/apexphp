<?php

namespace Apex\src\Model;

use Apex\src\App;

abstract class User extends Model
{
    protected string $searchableBy = 'email';
    protected array $hidden = [
        'password',
    ];

    public function login(): bool
    {
        $user = $this->firstWhere([$this->searchableBy => $this->email]);
        if (!$user) {
            $this->errorBag->addError('email', 'User dose not exist with this email');
            return false;
        }
        if (password_verify($this->password, $user->password)) {
            $this->errorBag->addError('password', 'Password is Incorrect');
            return false;
        }
        App::getInstance()->login($user);
        return true;
    }

    public function logout(): void
    {
        App::getInstance()->logout();
    }
}