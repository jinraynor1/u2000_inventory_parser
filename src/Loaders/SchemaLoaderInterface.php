<?php


namespace App\Loaders;


use App\Mappers\MappingColumnInterface;
use App\Mappers\MappingTableInterface;

interface SchemaLoaderInterface
{
    public function createTable(MappingTableInterface $table);

    public function createColumn(MappingTableInterface $mappingTable, MappingColumnInterface $mappingColumn);

    public function tableExists(MappingTableInterface $mappingTable);

    public function columnExists(MappingTableInterface $mappingTable, MappingColumnInterface $mappingColumn);

    /**
     * @return MappingTableInterface
     */
    public function getSchemaTables();
}