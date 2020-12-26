<?php


namespace App\Loaders;


use App\DatabaseInterface;
use App\Mappers\MappingColumn;
use App\Mappers\MappingColumnInterface;
use App\Mappers\MappingTable;
use App\Mappers\MappingTableCollection;
use App\Mappers\MappingTableInterface;

class MysqlSchemaLoader implements SchemaLoaderInterface
{
    /**
     * @var DatabaseInterface
     */
    private $database;

    public function __construct(DatabaseInterface $database )
    {

        $this->database = $database;
    }

    public function createTable(MappingTableInterface $table)
    {
        $sql = "CREATE TABLE `{$table->getName()}` (";
        $i=0;

        foreach ($table->getColumns() as $column) {
            if($column->isDatetime())
                $datatype = "DATETIME";
            else
                $datatype = "TEXT";

            if($i) $sql.=",\n";

            $sql .= "`{$column->getName()}` $datatype ";

            $i++;

        }

        $sql .= ")";


        $this->database->query($sql);
    }

    public function createColumn(MappingTableInterface $mappingTable, MappingColumnInterface $mappingColumn)
    {
        $sql = "ALTER TABLE `{$mappingTable->getName()}` ADD COLUMN `{$mappingColumn->getName()}` varchar (45)";
        $this->database->query($sql);

    }

    public function tableExists(MappingTableInterface $mappingTable)
    {
        $result = $this->database->get("SELECT COUNT(*) AS `exists` FROM information_schema.tables 
            WHERE table_schema = DATABASE()
            AND table_name = '{$mappingTable->getName()}'");


        if (!$result) return false;

        return $result->exists > 0;

    }

    public function columnExists(MappingTableInterface $mappingTable, MappingColumnInterface $mappingColumn)
    {
        $result = $this->database->get("SELECT COUNT(*) AS `exists` FROM information_schema.columns 
            WHERE table_schema = DATABASE()
            AND table_name = '{$mappingTable->getName()}'
            AND column_name = '{$mappingColumn->getName()}'");


        if (!$result) return false;

        return $result->exists > 0;
    }

    public function getSchemaTables()
    {
        $sql = "SELECT TABLE_NAME FROM information_schema.tables where table_schema = database() ";

        $tables = $this->database->getAll($sql);
        $map  = new MappingTableCollection();
        if(!$tables) return $map;


        foreach ($tables as $table){

            $sql = "SELECT COLUMN_NAME  FROM information_schema.columns
                    WHERE table_schema = database() AND table_name= '{$table->TABLE_NAME}' ";
            $columns  = $this->database->getAll($sql);

            if(!$columns) continue;

            $map[$table->TABLE_NAME] = new MappingTable($table->TABLE_NAME);

            foreach ($columns as $column){
                $map[$table->TABLE_NAME]->appendColumn(new MappingColumn($column->COLUMN_NAME));
            }

        }

        return $map;
    }
}