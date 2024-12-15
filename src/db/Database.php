<?php


namespace Src\Db;

use Src\Model\BaseModel;
use PDO, PDOException;

class Database{
    protected $url = "localhost";
    protected $username = "root";
    protected $password = "";
    protected $database = "";

    public function connect(){
        try{
            $_conn = new PDO("mysql:host=$this->url;", $this->username, $this->password);
            
            // set the PDO error mode to exception
            $_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            //use database
            if($this->database != "") $_conn->exec("USE $this->database");

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
        $this->database = $db_name;
    }


    public function createTable(BaseModel $model){
        $model->createModel();
        $model_name = $model->getModelName();
        $pk = $model->getPrimaryKey();

        $sql = "CREATE TABLE IF NOT EXISTS $model_name (";
        $sql = $sql . $pk->asSQL() . ", ";

        foreach($model->getNonKeyFields() as $fields){
            $sql = $sql . $fields->asSQL() . ", ";
        }

        //set pk, fk, otherss...
        $sql = $sql . " PRIMARY KEY ($pk->fieldName));";

        echo $sql . '<br><br>';

        $conn = $this->connect();
        try{
            $conn->exec($sql);
        }catch(PDOException $e){
            echo $e->getMessage();
        }

    }
}