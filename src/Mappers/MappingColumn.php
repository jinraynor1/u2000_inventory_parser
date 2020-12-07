<?php


namespace App\Mappers;


class MappingColumn implements  MappingColumnInterface
{
    private $columnName;
    private $columnValue;

    public function __construct($columnName)
    {
        $this->columnName = $columnName;
    }

    public function getName()
    {
        return $this->columnName;
    }

    public function getValue()
    {
        return $this->columnValue;
    }

    public function setValue($value)
    {
        $this->columnValue = $value;
    }


}
