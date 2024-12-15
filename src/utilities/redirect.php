<?php

require_once "../public/index.php";

function redirect($name){
    header("Location: " . getRouteName($name));
}