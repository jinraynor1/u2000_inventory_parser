<?php


namespace App\Loaders;


use App\DatabaseInterface;
use App\Mappers\MappingTable;
use App\Mappers\MappingTableCollectionInterface;
use App\Parsers\InventoryParserInterface;

class DBLoader
{
    /**
     * @var MappingTableCollectionInterface
     */
    private $mappingTableCollection;
    /**
     * @var DatabaseInterface
     */
    private $database;

    public function __construct(DatabaseInterface  $database, MappingTableCollectionInterface  $mappingTableCollection)
    {
        $this->database = $database;
        $this->mappingTableCollection = $mappingTableCollection;
    }

    public function loadParserToDatabase(InventoryParserInterface $parser)
    {
        foreach ($parser as $table){

            $table =  $this->getExistingElements($table);
            if(!$table) continue;

            $this->database->insert($table->getName(),$table->getColumnsAsKeyValue());

        }
    }

    /**
     * @param $table
     * @return MappingTable|false
     */
    public function getExistingElements($table)
    {
        $table = new MappingTable();

        if(!$this->mappingTableCollection->mappingTableExists($table)) return false;

        foreach ($table->getColumns() as $column){
            if($this->mappingTableCollection->mappingColumnsExists($table, $column)){
                continue;
            }

            $table->appendColumn($column);

        }
        if(empty($table->getColumns())) return false;

        return $table;
    }
}