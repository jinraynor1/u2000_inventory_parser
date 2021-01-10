<?php


namespace App\Loaders;


use App\Mappers\MappingColumnInterface;
use App\Mappers\MappingIndexInterface;
use App\Mappers\MappingTableInterface;

interface SchemaLoaderInterface
{
    public function createTable(MappingTableInterface $table);

    public function createIndex(MappingTableInterface $table, MappingIndexInterface $index);

    public function createColumn(MappingTableInterface $mappingTable, MappingColumnInterface $mappingColumn);

    public function tableExists(MappingTableInterface $mappingTable);

    public function columnExists(MappingTableInterface $mappingTable, MappingColumnInterface $mappingColumn);

    public function indexExists(MappingTableInterface $mappingTable, MappingIndexInterface $mappingIndex);

    /**
     * @return MappingTableInterface
     */
    public function getSchemaTables();

    public function getColumns(MappingTableInterface $mappingTable);


}