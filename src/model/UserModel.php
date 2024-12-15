<?php

namespace Src\Model;


class UserModel extends BaseModel{

    public function createModel(){
        $this->addNonKeyField("first_name", DataType::VARCHAR, 50, false, false);
        $this->addNonKeyField("last_name", DataType::VARCHAR, 50, false, false);
        $this->datestamp();
    }
}