<?php

function create_routes($router){
    $router->addMatchTypes(['cl', '[a-zA-Z0-9_-]+']);
    create_get_routes($router);
    create_post_routes($router);
}

function create_get_routes($router){
     $router->map("GET", "/", 'PublicController::index', 'home');
     $router->map("GET", "/landing", 'PublicController::landing', 'landing');
     $router->map("GET", "/create-account", "AuthenticationController::create_account", 'create_account_get');
     $router->map("GET", "/login", "AuthenticationController::login_account", 'login_account_get');
     $router->map("GET", "/dashboard", "UserController::dashboard", "dashboard");
    // $router->map("GET", "/create-account", 'AuthController::create_account', 'create_account');
     $router->map("GET", "/logout", "AuthenticationController::logout", "logout");
     $router->map("GET", "/migrate", "PublicController::migrate", 'migrate');


     $router->map('GET', '/joincalendar/[a:link]', "CalendarShareController::joinCalendar", 'join_calendar');
}

function create_post_routes($router){
    $router->map("POST", "/addcalendar", 'UserController::addCalendar', 'addCalendarUser');
    $router->map("POST", "/add-schedule", 'UserController::addSchedule', 'addScheduleUser');
    $router->map("POST", "/add-note", "UserController::addNote", "addNoteUser");
    $router->map("POST", "/requestuserschedules", "UserController::requestUserSchedules", "requestUserSchedules");
    $router->map("POST", "/requestusernotes", "UserController::requestUserNotes", "requestUserNotes");
    $router->map("POST", "/fetchusercalendars", 'UserController::fetchUserCalendars', 'fetchusercalendars');
    $router->map("POST", "/create-account", "AuthenticationController::create_account", "create_account_post");
    $router->map("POST", "/login", "AuthenticationController::login_account", 'login_account_post');

    $router->map('POST', '/createcalendarlink', 'CalendarShareController::createCalendarLink', 'create_calendar_link_post');
}