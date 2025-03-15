<?php

namespace Src\Model;

use Src\Model\BaseModel;

class CalendarModel extends BaseModel{
    function __construct(){
        $this->table_name = "tb_calendar_model";

        $this->fields = [
            'calendar_name' => 'VARCHAR(50) NOT NULL',
        ];
    }
}