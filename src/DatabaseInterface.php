<?php


namespace App;


interface DatabaseInterface
{
    public function insert($table, array $data);
    public function update($table, array $data, array $where);
    public function getAll($sql, $class_name = 'stdClass');
    public function get($sql, $class_name = 'stdClass');
    public function query($sql);

}