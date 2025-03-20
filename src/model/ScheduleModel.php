<?php
namespace Src\Model;

use Src\Model\BaseModel;

class ScheduleModel extends BaseModel{
    function __construct(){
        $this->table_name = "tb_calender_schedule_model";

        $this->fields = [
            'schedule_title' => 'VARCHAR(50) NOT NULL',
<<<<<<< Updated upstream
            'schedule_description' => 'VARCHAR(50) NOT NULL',
            'schedule_start' => 'DATETIME NOT NULL',
            'schedule_end' => 'DATETIME NOT NULL',
            'calendar_id' => 'INT NOT NULL'
=======
            'schedule_description' => 'VARCHAR(512) NOT NULL',
            'schedule_start' => 'VARCHAR(10) NOT NULL',
            'schedule_end' => 'VARCHAR(10) NOT NULL',
            'schedule_date' => 'DATETIME NOT NULL',
            'schedule_type' => 'INT(1) NOT NULL',
            'color_hue' => 'VARCHAR(7) NOT NULL',
            'calendar_id' => 'INT NOT NULL',
>>>>>>> Stashed changes
        ];

        $this->foreignKey('calendar_id', 'tb_calendar_model', 'id');
    }
}