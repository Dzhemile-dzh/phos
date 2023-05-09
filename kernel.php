<?php

include_once(__DIR__ . DIRECTORY_SEPARATOR . 'config.php');
include_once(__DIR__ . DIRECTORY_SEPARATOR . 'connection' . DIRECTORY_SEPARATOR . 'db.php');

spl_autoload_register (function($class) {
    if (file_exists(__DIR__ . DIRECTORY_SEPARATOR . 'controller' . DIRECTORY_SEPARATOR . $class . '.php')) {
        require __DIR__ . DIRECTORY_SEPARATOR . 'controller' . DIRECTORY_SEPARATOR . $class . '.php';
    }

    if (file_exists(__DIR__ . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . $class . '.php')) {
        require __DIR__ . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . $class . '.php';
    }
});

$db = Connection::getInstance($config);
$db = $db->getConnection();

include_once(__DIR__ . DIRECTORY_SEPARATOR . "routes" . DIRECTORY_SEPARATOR . "route.php");

if (!empty($route)) {
    $routes = explode('@', $route);
    $controller = ucfirst($routes[0]);
    $model = ucfirst(str_replace("Controller", '', $routes[0])) . 'Model';
    $action = lcfirst($routes[1]);
} else {
    $controller = 'RatesController';
    $model = 'RatesModel';
    $action = 'allRatesAction';
}
    $load_new = new $controller();
    $model = new $model();
    $load_new->model = $model;
    $model->db = $db;
    $index = $load_new->$action(); 
    