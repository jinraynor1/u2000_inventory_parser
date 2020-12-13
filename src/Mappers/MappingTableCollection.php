<?php


namespace App\Mappers;

use ArrayIterator;

/**
 * Class MappingTableCollection
 * @package App\Mappers
 */
class MappingTableCollection implements MappingTableCollectionInterface
{

    private $tables = array();

    /**
     * MappingTableCollection constructor.
     * @param MappingTableInterface[]
     */
    public function __construct(array $tables = array())
    {

        foreach ($tables as $table)
            $this->tables[$table->getName()] = $table;
    }

    /**
     * @return MappingTableInterface[]|ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->tables);
    }


    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->tables);
    }

    /**
     * @param mixed $offset
     * @return MappingTable
     */
    public function offsetGet($offset)
    {
        return $this->tables[$offset];
    }

    /**
     * @param mixed $offset
     * @param mixed MappingTable
     */
    public function offsetSet($offset, $value)
    {
        $this->tables[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->tables[$offset]);
    }

    public function mappingTableExists(MappingTableInterface $mappingTable)
    {
        return isset($this->tables[$mappingTable->getName()]);
    }

    public function mappingColumnsExists(MappingTableInterface $mappingTable, MappingColumnInterface $mappingColumn)
    {
        if(!$this->mappingTableExists($mappingTable)){
            return false;
        }
        $table = $this->tables[$mappingTable->getName()];
        return $table->columnExist($mappingColumn);

        /*
        foreach ($mappingTable->getColumns() as $column){

            if($column->getName() == $mappingColumn->getName()){
                return true;
            }
        }


        return false;
        */
    }
}