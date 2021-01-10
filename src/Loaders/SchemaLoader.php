<?php


namespace App\Loaders;


use App\Loaders\SchemaLoaderInterface;
use App\DatabaseInterface;
use App\Mappers\MappingColumnInterface;
use App\Mappers\MappingTableCollection;
use App\Mappers\MappingTableCollectionInterface;
use App\Mappers\MappingTableInterface;
use Cassandra\Map;

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

    public function __construct(SchemaLoaderInterface $schemaLoader)
    {
        $this->schemaLoader = $schemaLoader;
        $this->mappingTableCollection = new MappingTableCollection();
    }


    public function loadTableSchemaCollection(MappingTableCollectionInterface $mappingTableCollection)
    {
        foreach ($mappingTableCollection as $table) {
            $this->loadTableSchema($table);
        }
    }

    public function loadTableSchema(MappingTableInterface $table)
    {
        $in_cache = $this->mappingTableCollection->mappingTableExists($table);

        if (!$in_cache) {

            if (!$this->schemaLoader->tableExists($table)) {


                $this->schemaLoader->createTable($table);

                if (!empty($table->getIndexes()))
                    foreach ($table->getIndexes() as $index)
                        $this->schemaLoader->createIndex($table, $index);


            } else {

                // echo "bloque 1  verificando que columnas existan\n";
                $listDbColumns = $this->schemaLoader->getColumns($table);

                foreach ($table->getColumns() as $column) {

                    $columnExist = false;
                    foreach ($listDbColumns as $db_column) {
                        if ($db_column->COLUMN_NAME == $column->getName()) {
                            $columnExist = true;
                            break;
                        }
                    }
                    if (!$columnExist && !$this->schemaLoader->columnExists($table, $column)) {
                        //   echo "bloque 1 agregando  columnas  {$column->getName()} existe \n";

                        $this->schemaLoader->createColumn($table, $column);
                    }
                    // echo "bloque 1 columnas  {$column->getName()}  \n";
                }


            }

            $this->mappingTableCollection[$table->getName()] = $table;


        } else {
            $table_cache = $this->mappingTableCollection[$table->getName()];

            if ($table_cache) {

               // echo "bloque 2  verificando que columnas existan\n";
                foreach ($table->getColumns() as $column) {
                    //echo "bloque 2  column {$column->getName()} \n";
                    if (!$this->mappingTableCollection->mappingColumnsExists($table_cache, $column)) {


                        if (!$this->schemaLoader->columnExists($table, $column)) {
                 //           echo "bloque 2 agregando  columnas  {$column->getName()} existe \n";

                            $this->schemaLoader->createColumn($table, $column);
                        }

                        $table_cache->appendColumn($column);
                    }


                }
            }

        }

    }


}