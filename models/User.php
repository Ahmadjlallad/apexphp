<?php

namespace Apex\models;

use Apex\src\Model\Model;

/**
 * @property int id
 * @property string name
 * @property string email
 * @property \Carbon\Carbon|null birth_date
 * @property string password
 * @property \Carbon\Carbon|null created_at
 * @property \Carbon\Carbon|null updated_at
 */
class User extends Model
{

    public string|null $confirm_password = null;
    protected array $fillable = ['name', 'password', 'email', 'birth_date'];
}