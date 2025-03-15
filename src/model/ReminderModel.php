<?php
namespace Src\Model;

use Src\Model\BaseModel;

class ReminderModel extends BaseModel{
    function __construct(){
        $this->table_name = "tb_calender_schedule_model";

        $this->fields = [
            'reminder_title' => 'VARCHAR(50) NOT NULL',
            'reminder_description' => 'VARCHAR(50) NOT NULL',
            'reminder_time' => 'DATETIME NOT NULL',
            'calendar_id' => 'INT NOT NULL'
        ];

        $this->foreignKey('calendar_id', 'tb_calendar_model', 'id');
    }
}