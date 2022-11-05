<?php

use Phalcon\Di\FactoryDefault;
use Phalcon\Di\DiInterface;
use Phalcon\Mvc\View;

$container = new FactoryDefault();

$container->set(
    'view',
    function () {
        $view = new View();
        return $view;
    }
);

$container->set(
    'url',
    function () {
        $url = new \Phalcon\Url();
        $url->setBaseUri('/');
        return $url;
    }
);

$container->set(
    "db",
    function () use ($config) {
        return new \Phalcon\Db\Adapter\Pdo\Mysql(
            [
                "host"     => $config->database->host,
                "username" => $config->database->username,
                "password" => $config->database->password,
                "dbname"   => $config->database->name,
            ]
        );
    }
);

$container->set(
    'dispatcher',
    function () {
        $eventsManager = new \Phalcon\Events\Manager();

        $eventsManager->attach(
            'dispatch:beforeExecuteRoute',
            new \App\Plugins\SecurityPlugin()
        );

        $containerspatcher = new \Phalcon\Mvc\Dispatcher();

        $containerspatcher->setEventsManager($eventsManager);

        return $containerspatcher;
    }
);
