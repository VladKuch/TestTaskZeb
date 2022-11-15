<?php

namespace App\Plugins;

use Phalcon\Di\Injectable;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;
use App\Helpers\SecurityHelper;

class SecurityPlugin extends Injectable
{
    /**
     * Проверка доступа по Basic Auth перед выполнеием запроса
     */
    public function beforeExecuteRoute(
        Event $event, 
        Dispatcher $containerspatcher
    ) {
        try {
            $auth = $this->request->getBasicAuth();
            list($login, $password) = array_values($auth);
            if (!SecurityHelper::checkAuthentification($login, $password)) {
               throw new \Exception("Security issue!");
            }
            return true;
        } catch (\Throwable $error){
            $this->response->setStatusCode(401);
            return false;
        }
       
    }
}