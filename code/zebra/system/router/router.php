<?php

$container->set(
    "router", 
    function () {
        $router = new \Phalcon\Mvc\Router();
        $router->setDefaultNamespace("App\Controllers");
        return $router;
    }
);