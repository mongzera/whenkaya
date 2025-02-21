<?php
namespace Src\Controller;

use Src\Middleware\Auth;

class PublicController extends BaseController{

    public function index(){
        if(Auth::user()) redirect("dashboard");
        //redirect("login_account_get");

        $content = [
            "title" => "Whenkaya!",
            "head" => "../src/views/default_head.php",
            "body" => "../src/views/home.view.php"
        ];

        $static = [
            "css" => ['css/global.css', 'css/theme.css', 'css/home.css'],
            "js"  => []
        ];

        render_page($content, $static);
    }
}

?>