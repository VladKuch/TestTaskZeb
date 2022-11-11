<?php

namespace App\Models;

use Phalcon\Mvc\Model;

class Whitelist extends Model {
    public $id;
    public $login;
    public $password;
    public $is_active;
    public $created_at;

    public function initialize()
    {
        $this->setSource('whitelist');
    }
    /**
     * Событие: перед сохранением хэшировать пароль
     */
    public function beforeCreate()
    {
        $this->password = hash('sha256', $this->password);
    }

    public static function encryptPassword($password) 
    {
        return hash('sha256', $password);
    }
}