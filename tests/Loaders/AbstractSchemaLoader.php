<?php


namespace Test\Loaders;

use App\DatabaseInterface;
use App\Loaders\FactorySchemaLoader;
use App\Loaders\SchemaDetector;
use App\Loaders\SchemaLoaderInterface;
use App\Mappers\MappingColumn;
use App\Mappers\MappingTable;
use App\Mappers\MappingTableCollectionInterface;
use App\Parsers\FactoryParser;

abstract class AbstractSchemaLoader extends \PHPUnit_Framework_TestCase
{

    /**
     * @var DatabaseInterface
     */
    private $database;

    /**
     * @var SchemaLoaderInterface
     */
    private $specificSchemaLoader;

    /**
     * @var MappingTableCollectionInterface
     */
    private $fileMappingTableCollection;

    /**
     * @var array|\SplFileInfo[]
     */
    private $fileList = array();

    /**
     * @throws \Exception
     */
    public function setUp()
    {


        $this->database = $this->getDatabase();

        $factorySchemaLoader = new FactorySchemaLoader($this->database);

        $this->specificSchemaLoader = $factorySchemaLoader->getSpecificSchemaLoader();

        $this->fileList = array(
            new \SplFileInfo(APP_PATH . '/samples/mo_tree.xml'),
            new \SplFileInfo(APP_PATH . '/samples/datapacket.xml'),
            new \SplFileInfo(APP_PATH . '/samples/datapacket_2.xml')

        );

        $this->dropTables();
        $this->prepareTables();

    }

    /**
     * @return DatabaseInterface
     */
    abstract function getDatabase();

    private function dropTables()
    {
        $mappingTableCollection = $this->specificSchemaLoader->getSchemaTables();

        foreach ($mappingTableCollection as $table){
            $table_name_quoted = $this->database->quoteIdentifier($table->getName());
            $this->database->query("DROP TABLE $table_name_quoted");
        }
    }

    private function prepareTables()
    {

        $fileList = array(
            new \SplFileInfo(APP_PATH . '/samples/mo_tree.xml'),
            new \SplFileInfo(APP_PATH . '/samples/datapacket.xml'),
            new \SplFileInfo(APP_PATH . '/samples/datapacket_2.xml')

        );


        $schemaDetector = new SchemaDetector($fileList);
        $this->fileMappingTableCollection = $schemaDetector->detect();


        $schemaLoader = new \App\Loaders\SchemaLoader($this->fileMappingTableCollection, $this->specificSchemaLoader);
        $schemaLoader->load();
    }

    public function testSchemaLoader()
    {


        $databaseMappingTableCollection = $this->specificSchemaLoader->getSchemaTables();


        $this->assertNotEmpty($databaseMappingTableCollection->getIterator());
        $this->assertFalse($databaseMappingTableCollection->mappingTableExists(new MappingTable("dummy_table")));

        $this->assertFalse($databaseMappingTableCollection->mappingTableExists(
            new MappingTable("other_dummy_table"),
            new MappingColumn("dummy_column")
        ));


        foreach ($this->fileMappingTableCollection as $table) {
            $this->assertTrue($databaseMappingTableCollection->mappingTableExists($table));

            foreach ($table->getColumns() as $column) {
                $this->assertTrue($databaseMappingTableCollection->mappingColumnsExists($table, $column));
            }


        }
    }


    public function testDataLoader()
    {
        $databaseMappingTableCollection = $this->specificSchemaLoader->getSchemaTables();
        $loader = new \App\Loaders\DBLoader($this->database, $databaseMappingTableCollection);
        foreach($this->fileList as $fileInfo){
            $parser =  FactoryParser::getParserForFile($fileInfo);
            $loader->loadParserToDatabase($parser);

        }

        $table_name_mo_tree = $this->database->quoteIdentifier("mo_tree");
        $res = $this->database->get("SELECT COUNT(*) MO_TREE_ROWS FROM $table_name_mo_tree ");
        $this->assertEquals(5, $res->MO_TREE_ROWS);


        $table_name_slot = $this->database->quoteIdentifier("Slot");
        $res = $this->database->get("SELECT COUNT(*) SLOT_ROWS FROM $table_name_slot ");
        $this->assertEquals(30, $res->SLOT_ROWS);

        $table_name_subrack = $this->database->quoteIdentifier("Subrack");
        $res = $this->database->get("SELECT COUNT(*) SUBRACK_ROWS FROM $table_name_subrack ");
        $this->assertEquals(20, $res->SUBRACK_ROWS);





    }

}