<?php


namespace App\Loaders;


use App\DatabaseInterface;
use App\Mappers\MappingColumn;
use App\Mappers\MappingColumnInterface;
use App\Mappers\MappingTable;
use App\Mappers\MappingTableCollection;
use App\Mappers\MappingTableCollectionInterface;
use App\Mappers\MappingTableInterface;

class OracleSchemaLoader implements SchemaLoaderInterface
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
        $sql = "CREATE TABLE \"{$table->getName()}\" (";
        $i=0;
        foreach ($table->getColumns() as $column) {
            if($column->isDatetime())
                $datatype = "DATE";
            else
                $datatype = "varchar2 (4000)";

            if($i) $sql.=",\n";
            $sql .= "\"{$column->getName()}\" $datatype ";
            $i++;
        }

        $sql.= ")";


        $this->database->query($sql);
    }

    public function createColumn(MappingTableInterface $mappingTable, MappingColumnInterface $mappingColumn)
    {
        $sql = "ALTER TABLE \"{$mappingTable->getName()}\" ADD COLUMN \"{$mappingColumn->getName()}\" varchar2(4000)";
        $this->database->query($sql);

    }

    public function tableExists(MappingTableInterface $mappingTable)
    {
        $sql = "   select count(*) \"exists\" FROM user_tables WHERE table_name = '{$mappingTable->getName()}'";
        $result = $this->database->get($sql);


        if (!$result) return false;

        return $result->exists > 0;

    }

    public function columnExists(MappingTableInterface $mappingTable, MappingColumnInterface $mappingColumn)
    {
        $sql="SELECT COUNT(*) \"exists\" FROM user_tab_cols WHERE table_name= '{$mappingTable->getName()}'
    AND  column_name='{$mappingColumn->getName()}'";

        $result = $this->database->get($sql);

        if (!$result) return false;

        return $result->exists > 0;
    }

    /**
     * @return MappingTableCollectionInterface
     */
    public function getSchemaTables()
    {
        $sql = "SELECT table_name FROM user_tables ";

        $tables = $this->database->getAll($sql);
        $map  = new MappingTableCollection();
        if(!$tables) return $map;


        foreach ($tables as $table){

            $sql = "SELECT column_name  FROM user_tab_cols WHERE table_name= '{$table->TABLE_NAME}' ";
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