<?php

namespace Src\Middleware;

use PDOException;
use Src\Model\UserModel;

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
        $user = new UserModel();

        $row = $user->getColumn('username', $username);

        if($row == false) return false;

        $hashed_password = $row['password_hashed'];

        if(password_verify($password, $hashed_password)){
            Auth::initialize($user);
            return true;
        }

        echo 'Incorrect Information';
        return false;


        // $conn = connect_db();
        // $stmt = $conn->prepare("SELECT `password`, `user_id` FROM tb_basic_user_info WHERE `first_name` = '$username'");
        // $stmt->execute();

        // //what if first_name specified doesnt exist?
        // $rows = $stmt->fetchAll();
        // $columns = $rows[0];
        // if($columns == null) return false;

        // $real_user_password = $columns['password']; //bcrypt encrypted

        // if(password_verify($password, $real_user_password)){
        //     //login user
        //     $userId = $columns['user_id'];
        //     Auth::initialize($userId);
        //     return true;
        // }

        // echo 'Incorrect information!';
        // return false;

    }

    //only call this function when you're sure the user logged in succesfully...
    public static function initialize($user){
        
        $_SESSION['user_authenticated'] = true;
        $_SESSION['user_identification'] = $user->get('id');
        $_SESSION['username'] = $user->get('username');
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