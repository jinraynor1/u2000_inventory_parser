<?php


namespace App\Mappers;


class MappingTable implements  MappingTableInterface
{
    private $tableName;
    /**
     * @var MappingColumn[]
     */
    private $columns = array();

    public function __construct($tableName)
    {
        $this->tableName = $tableName;
    }

    public function getName()
    {
        return $this->tableName;
    }

    public function appendColumn(MappingColumnInterface $mappingColumn)
    {
        $this->columns[$mappingColumn->getName()] = $mappingColumn;
    }

    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * @return array
     */
    public function getColumnsAsKeyValue()
    {
        $map = array();
        foreach ($this->columns as $column){
            $map[$column->getName()] = $column->getValue();
        }
        return $map;
    }


}
