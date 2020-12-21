<?php


namespace App\Loaders;


use App\DatabaseInterface;
use App\Mappers\MappingColumnInterface;
use App\Mappers\MappingTableInterface;

class MysqlSchemaLoaderInterface implements SchemaLoaderInterface
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

        foreach ($table->getColumns() as $column) {
            $sql .= "`{$column->getName()}` varchar (45), \n";
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
        // TODO: Implement getSchemaTables() method.
    }
}