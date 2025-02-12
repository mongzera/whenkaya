<?php

use Src\Db\Database;

function connect_db(){
    $dbInstance = new Database();
    $dbInstance->setDatabase("whenkaya_db");
    return $dbInstance->connect();
}

function addCalendar($name){
    $conn = connect_db();

    $create_new_calendar = "INSERT INTO tb_calendar (`calendar_name`, `date_created`, `date_modified`) VALUES ('$name', NOW(), NOW())";
    $stmt = $conn->prepare($create_new_calendar);
    if($stmt->execute() == true){
        $retrieve_recent_calendar_id = $conn->lastInsertId();

        $new_user_calendar_assoc = "INSERT INTO tb_user_calendar_assoc (`user_id`, `calendar_id`, `user_role`, `date_created`, `date_modified`) VALUES (2, $retrieve_recent_calendar_id, 0, NOW(), NOW());";
        $stmt = $conn->prepare($new_user_calendar_assoc);

        $stmt->execute();
        
    }
    else var_dump("FAILED TO ADD");
}

function getAllCalendar($user_id){
    $conn = connect_db();

    $query = "SELECT calendar_name FROM tb_calendar, tb_user_calendar_assoc WHERE (tb_user_calendar_assoc.user_id = $user_id) AND (tb_user_calendar_assoc.calendar_id = tb_calendar.calendar_id);";

    $stmt = $conn->prepare($query);
    $stmt->execute();

    return $stmt->fetchAll();
}