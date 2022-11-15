<?php
namespace App\Helpers;

use \App\Models\Whitelist;

class SecurityHelper {

    /**
     * проверка логина и пароля для доступа
     */
    public static function checkAuthentification(string $login, string $password): bool
    {
        $whitelist = new Whitelist();

        $whitelist = Whitelist::findFirst(
            [
                'conditions' => 'login = :login:',
                'bind'       => [
                    'login' => $login
                ]
            ]
        );
        
        if ($whitelist->password == Whitelist::encryptPassword($password)) {
            return true;
        }

        return false;
    }
}