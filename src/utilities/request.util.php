<?php

function cleanRequest($var){
    if(isset($var)) {
        $var = trim(htmlspecialchars($var));
        return $var;
    }

    return false;
}

function isPost(){
    return isRequest("POST");
}

function isGet(){
    return isRequest("GET");
}

function isRequest($method){
    if($_SERVER['REQUEST_METHOD'] == $method) return true;
    return false;
}