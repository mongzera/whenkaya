<?php

use Tests\Lemon;

class QueryBuilderTest extends \PHPUnit\Framework\TestCase {
    public function testCreateTable(){
        $table = Lemon::create_table('test_table');
        $table->column(Lemon::column("id")->int()->notnull()->unsigned());
        $table->column(Lemon::column("first_name")->varchar(50)->notnull());
        $table->column(Lemon::column("last_name")->varchar(50)->notnull());
        $table->column(Lemon::column("weight")->varchar(5)->default(20));

        $table->primary_key("pk_id", 'id');
        $table->foreign_key('fk_fname', 'first_name', 'tb_customer', 'first_name');
        

        $this->assertTrue(" ", $table->stringify());
    }

    public function testSelect(){
        $select = Lemon::select('column_name', 'column_name2')->from('table_name');
        

        $this->assertTrue(" ", $select->stringify());
    }
}

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
    public $_columnName = "", $_dataType = "", $_unsigned = "", $_notNull = "", $_ai = "", $_default = "";

    public static function name($columnname){
        $field = new ColumnName();
        $field->concatQuery($columnname);
        $field->_columnName = $columnname;
        return $field;
    }

    public function datatype($datatype){
        $this->_dataType = $datatype;
        $this->concatQuery(" $datatype");
        return $this;
    }

    public function unsigned(){
        $this->_unsigned = "UNSIGNED";
        $this->concatQuery(" UNSIGNED");
        return $this;
    }

    public function notnull(){
        $this->_notNull = "NOT NULL";
        $this->concatQuery(" NOT NULL");
        return $this;
    }

    public function a_i(){
        $this->_ai = "AUTO_INCREMENT";
        $this->concatQuery(" AUTO_INCREMENT");
        return $this;
    }

    public function default($value){
        $this->_default = "DEFAULT '$value'";
        $this->concatQuery(" DEFAULT '$value'");
        return $this;
    }


    public function concatQuery($code){
        $this->columnSql .= $code;
    }

    public function getSql(){
        return $this->_columnName . " " . $this->_dataType . " " . $this->_unsigned . " " . $this->_notNull . " " . $this->_ai . " " . $this->_default;
        //return $this->columnSql;
    }
}

?>
