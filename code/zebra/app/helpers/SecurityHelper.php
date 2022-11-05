<?php
namespace App\Helpers;

use \App\Models\Whitelist;

class SecurityHelper {
    public static function checkAuthentification($login, $password)
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
        
        if (isset($whitelist) && $whitelist->password == Whitelist::encryptPassword($password)) {
            return true;
        }

        return false;
    }
}