<?php

namespace Src\Model;

class BaseModel{
    private $fields = [];
    
    public function __construct($fields) {
        $this->fields = $fields;


    }
}