<?php


namespace App\Mappers;


class MappingTable implements  MappingTableInterface
{
    private $tableName;

    /**
     * @var MappingColumn[]
     */
    private $columns = array();

    /**
     * @var MappingColumn[]
     */
    private $indexes = array();

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

    public function appendIndex(MappingColumnInterface $mappingColumn)
    {
        $this->indexes[$mappingColumn->getName()] = $mappingColumn;
    }

    public function getColumns()
    {
        return $this->columns;
    }

    public function getIndexes()
    {
        return $this->indexes;
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

    public function columnExist(MappingColumnInterface $mappingColumn)
    {
        return isset($this->columns[$mappingColumn->getName()] );

    }


}
