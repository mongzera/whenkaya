<?php

namespace Src\Model;
use Src\Model\BaseModel;

class NoteModel extends BaseModel{
    function __construct(){
        $this->table_name = "tb_note_model";

        $this->fields = [
            'note_title' => 'VARCHAR(50) NOT NULL',
            'note_description' => 'VARCHAR(512) NOT NULL',
            'note_date' => 'DATETIME NOT NULL',
            'color_hue' => 'VARCHAR(7) NOT NULL',
            'calendar_id' => 'INT NOT NULL'
        ];

        $this->foreignKey('calendar_id', 'tb_calendar_model', 'id');
    }
}