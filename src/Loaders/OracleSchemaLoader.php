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

class OracleSchemaLoader extends AbstractSchemaLoader implements SchemaLoaderInterface
{


    function getDataTypeDatetime()
    {
        return "date";
    }

    function getDataTypeVarChar()
    {
        return "varchar";
    }

    function getDataTypeText()
    {
        return "varchar(4000)";
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
        $sql = "SELECT count(*) \"exists\"  FROM user_tab_cols WHERE table_name= '{$mappingTable->getName()}' AND column_name = '{$mappingColumn->getName()}' ";
        $result = $this->database->get($sql);


        if (!$result) return false;

        return $result->exists > 0;
    }



    function getTables()
    {

        $sql = "SELECT TABLE_NAME FROM user_tables ";
        return $this->database->getAll($sql);

    }

    function getColumns(MappingTableInterface $mappingTable)
    {
        $sql = "SELECT COLUMN_NAME  FROM user_tab_cols WHERE table_name= '{$mappingTable->getName()}' ";
        return $this->database->getAll($sql);
    }

    function getIndexes(MappingTableInterface $mappingTable)
    {
        $sql = "SELECT DISTINCT INDEX_NAME FROM USER_IND_COLUMNS WHERE TABLE_NAME = '{$mappingTable->getName()}'";
        return $this->database->getAll($sql);

    }

    function getColumnIndexes(MappingTableInterface $mappingTable, MappingIndexInterface $mappingIndex)
    {
        $sql = "SELECT COLUMN_NAME FROM USER_IND_COLUMNS WHERE TABLE_NAME = '{$mappingTable->getName()}'";
        return $this->database->getAll($sql);
    }


}