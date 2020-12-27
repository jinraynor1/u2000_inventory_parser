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

abstract class AbstractSchemaLoader
{

    /**
     * @var DatabaseInterface
     */
    protected $database;

    public function __construct(DatabaseInterface $database)
    {

        $this->database = $database;
    }

    abstract function getDataTypeDatetime();

    abstract function getDataTypeVarChar();

    abstract function getDataTypeText();

    public function createTable(MappingTableInterface $table)
    {
        $sql = "CREATE TABLE " . $this->database->quoteIdentifier($table->getName()) . " (";
        $i = 0;

        foreach ($table->getColumns() as $column) {
            if ($column->isDatetime())
                $datatype = $this->getDataTypeDatetime();
            elseif ($column->getColumnLength())
                $datatype = $this->getDataTypeVarChar() . "({$column->getColumnLength()})";
            else
                $datatype = $this->getDataTypeText();

            if ($i) $sql .= ",\n";

            $sql .= $this->database->quoteIdentifier($column->getName()) . " " . $datatype;

            $i++;

        }

        $sql .= ")";


        $this->database->query($sql);
    }

    public function createIndex(MappingTableInterface $table, MappingIndexInterface $index)
    {
        $columns = $index->getColumns();
        if(empty($columns)) return;

        $i=0;
        $field_string = "";
        foreach ($columns as $column){
            if($i) $field_string.=", ";
            $field_string .= $this->database->quoteIdentifier($column->getName());
            $i++;
        }
        $index_name = $this->database->quoteIdentifier($index->getName()."_".$table->getName());
        $table_name = $this->database->quoteIdentifier($table->getName());
        $sql="CREATE INDEX $index_name ON  $table_name ($field_string)";
        $this->database->query($sql);

    }




    public function createColumn(MappingTableInterface $mappingTable, MappingColumnInterface $mappingColumn)
    {
        $table_name = $this->database->quoteIdentifier($mappingTable->getName());
        $column_name = $this->database->quoteIdentifier($mappingColumn->getName());


        $sql = "ALTER TABLE $table_name ADD COLUMN $column_name ". $this->getDataTypeText();
        $this->database->query($sql);

    }

    public function columnExists(MappingTableInterface $mappingTable, MappingColumnInterface $mappingColumn)
    {
        $columns = $this->getColumns($mappingTable);

        foreach ($columns as $column){
            if($column->COLUMN_NAME == $mappingColumn->getName()){
                return true;
            }
        }

        return false;
    }

    public function indexExists(MappingTableInterface $mappingTable, MappingIndexInterface $mappingIndex)
    {
        $indexes = $this->getIndexes($mappingTable);

        foreach ($indexes as $index){
            if($index->INDEX_NAME == $mappingIndex->getName()){
                return true;
            }
        }

        return false;
    }




    abstract function getTables();
    abstract function getColumns(MappingTableInterface $mappingTable);
    abstract function getIndexes(MappingTableInterface $mappingTable);
    abstract function getColumnIndexes(MappingTableInterface $mappingTable, MappingIndexInterface $mappingIndex);


    public function getSchemaTables()
    {
        $tables = $this->getTables();

        $map = new MappingTableCollection();
        if (!$tables) return $map;


        foreach ($tables as $table) {

            $map[$table->TABLE_NAME] = new MappingTable($table->TABLE_NAME);

            $columns = $this->getColumns($map[$table->TABLE_NAME]);
            if (!$columns) continue;


            foreach ($columns as $column) {
                $map[$table->TABLE_NAME]->appendColumn(new MappingColumn($column->COLUMN_NAME));
            }

            $indexes = $this->getIndexes($map[$table->TABLE_NAME]);


            if($indexes)
                foreach ($indexes as $index) {
                    $index = new MappingIndex($index->INDEX_NAME);
                    $columns = $this->getColumnIndexes($map[$table->TABLE_NAME], $index);

                    if ($columns)
                        foreach ($columns as $column)
                            $index->appendColumn(new MappingColumn($column));

                    $map[$table->TABLE_NAME]->appendIndex($index);

                    $result[] = $index;

                }

        }

        return $map;
    }

}