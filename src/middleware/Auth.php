<?php

namespace Src\Middleware;

class Auth{
    public static function user(){
        if(!isset($_SESSION['user_authenticated'])) return false;

        return $_SESSION['user_authenticated'];
    }
}

?>