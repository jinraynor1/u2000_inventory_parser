<?php


namespace App\Loaders;


use App\DatabaseInterface;
use App\Mappers\MappingColumn;
use App\Mappers\MappingColumnInterface;
use App\Mappers\MappingIndexInterface;
use App\Mappers\MappingTable;
use App\Mappers\MappingTableCollection;
use App\Mappers\MappingTableCollectionInterface;
use App\Mappers\MappingTableInterface;

class SqliteSchemaLoader extends AbstractSchemaLoader implements SchemaLoaderInterface
{
    function getDataTypeDatetime()
    {
        return "text";
    }

    function getDataTypeVarChar()
    {
        return "text";
    }

    function getDataTypeText()
    {
        return "text";
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
        $sql = "SELECT COUNT(*) AS `exists` FROM pragma_table_info('{$mappingTable->getName()}') WHERE name='{$mappingColumn->getName()}'";
        $result = $this->database->get($sql);

        if (!$result) return false;
        return $result->exists > 0;
    }


    function getTables()
    {
        $sql = "SELECT tbl_name TABLE_NAME FROM sqlite_master WHERE type='table'";
        return $this->database->getAll($sql);
    }

    function getColumns(MappingTableInterface $mappingTable)
    {
        $sql = "PRAGMA Table_Info('{$mappingTable->getName()}') ";
        $result = $this->database->getAll($sql);
        return $this->parseNameResult($result,"COLUMN_NAME");

    }

    function getIndexes(MappingTableInterface $mappingTable)
    {
        $sql = "PRAGMA index_list('{$mappingTable->getName()}') ";
        $result =  $this->database->getAll($sql);
        return $this->parseNameResult($result,"INDEX_NAME");


    }

    function getColumnIndexes(MappingTableInterface $mappingTable, MappingIndexInterface $mappingIndex)
    {
        $sql = "PRAGMA index_xinfo('{$mappingIndex->getName()}') ";
        $result =  $this->database->getAll($sql);
        return $this->parseNameResult($result,"COLUMN_NAME");
    }

    private function parseNameResult($result,$field_name)
    {
        $list = [];
        if($result){
            foreach ($result as $row){
                if($row->name){
                    $list[] = (object)array($field_name => $row->name);
                }
            }
        }
        return $list;
    }

}