<?php

namespace Src\Model;

enum DataType{
    case VARCHAR;
    case TEXT;
    case INT;
    case TINY_INT;
    CASE SMALL_INT;
    case BIG_INT;
    case DATE_TIME;

}

class ModelColumnAttrib{
    public $fieldName;
    public $datatype;
    public $length;
    public $unsigned;
    public $nullable;
    public $defaultValue;

    public function __construct($fieldName, $datatype, $length, $unsigned = true, $nullable = true, $defaultValue = '0')
    {
        $this->fieldName = $fieldName;
        $this->datatype = $datatype;
        $this->length = $length;
        $this->unsigned = $unsigned;
        $this->nullable = $nullable;
        $this->defaultValue = $defaultValue;
    }
}

abstract class BaseModel{
    protected $primaryKey;
    protected $nonkey_fields = [];
    protected $created_at = "date_created";
    protected $updated_at = "date_updated";

    public function __construct() {

    }

    public abstract function createModel();
    protected function addNonKeyField($fieldName, $datatype, $length, $unsigned, $nullable){
        $modelField = new ModelColumnAttrib($fieldName, $datatype, $length, $unsigned, $nullable);
        array_push($nonkey_fields, $modelField);
    }

    public function datestamp(){
        $this->addNonKeyField($this->created_at, DataType::DATE_TIME, 0, false, false);
        $this->addNonKeyField($this->updated_at, DataType::DATE_TIME, 0, false, false);
    }
    
}

