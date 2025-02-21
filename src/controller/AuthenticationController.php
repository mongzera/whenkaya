<?php

namespace Src\Controller;

use Src\Db\Database;
use Src\Middleware\Auth;
use Src\Model\UserModel;

class AuthenticationController extends BaseController{

    public function create_account(){
        if(Auth::user()) redirect("dashboard");

        //create new user logic
        if(isPost()){
            $username = cleanRequest($_POST['username']);
            $password = cleanRequest($_POST['password']);
            $retype_password = cleanRequest($_POST['retype-password']);

            $hashed = password_hash($password, PASSWORD_DEFAULT);

            if(!($password === $retype_password)) echo "Password mismatch! $password != $retype_password";
            
            if(db_create_user($username, $hashed)){
                Auth::authenticate_user($username, $password);
                redirect("dashboard");
            
            }
        }

        $content = [
            "title" => "Create Account",
            "head" => "../src/views/default_head.php",
            "body" => "../src/views/auth/create-account.view.php"
        ];

        $static = [
            "css" => ['css/global.css', 'css/theme.css', 'css/auth/auth.css'],
            "js"  => []
        ];

        render_page($content, $static);
    }

    public function login_account(){
        if(Auth::user()) redirect("dashboard");

        //login user logic
        if(isPost()){
            $username = cleanRequest($_POST['username']);
            $password = cleanRequest($_POST['password']);

            if(Auth::authenticate_user($username, $password)){
                redirect("dashboard");
            }
        }

        $content = [
            "title" => "Login Account",
            "head" => "../src/views/default_head.php",
            "body" => "../src/views/auth/login.view.php"
        ];

        $static = [
            "css" => ['css/global.css', 'css/theme.css', 'css/auth/auth.css'],
            "js"  => []
        ];

        render_page($content, $static);
    }

    public function logout(){
        Auth::logout();
        redirect("login_account_get");
    }
}