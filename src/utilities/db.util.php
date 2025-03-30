<?php

use Src\Db\Database;
use Src\Model\CalendarModel;
use Src\Model\CalendarUserModel;

define("DUPLICATE_ENTRY", 23000);

function connect_db(){
    $dbInstance = new Database();
    $dbInstance->setDatabase("whenkaya_db");
    return $dbInstance->connect();
}

//@returns boolean
function db_create_user($username, $password){
    $conn = connect_db();
    $query = "INSERT INTO tb_basic_user_info (`first_name`, `last_name`, `password`, `date_created`, `date_modified`) VALUES ('$username', '', '$password', NOW(), NOW());";
    $stmt = $conn->prepare($query);
    

    try{
        $stmt->execute();
        return true;

    }catch(PDOException $e){
        $errCode = $e->getCode();
        //duplicate entry
        if($errCode == DUPLICATE_ENTRY){
            echo "User already exists!";
            return false;
        }
    }
}

function requestCardsForTheDate($date, $userId){
    $conn = connect_db();

    //use ORM
    //create columns for DAY, MONTH, YEAR
    //fix js request
}

// function insertNewCalendar($name, $userId, $privilage_level){
    



//     // $conn = connect_db();

//     // $create_new_calendar = "INSERT INTO tb_calendar (`calendar_name`, `date_created`, `date_modified`) VALUES ('$name', NOW(), NOW())";
//     // $stmt = $conn->prepare($create_new_calendar);
//     // if($stmt->execute() == true){
//     //     $retrieve_recent_calendar_id = $conn->lastInsertId();

//     //     $new_user_calendar_assoc = "INSERT INTO tb_user_calendar_assoc (`user_id`, `calendar_id`, `user_role`, `date_created`, `date_modified`) VALUES ($userId, $retrieve_recent_calendar_id, $role, NOW(), NOW());";
//     //     $stmt = $conn->prepare($new_user_calendar_assoc);

//     //     try{
//     //         $stmt->execute();
//     //     }
//     //     catch(PDOException $e){
//     //         echo $e->getMessage();
//     //     }
        
//     // }
//     // else var_dump("FAILED TO ADD");
// }

function getAllCalendar($user_id){
    $conn = connect_db();

    $query = "SELECT calendar_name FROM tb_calendar, tb_user_calendar_assoc WHERE (tb_user_calendar_assoc.user_id = $user_id) AND (tb_user_calendar_assoc.calendar_id = tb_calendar.calendar_id);";

    $stmt = $conn->prepare($query);
    $stmt->execute();

    return $stmt->fetchAll();
}

function delimiter($array, $delimiter, $wrap = '?'){
    $i = 0;

    $out = "";

    foreach($array as $a){
        $out .= str_replace("?", $a, $wrap) . ($i < count($array)-1 ? $delimiter : "");
        $i++;
    }

    return $out;
}