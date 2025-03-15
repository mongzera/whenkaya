<?php
namespace Src\Model;
use Src\Model\BaseModel;

class CalendarUserModel extends BaseModel{
    function __construct(){
        $this->table_name = "tb_user_calendar_assoc";

        $this->fields = [
            'user_id' => 'INT NOT NULL',
            'calendar_id' => 'INT NOT NULL',
            'privilage_level' => 'INT(1) NOT NULL'
        ];

        $this->foreignKey('user_id', 'tb_user_model', 'id');
        $this->foreignKey('calendar_id', 'tb_calendar_model', 'id');
    }
}