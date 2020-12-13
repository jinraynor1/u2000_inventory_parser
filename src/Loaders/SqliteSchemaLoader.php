<?php


namespace App\Loaders;


use App\DatabaseInterface;
use App\Mappers\MappingColumn;
use App\Mappers\MappingColumnInterface;
use App\Mappers\MappingTable;
use App\Mappers\MappingTableCollection;
use App\Mappers\MappingTableCollectionInterface;
use App\Mappers\MappingTableInterface;

class SqliteSchemaLoader implements SchemaLoaderInterface
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
            if($i) $sql.=",\n";
            $sql .= "`{$column->getName()}` text ";
            $i++;
        }

        $sql.= ")";


        $this->database->query($sql);
    }

    public function createColumn(MappingTableInterface $mappingTable, MappingColumnInterface $mappingColumn)
    {
        $sql = "ALTER TABLE `{$mappingTable->getName()}` ADD COLUMN `{$mappingColumn->getName()}` text";
        $this->database->query($sql);

    }

    public function tableExists(MappingTableInterface $mappingTable)
    {
        $sql = "SELECT COUNT(*) AS `exists`FROM sqlite_master WHERE type='table' AND tbl_name='{$mappingTable->getName()}'";
        $result = $this->database->get($sql);


        if (!$result) return false;

        return $result->exists > 0;

    }

    public function columnExists(MappingTableInterface $mappingTable, MappingColumnInterface $mappingColumn)
    {
        $sql="SELECT COUNT(*) AS `exists` FROM pragma_table_info('{$mappingTable->getName()}') WHERE name='{$mappingColumn->getName()}'";

        $result = $this->database->get($sql);


        if (!$result) return false;

        return $result->exists > 0;
    }

    /**
     * @return MappingTableCollectionInterface
     */
    public function getSchemaTables()
    {
        $sql = "SELECT tbl_name FROM sqlite_master WHERE type='table'";

        $tables = $this->database->getAll($sql);
        $map  = new MappingTableCollection();
        if(!$tables) return $map;


        foreach ($tables as $table){

            $sql = "SELECT name  FROM pragma_table_info('{$table->tbl_name}') ";
            $columns  = $this->database->getAll($sql);

            if(!$columns) continue;

            $map[$table->tbl_name] = new MappingTable($table->tbl_name);

            foreach ($columns as $column){
                $map[$table->tbl_name]->appendColumn(new MappingColumn($column->name));
            }

        }

        return $map;
    }
}