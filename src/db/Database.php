<?php

class Datebase{
    protected $url = "localhost";
    protected $username = "root";
    protected $password = "";

    public function connect(){
        try{
            $_conn = new PDO("mysql:host=$this->url", $this->username, $this->password);
            // set the PDO error mode to exception
            $_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $_conn;
        }catch(PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function createDatabase($db_name){
        $sql = "CREATE DATABASE $db_name";

        $conn = $this->connect();
        try{
            $conn->exec($sql);
        }catch(PDOException $e){
            echo $e->getMessage();
        }
    }

    public function setDatabase($db_name){
        $sql = "USE DATABASE $db_name";

        $conn = $this->connect();
        try{
            $conn->exec($sql);
        }catch(PDOException $e){
            echo $e->getMessage();
        }
    }
}