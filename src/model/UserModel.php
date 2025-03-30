<?php
namespace Src\Model;
use Src\Model\BaseModel;

class UserModel extends BaseModel{
    function __construct(){
        $this->table_name = "tb_user_model";

        $this->fields = [
            'first_name' => 'VARCHAR(25) NOT NULL',
            'last_name' => 'VARCHAR(25) NOT NULL',
            'username' => 'VARCHAR(50) NOT NULL',
            'email' => 'VARCHAR(50) NOT NULL',
            'password_hashed' => 'VARCHAR(77) NOT NULL',
        ];
    }
}