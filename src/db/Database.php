<?php


namespace Src\Db;

use Src\Model\BaseModel;
use PDO, PDOException;

class Database{
    public static $url = "localhost";
    public static $username = "root";
    public static $password = "";
    public static $database = "";

    public function connect(){
        try{
            $_conn = new PDO("mysql:host=" . Database::$url . ";", Database::$username, Database::$password);
            
            // set the PDO error mode to exception
            $_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            //use database
            if(Database::$database != "") $_conn->exec("USE " . Database::$database);

            return $_conn;
        }catch(PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function createDatabase($db_name){
        $sql = "CREATE DATABASE IF NOT EXISTS `$db_name`";

        $conn = $this->connect();
        try{
            $conn->exec($sql);
        }catch(PDOException $e){
            echo $e->getMessage();
        }
    }

    public function setDatabase($db_name){
        Database::$database = $db_name;
    }


    public function createTable(BaseModel $model){
        $model->createModel();
        $model_name = $model->getModelName();
        $pk = $model->getPrimaryKey();

        $sql = "CREATE TABLE IF NOT EXISTS $model_name (";
        $sql = $sql . $pk->asSQL() . " AUTO_INCREMENT, ";

        foreach($model->getNonKeyFields() as $fields){
            $sql = $sql . $fields->asSQL() . ", ";
        }

        //set pk, fk, otherss...
        $sql = $sql . " PRIMARY KEY ($pk->fieldName));";

        //echo $sql . '<br><br>';

        $conn = $this->connect();
        try{
            $conn->exec($sql);
        }catch(PDOException $e){
            echo $e->getMessage();
        }

    }
}