<?php

namespace Apex\models;

use Apex\src\Model\Model;

class User extends Model
{
    // for now
    public string $name = '';
    public string $label = '';
    public string $email = '';
    public string $password = '';
    public string $confirm_password = '';
    public string $date = '';
}