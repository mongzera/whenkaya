<?php

namespace Src\Model;

use Src\Model\BaseModel;

class CalendarLinkShareModel extends BaseModel{
    function __construct(){
        $this->table_name = "tb_calendar_link_share_model";

        $this->fields = [
            'user_id' => 'INT NOT NULL',
            'link' => 'CHAR(36) NOT NULL',
            'calendar_id' => 'INT NOT NULL',
            'privilage_level' => 'INT(1) NOT NULL',
            'duration' => 'INT NOT NULL' //days
        ];

        $this->foreignKey('user_id', 'tb_user_model', 'id');
        $this->foreignKey('calendar_id', 'tb_calendar_model', 'id');
    }
}