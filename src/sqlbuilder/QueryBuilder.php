<?php

namespace Src\SqlBuilder;

class TableBuilder{


    protected $query = '';
    protected $columnNames = [];

    public static function create($tbname){
        $qb = new TableBuilder();
        $qb->concatQuery("CREATE");
        $qb->table($tbname);
        return $qb;
    }

    public function table($tbname){
        $this->concatQuery(" TABLE IF NOT EXISTS $tbname (:column_names);");
        return $this;
    }

    public function addColumn(ColumnName $column){
        array_push($this->columnNames, $column->getSql());
        return $this;
    }

    public function concatQuery($code){
        $this->query .= $code;
    }

    public function stringify_columns(){
        $output = "";
        $i = 0;
        foreach($this->columnNames as $column){
            $output .= ($i > 0 ? ", " : "") . $column;
            $i++;
        }

        return $output;
    }

    public function getQuery(){
        $query = preg_replace('/:column_names/', $this->stringify_columns(), $this->query);

        return $query;
    }
}

class ColumnName{

    protected $columnSql = "";

    public static function name($columnname){
        $field = new ColumnName();
        $field->concatQuery($columnname);
        return $field;
    }

    public function datatype($datatype){
        $this->concatQuery(" $datatype");
        return $this;
    }

    public function unsigned(){
        $this->concatQuery(" UNSIGNED");
        return $this;
    }

    public function notnull(){
        $this->concatQuery(" NOT NULL");
        return $this;
    }

    public function a_i(){
        $this->concatQuery(" AUTO_INCREMENT");
        return $this;
    }

    public function default($value){
        $this->concatQuery(" DEFAULT '$value'");
        return $this;
    }


    public function concatQuery($code){
        $this->columnSql .= $code;
    }

    public function getSql(){
        return $this->columnSql;
    }
}