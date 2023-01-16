<?php

namespace Apex\models;

use Apex\src\Model\Model;
use Carbon\Carbon;

/**
 * @property int id
 * @property string name
 * @property string email
 * @property Carbon|null birth_date
 * @property string password
 * @property Carbon|null created_at
 * @property Carbon|null updated_at
 */
class User extends Model
{

    public string|null $confirm_password = null;
    protected array $fillable = ['name', 'password', 'email', 'birth_date'];
    public function save(): bool
    {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
        return parent::save();
    }
}