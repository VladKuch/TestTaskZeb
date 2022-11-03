<?php

use Phalcon\Di\FactoryDefault;
use Phalcon\Di\DiInterface;
use Phalcon\Session\Adapter\Stream;
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
    "session",
    function () {
        $session = new \Phalcon\Session\Manager();
        $files   = new Stream(
            [
                'savePath' => '/tmp'
            ]
        );
        $session->setAdapter($files);
        $session->start();
        return $session;
    }
);

$container->set(
    'security',
    function () {
        $security = new \Phalcon\Security();

        $security->setWorkFactor(12);

        return $security;
    },
    true
);

$container->set(
    'crypt',
    function () use ($config) {
        $crypt = new \Phalcon\Crypt();
        $crypt->setCipher('aes256')->useSigning(false);
        $crypt->setKey(
            $config->security->encryption_key
        );

        return $crypt;
    },
    true
);

