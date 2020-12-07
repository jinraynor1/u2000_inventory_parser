<?php

namespace Test;

use App\Mappers\MappingColumn;
use App\Mappers\MappingTable;
use App\Mappers\MappingTableCollection;
use App\Mappers\MappingTableCollectionInterface;

class FilterExistingSchemaTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MappingTableCollection
     */
    private $mappingCollection;

    public function setUp()
    {

    }
    public function testFilterExistingTable()
    {
        $table1 = new MappingTable("table_1");
        $table2 = new MappingTable("table_2");

        $mappingCollection= new MappingTableCollection(array($table1 ));
        $this->assertTrue($mappingCollection->mappingTableExists($table1));
        $this->assertFalse($mappingCollection->mappingTableExists($table2));

    }

    public function testFilterExistingColumn()
    {
        $table1 = new MappingTable("table_1");
        $table2 = new MappingTable("table_2");
        $column1 = new MappingColumn("column_1");
        $column2 = new MappingColumn("column_2");

        $table1->appendColumn($column1);
        $table1->appendColumn($column2);

        $table2->appendColumn($column1);


        $mappingCollection= new MappingTableCollection(array($table1, $table2));

        $this->assertTrue($mappingCollection->mappingColumnsExists($table1,$column1));

        $this->assertFalse($mappingCollection->mappingColumnsExists($table2,$column2));


    }
}