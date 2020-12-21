<?php


namespace App\Mappers;


class MappingColumn implements  MappingColumnInterface
{
    private $columnName;
    private $columnValue;
    private $columnType;

    const COLUMN_TYPE_VARCHAR = 1;
    const COLUMN_TYPE_DATE = 2;

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

    public function setType($type)
    {
        $this->columnType = $type;
    }

    public function getType()
    {
        return $this->columnType;
    }

    public function isDatetime()
    {
        return $this->columnType == self::COLUMN_TYPE_DATE;
    }


}
