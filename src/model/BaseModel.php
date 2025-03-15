<?php

namespace Src\Model;

use PDO;
use RuntimeException;

require_once "../src/utilities/db.util.php";
require_once "../src/utilities/orm.util.php";


abstract class BaseModel{

    protected string $table_name;
    protected array $fields;
    private $constraints;
    private $constraintCount = 0;
    private $row;

    public function getId(int $id) : array{
        return $this->getColumn('id', $id);
    }

    public function getColumn($columnname, $value){
        $conn = connect_db();

        $query = "SELECT * FROM {$this->table_name} WHERE `{$columnname}` = '{$value}' LIMIT 1";

        $stmt = $conn->prepare($query);
        $stmt->execute();

        $this->row = $stmt->fetchAll()[0];

        return $this->row;
    }

    public function getAllFromRelatedModel($model_name, $foreign_key, $target_column, $target_value, $select = "*"){
        $conn = connect_db();

        $query = "SELECT {$select} FROM `{$model_name}`, `{$this->table_name}` WHERE {$this->table_name}.{$foreign_key} = {$model_name}.id AND {$this->table_name}.{$target_column} = {$target_value};";
        $stmt = $conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function insert($data){
        $conn = connect_db();

        $data = array_intersect_key($data, $this->fields);

        $keys = delimiter(array_keys($data), ",", $wrap="`?`");
        $values = delimiter(array_values($data), ",", $wrap="'?'");

        $query = "INSERT INTO `{$this->table_name}` ({$keys}) VALUES ($values);";

        $stmt = $conn->prepare($query);

        $status = $stmt->execute();

        $this->getId($conn->lastInsertId());

        return $status;
    }

    public function migrate(){
        $conn = connect_db();

        $query = "CREATE TABLE IF NOT EXISTS `{$this->table_name}`";
        $fields = "`id` INT PRIMARY KEY AUTO_INCREMENT,";

        //add user-defined fields
        foreach($this->fields as $colName => $datatype){
            $fields .= "\n`{$colName}` {$datatype},";
        }

        //add date-fields
        $fields .= "\n`date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,";
        $fields .= "\n`date_updated` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP";
        
        $stmt = "{$query} ({$fields}" . ($this->constraintCount > 0 ? ", {$this->constraints});" : ");");


        echo '<br>' . $this->table_name .':<p style="background-color: black; color: yellow;">' . $stmt . '</p>';

        return $conn->exec($stmt);
    }

    protected function foreignKey($colname, $reference_table, $reference_colname){
        $this->constraints .= ($this->constraintCount++ > 0 ? ",\n" : "") . "CONSTRAINT `FK_{$colname}_{$this->table_name}` FOREIGN KEY ({$colname}) REFERENCES {$reference_table}(`{$reference_colname}`) ON UPDATE CASCADE ON DELETE CASCADE \n";
    }

    public function get($colname){
        return $this->row[$colname];
    }
}