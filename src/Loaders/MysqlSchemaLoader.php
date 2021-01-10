<?php


namespace App\Loaders;


use App\DatabaseInterface;
use App\Mappers\MappingColumn;
use App\Mappers\MappingColumnInterface;
use App\Mappers\MappingIndex;
use App\Mappers\MappingIndexInterface;
use App\Mappers\MappingTable;
use App\Mappers\MappingTableCollection;
use App\Mappers\MappingTableInterface;

class MysqlSchemaLoader extends AbstractSchemaLoader implements SchemaLoaderInterface
{
    function getDataTypeDatetime()
    {
        return "Datetime";
    }

    function getDataTypeVarChar()
    {
        return "varchar";
    }

    function getDataTypeText()
    {
        return "text";
    }


    public function tableExists(MappingTableInterface $mappingTable)
    {
        $result = $this->database->get("SELECT COUNT(*) AS `exists` FROM information_schema.tables 
            WHERE table_schema = DATABASE()
            AND table_name = '{$mappingTable->getName()}'");


        if (!$result) return false;

        return $result->exists > 0;

    }

    public function columnExists(MappingTableInterface $mappingTable, MappingColumnInterface  $mappingColumn)
    {
        $result = $this->database->get("SELECT COUNT(*) AS `exists` FROM information_schema.columns 
            WHERE table_schema = DATABASE()
            AND table_name = '{$mappingTable->getName()}' AND column_name= '{$mappingColumn->getName()}'  ");


        if (!$result) return false;

        return $result->exists > 0;

    }




    public function getTables(){
        $sql = "SELECT TABLE_NAME FROM information_schema.tables where table_schema = database() ";

        return $this->database->getAll($sql);
    }



    public function getColumns(MappingTableInterface $mappingTable)
    {
        $sql = "SELECT COLUMN_NAME  FROM information_schema.columns
                    WHERE table_schema = database() AND table_name= '{$mappingTable->getName()}' ";

        return $this->database->getAll($sql);
    }

    public function getIndexes(MappingTableInterface $mappingTable)
    {

        $sql = "SELECT DISTINCT INDEX_NAME  FROM INFORMATION_SCHEMA.STATISTICS
WHERE TABLE_SCHEMA = DATABASE()
            AND table_name = '{$mappingTable->getName()}'";

        return $this->database->getAll($sql);

    }

    public function getColumnIndexes(MappingTableInterface $mappingTable, MappingIndexInterface $mappingIndex)
    {

        $sql = "SELECT DISTINCT COLUMN_NAME  FROM INFORMATION_SCHEMA.STATISTICS
WHERE TABLE_SCHEMA = DATABASE()
            AND table_name = '{$mappingTable->getName()}' AND INDEX_NAME= '{$mappingIndex->getName()}'";

        return $this->database->getAll($sql);
    }


}