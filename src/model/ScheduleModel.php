<?php
namespace Src\Model;

use Src\Model\BaseModel;

class ScheduleModel extends BaseModel{
    static int $SCHEDULE = 0;
    static int $REMINDER = 1;
    static int $EVENT = 2;
    

    function __construct(){
        $this->table_name = "tb_calendar_schedule_model";

        $this->fields = [
            'schedule_title' => 'VARCHAR(50) NOT NULL',
            'schedule_description' => 'VARCHAR(50) NOT NULL',
            'schedule_start' => 'VARCHAR(10) NOT NULL',
            'schedule_end' => 'VARCHAR(10) NOT NULL',
            'schedule_type' => 'INT(1) NOT NULL',
            'color_hue' => 'VARCHAR(7) NOT NULL',
            'calendar_id' => 'INT NOT NULL'
        ];

        $this->foreignKey('calendar_id', 'tb_calendar_model', 'id');
    }
}