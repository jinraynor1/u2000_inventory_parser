<?php


namespace App;


interface DatabaseInterface
{
    public function insert($table, array $data);
    public function delete($table, array $where = array());

    public function update($table, array $data, array $where);
    public function selectOne($table, array $columns, array $where = array(), $class_name = 'stdClass');
    public function selectAll($table, array $columns, array $where = array(), $class_name = 'stdClass');
    public function getAll($sql, $class_name = 'stdClass');
    public function get($sql, $class_name = 'stdClass');
    public function query($sql);
    public function quoteIdentifier($identifier);
    public function getDriver();

}