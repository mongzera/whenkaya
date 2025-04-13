<?php



function generateUUID() {
    $millis = round(microtime(true) * 1000);              // Current time in ms
    $timeHex = str_pad(dechex($millis), 12, '0', STR_PAD_LEFT); // 12 hex chars
    $randomHex = bin2hex(random_bytes(12));               // 24 hex chars
    return $timeHex . $randomHex; 
}
