<?php
    namespace Tests;
    //namespace Gamat\SqlBuilder\Lemon;

    // $user_table = SQL::create_table('user_table');

    // $user_table->column(SQL::create_column('first_name')->varchar(50)->default('ethan'));
    // $user_table->column(SQL::create_column('last_name')->varchar(50)->default('gamat'));
    // $user_table->column(SQL::create_column('sex')->varchar(6)->default('male'));
    // $user_table->column(SQL::create_column('age')->int()->default(18)->unsigned()->notnull());
    // $user_table->column(SQL::create_column('id')->int()->a_i()->unsigned()->notnull());

    // $uer_table->constraint("pk_id")->primary_key('id');
    // $user_table->constraint("fk_age")->foreign_key('age')->reference('table_name', 'column_name');

    

    class Lemon{
        static function create_table($table_name){
            return CreateTable::name($table_name);
        }

        static function column($table_column){
            return Column::name($table_column);
        }

        static function select($columns){
            return Select::column($columns);
        }
    }

    /*
        @ToBeImplemented
    */

    class Select{
        private $sql = '';
        private $_columns = '';
        private $_tables = '';

        private function __construct($columns)
        {
            if(is_array($columns)){
                foreach($columns as $idx => $column){
                    if($idx > 0) $this->_columns .= ',';
                    $this->_columns .= $column;
                }

                return $this;
            }

            $this->_columns = $columns;
        }

        public static function column($column){
            return new Select($column); 
        }

        public function from($table_name){
            if(is_array($table_name)){
                foreach($table_name as $idx => $table){
                    if($idx > 0) $this->_tables .= ',';
                    $this->_tables .= $table;
                }

                return $this;
            }

            $this->_tables = $table_name;
            return $this;
        }

        public function stringify(){
            return "SELECT " . $this->_columns . " FROM " . $this->_tables;
        }
    }

    /*
        @ToBeImplemented
    */
    class Where{
        protected $_where = "WHERE ";



        //comparison operators

        public function both($comparison1, $comparison2){

        }

        public static function equals($column, $value){
            return "WHERE $column = $value";
        }

    }

    class CreateTable{
        public $table_name;

        //contained here are stringified SQL commands of columns
        //e.g [0] = ['id INT NOT NULL']
        //e.g [1] = ['first_name VARCHAR(50) NOT NULL']
        //e.g [2] = ['last_name VARCHAR(50) NOT NULL']
        //e.g [3] = ['age INT UNSIGNED NOT NULL AUTO_INCREMENT']
        private $_columns = [];

        //contained here are stringified SQL commands for constraints of columns
        //e.g [0] = ['INDEX 'idx_name' (first_name, last_name) USING BTREE ']
        //e.g [1] = ['CONSTRAINT pk_id PRIMARY KEY (id)']
        //e.g [2] = ['CONSTRAINT fk_age FOREIGN KEY (age) REFERENCES OtherTableName(OtherColumnName)']
        private $_constraints = [];

        public static function name($table_name){
            $table = new CreateTable();
            $table->table_name = $table_name;

            return $table;
        }

        public function column($column){

            array_push($this->_columns, $column->stringify());
            return $this;
        }

        //contraints here
        public function primary_key($constrain_name, $column_name){
            $constraint = "CONSTRAINT $constrain_name PRIMARY KEY ($column_name)";
            array_push($this->_constraints, $constraint);
            return $this;
        }

        public function foreign_key($constrain_name, $column_name, $reference_table, $reference_column){
            $constraint = "CONSTRAINT $constrain_name FOREIGN KEY ($column_name) REFERENCES $reference_table($reference_column)";
            array_push($this->_constraints, $constraint);
            return $this;
        }

        public function stringify(){
            $sql = "CREATE TABLE IF NOT EXISTS $this->table_name (";

            //add columns
            foreach($this->_columns as $idx => $column){
                if($idx > 0) $sql .= ', ';
                $sql .= $column;
            }

            

            //add constraints
            foreach($this->_constraints as $idx => $constraint){
                $sql .= ', ';
                $sql .= $constraint;
            }

            $sql .= ');';

            return $sql;

        }
    }

    class Column{
        public $_column_name = '';
        private $_datatype = '';
        private $_unsigned = false;
        private $_zero_fill = false;
        private $_auto_icremenent = false;
        private $_notnull = false;
        private $_default = null;
        

        public static function name($colname){
            $column = new Column();
            $column->_column_name = $colname;

            return $column;
        }

        //datatype
        public function int(){
            $this->_datatype = "INT";
            return $this;
        }

        public function varchar($len){
            $this->_datatype = "VARCHAR($len)";
            return $this;
        }

        public function unsigned(){
            $this->_unsigned = true;
            return $this;
        }

        public function zero_fill(){
            $this->_zero_fill = true;
            return $this;
        }

        public function a_i(){
            $this->_auto_icremenent = true;
            return $this;
        }

        public function default($default){
            $this->_default = $default;
            return $this;
        }

        public function notnull(){
            $this->_notnull = true;
            return $this;
        }

        public function stringify(){
            $command = 
             $this->_column_name . ' ' . 
             $this->_datatype . 
            ($this->_unsigned ? ' UNSIGNED' : '') . 
            ($this->_zero_fill ? ' ZERO FILL' : '') . 
            ($this->_notnull ? ' NOT NULL' : '') . 
             (is_numeric($this->_default) ? " DEFAULT " . $this->_default : ( isset($this->_default) ? " DEFAULT '$this->_default'" : "")) . 
            ($this->_auto_icremenent ? " AUTO INCREMENET" : "");

            return $command;
        }
    }

?>