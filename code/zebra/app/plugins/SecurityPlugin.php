<?php

namespace App\Plugins;

use Phalcon\Di\Injectable;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;
use App\Helpers\SecurityHelper;

class SecurityPlugin extends Injectable
{
    public function beforeExecuteRoute(
        Event $event, 
        Dispatcher $containerspatcher
    ) {
        $auth = $this->request->getBasicAuth();
        if (!empty($auth)) {
            list($login, $password) = array_values($auth);
            
            if (!empty($login) && !empty($password)) {
                if (SecurityHelper::checkAuthentification($login, $password)) {
                    return true;
                }
            }
        }

        $this->response->setStatusCode(401);
        return false;
    }
}