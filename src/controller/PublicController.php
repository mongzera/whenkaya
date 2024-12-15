<?php
namespace Src\Controller;


class PublicController extends BaseController{

    
    public function index(){
        //if(!Auth::user()) redirect("/login");
        echo 'index';
    }
}

?>