<?php

$container->set(
    "router", 
    function () {
        $router = new \Phalcon\Mvc\Router();
        $router->setDefaultNamespace("App\Controller");
        return $router;
    }
);