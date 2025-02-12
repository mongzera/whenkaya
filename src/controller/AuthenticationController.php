<?php

namespace Src\Controller;

use Src\Db\Database;
use Src\Middleware\Auth;
use Src\Model\UserModel;

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

        $db = new Database();
        $db->createDatabase("test_orm_db");
        $db->setDatabase("test_orm_db");
        
        $userModel = new UserModel();
        $db->createTable($userModel);

        $userModel->insertRow(["ethan", "gamat"]);
        $userModel->insertRow(["ethan2", "gamat2"]);
        $userModel->insertRow(["ethan3", "gamat3"]);
        $userModel->insertRow(["ethan4", "gamat4"]);
        $userModel->insertRow(["ethan5", "gamat5"]);

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