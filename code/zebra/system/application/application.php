<?php

use Phalcon\Mvc\Application;
use Phalcon\Autoload\Loader;

$loader = new Loader();

$loader->setDirectories(
    [
        APP_PATH . '/controllers/',
        APP_PATH . '/models/',
        APP_PATH . '/helpers/',
        APP_PATH . '/plugins/',
    ]
);

$loader->setNamespaces(
    [
        'App\Controller'  => APP_PATH . '/controllers/',
        'App\Models'      => APP_PATH . '/models/',
        'App\Helpers'     => APP_PATH . '/helpers/',
        'App\Plugins'     => APP_PATH . '/plugins/',
    ]
);

$loader->register();

$application = new Application($container);

$url = str_replace("/api", "",$_SERVER["REQUEST_URI"]);

try {
    $response = $application->handle(
        $url
    );
    $response->send();
} catch (\Exception $e) {
    echo $url;
	echo $e->getMessage() . '<br>' . $e->getTraceAsString();
}