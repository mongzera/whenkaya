<?php

namespace Src\Controller;

use Src\Middleware\Auth;

class AuthenticationController extends BaseController{
    public function login(){

    }

    public function create_account(){
        if(Auth::user()) redirect("home");
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
        if(Auth::user()) redirect("home");
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
}