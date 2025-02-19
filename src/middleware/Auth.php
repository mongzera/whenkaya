<?php

namespace Src\Middleware;

use PDOException;

include_once "../src/utilities/db.util.php";
include_once "../src/utilities/redirect.php";

class Auth{
    
    public static function user(){
        if(!isset($_SESSION['user_authenticated'])) return false;

        return $_SESSION['user_authenticated'];
    }

    public static function redirectIfNotLoggedIn(){
        if(!Auth::user()) redirect("home");
    }

    public static function authenticate_user($username, $password){
        $conn = connect_db();
        $stmt = $conn->prepare("SELECT `password`, `user_id` FROM tb_basic_user_info WHERE `first_name` = '$username'");
        $stmt->execute();

        //what if first_name specified doesnt exist?
        $rows = $stmt->fetchAll();
        $columns = $rows[0];
        if($columns == null) return false;

        $real_user_password = $columns['password']; //bcrypt encrypted

        if(password_verify($password, $real_user_password)){
            //login user
            $userId = $columns['user_id'];
            Auth::initialize($userId);
            return true;
        }

        echo 'Incorrect information!';
        return false;

    }

    //only call this function when you're sure the user logged in succesfully...
    public static function initialize($userId){
        $conn = connect_db();
        
        $stmt = $conn->prepare("SELECT * FROM tb_basic_user_info WHERE `user_id` = '$userId';");
        
        try{
            $stmt->execute();
            $columns = $stmt->fetchAll()[0];

            $_SESSION['user_authenticated'] = true;
            $_SESSION['user_identification'] = $columns['user_id'];
            $_SESSION['username'] = $columns['first_name'];


        }catch(PDOException $e){
            $errCode = $e->getCode();
            //catch errors here
        }
    }

    public static function getUsername(){
        Auth::redirectIfNotLoggedIn();
        return $_SESSION['username'];
    }

    public static function getUserId(){
        Auth::redirectIfNotLoggedIn();
        return $_SESSION['user_identification'];
    }

    public static function logout(){
        session_destroy();
        Auth::redirectIfNotLoggedIn();
    }
}

?>