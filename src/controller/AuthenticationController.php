<?php

namespace Src\Controller;

use Src\Db\Database;
use Src\Middleware\Auth;
use Src\Model\UserModel;

class AuthenticationController extends BaseController{

    public function create_account(){
        if(Auth::user()) redirect("dashboard");


        $error = "";
        //create new user logic
        if(isPost()){

            $firstname = cleanRequest($_POST['firstname']);
            $lastname = cleanRequest($_POST['lastname']);
            $username = cleanRequest($_POST['username']);
            $password = cleanRequest($_POST['password']);
            $email = cleanRequest($_POST['email']);
            $retype_password = cleanRequest($_POST['retype-password']);

            $isAllInputValid = true;
          

            $hashed = password_hash($password, PASSWORD_DEFAULT);

            if(!($password === $retype_password)) {
                $error =  "<p style='color: red;'>Password mismatch!</p>";
                $isAllInputValid = false;
            }

            if($firstname == ''){
                $error =  "<p style='color: red;'>First name cannot be blank!</p>";
                $isAllInputValid = false;
            }

            if($lastname == ''){
                $error =  "<p style='color: red;'>Last Name cannot be blank!</p>";
                $isAllInputValid = false;
            }

            if($username == ''){
                $error =  "<p style='color: red;'>Username name cannot be blank!</p>";
                $isAllInputValid = false;
            }

            if($password == ''){
                $error =  "<p style='color: red;'>Password cannot be blank!</p>";
                $isAllInputValid = false;
            }

            if($email == ''){
                $error =  "<p style='color: red;'>Username name cannot be blank!</p>";
                $isAllInputValid = false;
            }

            if($isAllInputValid){
                $user = new UserModel();

                if($user->insert([
                    'first_name' => $firstname,
                    'last_name' => $lastname,
                    'username' => $username,
                    'email' => $email,
                    'password_hashed' => $hashed
                ])){
                    Auth::authenticate_user($username, $password);
                    redirect("dashboard");
                }
            }
            
             //if(db_create_user($username, $hashed)){
               //  Auth::authenticate_user($username, $password);
               //redirect("dashboard");
            //}
        }
        
        $content = [
            "title" => "Create Account",
            "head" => "../src/views/default_head.php",
            "body" => "../src/views/auth/create-account.view.php",
            "error" => $error,
        ];

        $static = [
            "css" => ['css/global.css', 'css/theme.css', 'css/auth/auth.css'],
            "js"  => []
        ];

        render_page($content, $static);
    }

    public function login_account(){
        if(Auth::user()) redirect("dashboard");

        $error = "";

        //login user logic
        if(isPost()){
            $username = cleanRequest($_POST['username']);
            $password = cleanRequest($_POST['password']);
            
            if(Auth::authenticate_user($username, $password)){
                redirect("dashboard");

            }else{
                $error = "<p style='color: red;'>Account does not exist!</p>";
            }

            if (empty($username) && empty($password)) {
                $error = "<p style='color: red;'>Please input the necessary information, the boxes cannot be blank!</p>";
                $isAllInputValid = false;
            }

            if($username == ''){
                $error =  "<p style='color: red;'>Username name cannot be blank!</p>";
                $isAllInputValid = false;
            }
            
            if($password == ''){
                $error =  "<p style='color: red;'>Password name cannot be blank!</p>";
                $isAllInputValid = false;
            }

        }

        $content = [
            "title" => "Login Account",
            "head" => "../src/views/default_head.php",
            "body" => "../src/views/auth/login.view.php",
            "error" => $error
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