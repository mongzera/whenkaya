<?php
namespace Src\Model;

use Src\Model\BaseModel;

class EventModel extends BaseModel{
    function __construct(){
        $this->table_name = "tb_calender_event_model";

        $this->fields = [
            'event_title' => 'VARCHAR(50) NOT NULL',
            'event_description' => 'VARCHAR(50) NOT NULL',
            'event_date' => 'DATETIME NOT NULL',
            'calendar_id' => 'INT NOT NULL'
        ];

        $this->foreignKey('calendar_id', 'tb_calendar_model', 'id');
    }
}