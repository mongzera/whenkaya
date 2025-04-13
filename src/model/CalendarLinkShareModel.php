<?php

namespace Src\Model;

use Src\Model\BaseModel;

class CalendarModel extends BaseModel{
    function __construct(){
        $this->table_name = "tb_calendar_link_share_model";

        $this->fields = [
            'link' => 'VARCHAR(8) NOT NULL',
            'calendar_id' => 'INT NOT NULL',
            'privilage_level' => 'INT(1) NOT NULL',
            'duration' => 'INT NOT NULL' //days
        ];

        $this->foreignKey('calendar_id', 'tb_calendar_model', 'id');
    }
}