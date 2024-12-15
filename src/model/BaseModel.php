<?php

namespace Src\Model;

enum DataType{
    case VARCHAR;
    case INT;
    case TINY_INT;
    case SMALL_INT;
    case BIG_INT;
    case TIMESTAMP;
}

class ModelColumnAttrib{
    public $fieldName;
    public $datatype;
    public $length;
    public $unsigned;
    public $nullable;
    public $defaultValue;
    public $isUnique;

    public function __construct($fieldName, $datatype, $length, $unsigned = false, $nullable = true, $defaultValue = '', $isUnique = false)
    {
        $this->fieldName = $fieldName;
        $this->datatype = $datatype;
        $this->length = $length;
        $this->unsigned = $unsigned;
        $this->nullable = $nullable;
        $this->defaultValue = $defaultValue;
        $this->isUnique = $isUnique;
    }

    protected function fieldConstraints(){
        $constraints = "";

        //data type
        $constraints = $constraints . match($this->datatype){
            DataType::VARCHAR => "VARCHAR($this->length) ",
            DataType::INT => "INT ",
            DataType::TINY_INT => "TINYINT ",
            DataType::SMALL_INT => "SMALLINT ",
            DataType::BIG_INT => "BIGINT ",
            DataType::TIMESTAMP => "TIMESTAMP "
        };

        //unsigned constraints
        $constraints = $constraints . ($this->unsigned ? "UNSIGNED " : "");

        //not null / null
        $constraints = $constraints . (!$this->nullable ? "NOT NULL " : "");

        //default
        $constraints = $constraints . ($this->defaultValue !== '' ? "DEFAULT " . $this->defaultValue : "");

        //auto-increment [only if integer]
        //implement!
        $constraints = $constraints . "";

        

        //zero fill [only for integers]
        $constraints = $constraints . "";

        //unique
        $constraints = $constraints . ($this->isUnique ? "UNIQUE " : " ");

        return $constraints;
    }

    public function asSQL(){
        $sql = "";

        $sql = $sql . $this->fieldName . " ";
        $sql = $sql . $this->fieldConstraints();

        return $sql;
    }
}

abstract class BaseModel{
    protected string $model_name;
    protected ModelColumnAttrib $primaryKey;
    protected $nonkey_fields = [];
    protected string $created_at = "date_created";
    protected string $updated_at = "date_updated";

    public function __construct($model_name, $pk) {
        $this->model_name = $model_name;
        $this->primaryKey = new ModelColumnAttrib($pk, DataType::INT, 0, true, false, '', false);
    }

    public abstract function createModel();
    protected function addNonKeyField($fieldName, $datatype, $length, $unsigned = false, $nullable = true, $defaultValue = '', $isUnique = false){
        $modelField = new ModelColumnAttrib($fieldName, $datatype, $length, $unsigned, $nullable, $defaultValue, $isUnique);
        array_push($this->nonkey_fields, $modelField);
    }

    public function datestamp(){
        $this->addNonKeyField($this->created_at, DataType::TIMESTAMP, 0, false, false, $defaultValue = "CURRENT_TIMESTAMP()");
        $this->addNonKeyField($this->updated_at, DataType::TIMESTAMP, 0, false, false, $defaultValue = "CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP()");
    }
    
    public function getModelName(){
        return $this->model_name;
    }

    public function getPrimaryKey() : ModelColumnAttrib{
        return $this->primaryKey;
    }

    public function getNonKeyFields(){
        return $this->nonkey_fields;
    }
}

