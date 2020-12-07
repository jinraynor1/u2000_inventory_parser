<?php


namespace App\Mappers;
use ArrayIterator;

interface MappingTableCollectionInterface extends \IteratorAggregate,\ArrayAccess
{


    /**
     * @return MappingTableInterface[]|ArrayIterator
     */
    public function getIterator();

    public function mappingTableExists(MappingTableInterface $mappingTable);

    public function mappingColumnsExists(MappingTableInterface $mappingTable, MappingColumnInterface $mappingColumn);

}