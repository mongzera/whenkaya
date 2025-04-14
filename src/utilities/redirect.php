<?php

require_once "../public/index.php";

function redirect($name){
    header("Location: " . getRouteName($name));
}

function addMessage($message, $status = 'success'){
    initMessages();
    array_push($_SESSION['messages'], [$message, $status]);
}

function popMessage(){
    initMessages();

    return array_pop($_SESSION['messages']);
}

function initMessages(){
    if(!isset($_SESSION['messages'])) $_SESSION['messages'] = [];
}