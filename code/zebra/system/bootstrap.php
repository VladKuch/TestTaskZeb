<?php

$server_name = explode('.', $_SERVER['HTTP_HOST'], 2);
switch ($server_name[0]) {
    case 'admin':
        $module = $server_name[0];
        break;
    
    default:
        $module = 'web';
        break;
}

require_once BASE_PATH . '/system/config/config.php';
require_once BASE_PATH . '/system/container/di.php';
require_once BASE_PATH . '/system/router/router.php';
require_once BASE_PATH . '/system/application/application.php';