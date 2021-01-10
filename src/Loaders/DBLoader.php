<?php


namespace App\Loaders;


use App\DatabaseInterface;
use App\Mappers\MappingTable;
use App\Mappers\MappingTableCollection;
use App\Mappers\MappingTableCollectionInterface;
use App\Mappers\MappingTableInterface;
use App\Parsers\InventoryParserInterface;
use Cassandra\Map;

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

    /**
     * @var MappingTableCollection
     */
    private $nonExistingTables;

    public function __construct(DatabaseInterface  $database, MappingTableCollectionInterface  $mappingTableCollection)
    {
        $this->database = $database;
        $this->mappingTableCollection = $mappingTableCollection;
        $this->nonExistingTables = new MappingTableCollection();
    }

    public function loadParserToDatabase(InventoryParserInterface $parser)
    {
        $loaderResult = new LoaderResult();
        foreach ($parser as $table){
            $table =  $this->getExistingElements($table);

            if(!$table) {
                $loaderResult->addSkipeed($table);
                continue;
            }

            try {
                $this->database->insert($table->getName(), $table->getColumnsAsKeyValue());
                $loaderResult->addSuccess($table);
            } catch (\Exception $e) {
                $loaderResult->addFailed($table);
            }

        }
        return $loaderResult;
    }

    /**
     * @param $table
     * @return MappingTable|false
     */
    public function getExistingElements(MappingTableInterface $table)
    {

        if(!$this->mappingTableCollection->mappingTableExists($table)){
            $this->nonExistingTables[$table->getName()] = $table;
            return false;
        }

        $new_table = new MappingTable($table->getName());
        foreach ($table->getColumns() as $column){
            if(!$this->mappingTableCollection->mappingColumnsExists($table, $column)){

                if(!isset($this->nonExistingColumns[$table->getName()])){
                    $this->nonExistingTables[$table->getName()] =  new MappingTable($table->getName());
                }

                $this->nonExistingTables[$table->getName()]->appendColumn($column);

                continue;
            }

            $new_table->appendColumn($column);

        }
        if(empty($new_table->getColumns())) return false;

        return $new_table;
    }

    public function getNonExistingTables()
    {
        return $this->nonExistingTables;
    }
}