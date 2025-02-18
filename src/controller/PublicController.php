<?php
namespace Src\Controller;

use Src\Middleware\Auth;

class PublicController extends BaseController{

    
    public function index(){
        if(Auth::user()) redirect("dashboard");
    }
}

?>