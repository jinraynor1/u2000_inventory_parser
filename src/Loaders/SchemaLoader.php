<?php


namespace App\Loaders;


use App\Loaders\SchemaLoaderInterface;
use App\DatabaseInterface;
use App\Mappers\MappingColumnInterface;
use App\Mappers\MappingTableCollectionInterface;
use App\Mappers\MappingTableInterface;

class SchemaLoader
{

    /**
     * @var MappingTableCollectionInterface
     */
    private $mappingTableCollection;
    /**
     * @var SchemaLoaderInterface
     */
    private $schemaLoader;

    public function __construct(MappingTableCollectionInterface $mappingTableCollection,
    SchemaLoaderInterface $schemaLoader)
    {

        $this->mappingTableCollection = $mappingTableCollection;

        $this->schemaLoader = $schemaLoader;
    }



    public function load()
    {
        foreach ($this->mappingTableCollection as $table) {

            if (!$this->schemaLoader->tableExists($table)) {
                $this->schemaLoader->createTable($table);
            } else {
                $this->alterTable($table);
            }


        }
    }

    public function alterTable(MappingTableInterface $table)
    {
        foreach ($table->getColumns() as $column) {
            if (!$this->schemaLoader->columnExists($table, $column)) {
                $this->schemaLoader->createColumn($table, $column);
            }
        }
    }


}