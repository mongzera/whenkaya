<?php

function cleanRequest($var){
    if(isset($var)) {
        $var = htmlspecialchars($var);
        return $var;
    }

    return false;
}