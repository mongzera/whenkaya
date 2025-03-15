<?php
namespace Src\Model;

use Src\Model\BaseModel;

class ScheduleModel extends BaseModel{
    function __construct(){
        $this->table_name = "tb_calender_schedule_model";

        $this->fields = [
            'schedule_title' => 'VARCHAR(50) NOT NULL',
            'schedule_description' => 'VARCHAR(50) NOT NULL',
            'schedule_start' => 'DATETIME NOT NULL',
            'schedule_end' => 'DATETIME NOT NULL',
            'calendar_id' => 'INT NOT NULL'
        ];

        $this->foreignKey('calendar_id', 'tb_calendar_model', 'id');
    }
}