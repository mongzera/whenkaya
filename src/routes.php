<?php

function create_routes($router){

    create_get_routes($router);
    create_post_routes($router);
}

function create_get_routes($router){
     $router->map("GET", "/", 'PublicController::index', 'home');
     $router->map("GET", "/create-account", "AuthenticationController::create_account", 'create_account_get');
     $router->map("GET", "/login", "AuthenticationController::login_account", 'login_account_get');
     $router->map("GET", "/dashboard", "UserController::dashboard", "dashboard");
    // $router->map("GET", "/create-account", 'AuthController::create_account', 'create_account');
    // $router->map("GET", "/login", "AuthController::login", "login");
    // $router->map("GET", "/logout", "AuthController::logout", "logout");
}

function create_post_routes($router){
    $router->map("POST", "/addschedule", 'UserController::addSchedule', 'addScheduleUser');
    $router->map("POST", "/fetchuserschedule", 'UserController::fetchUserSchedules', 'fetchScheduleUser');
    $router->map("POST", "/create-account", "AuthenticationController::create_account", "create_account_post");
    $router->map("POST", "/login", "AuthenticationController::login_account", 'login_account_post');
}