<?php

session_start();

require '../vendor/autoload.php';
require_once '../src/routes.php';

$router = new AltoRouter();

create_routes($router);

$match = $router->match();

if($match){
    list($controller, $controllerMethod) = explode('::', $match['target']);
    $controller = "Src\Controller\\$controller";
    $controller = new $controller();

    call_user_func_array([$controller, $controllerMethod], $match['params']);
}else{
    echo "Error 404. Cannot find the page you're looking for...";
}

function getRouteName($name){
    global $router;
    return $router->generate($name);
}
