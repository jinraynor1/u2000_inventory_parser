<?php


namespace App;


class DatabaseAdapter implements DatabaseInterface
{

    /**
     * @var \PDO
     */
    private $db;

    /**
     * @var string
     */
    private $quote_identifier_character;

    public function __construct(\PDO $db, $quote_identifier_character)
    {

        $this->db = $db;
        $this->quote_identifier_character = $quote_identifier_character;
    }

    /**
     * insert record
     *
     * @param  string $table table name
     * @param  array $data array of columns and values
     */
    public function insert($table,array $data)
    {
        $that = $this;
        $columns  = implode(',',array_map(function ($val) use ($that) {
            return $that->quoteIdentifier($val);
        }, array_keys($data)));


        //get values
        $values = array_values($data);

        $placeholders = array_map(function ($val) {
            return '?';
        }, array_keys($data));

        //convert array into comma seperated string
        $placeholders = implode(',', array_values($placeholders));

        $table = $this->quoteIdentifier($table);

        return  $this->run("INSERT INTO  $table($columns) VALUES ($placeholders)", $values);


    }

    /**
     * update record
     *
     * @param  string $table table name
     * @param  array $data array of columns and values
     * @param  array $where array of columns and values
     */

    public function update($table, array $data, array $where)
    {
        //merge data and where together
        $collection = array_merge($data, $where);

        //collect the values from collection
        $values = array_values($collection);

        //setup fields
        $fieldDetails = null;
        foreach ($data as $key => $value) {
            $fieldDetails .= "$key = ?,";
        }
        $fieldDetails = rtrim($fieldDetails, ',');

        //setup where
        $whereDetails = null;
        $i = 0;
        foreach ($where as $key => $value) {
            $whereDetails .= $i == 0 ? "$key = ?" : " AND $key = ?";
            $i++;
        }

        return $this->run("UPDATE $table SET $fieldDetails WHERE $whereDetails", $values);

    }

    /**
     * Run sql query
     *
     * @param  string $sql sql query
     * @param  array $args params
     * @return object            returns a PDO object
     */
    private function run($sql, $args = array())
    {
        if (empty($args)) {
            return $this->db->query($sql);
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($args);

        return $stmt;
    }

    /**
     * @param $sql
     * @return false2|\PDOStatement
     */
    public function query($sql)
    {
        return $this->db->query($sql);
    }

    public function getAll($sql, $class_name = 'stdClass')
    {
        $stmt = $this->db->query($sql);
        if (!$stmt) return false;
        return $stmt->fetchAll(\PDO::FETCH_CLASS, $class_name);
    }

    public function get($sql, $class_name = 'stdClass')
    {
        $stmt = $this->db->query($sql);
        if (!$stmt) return false;
        return $stmt->fetchObject($class_name);
    }

    public function quoteIdentifier($identifier)
    {
        return sprintf("%s$identifier%s",$this->quote_identifier_character,$this->quote_identifier_character);

    }

    public function getDriver()
    {
        return $this->db->getAttribute(\PDO::ATTR_DRIVER_NAME);

    }


}