<?php


namespace Loaders;


use App\Loaders\SchemaDetector;
use App\Mappers\MappingColumn;
use App\Mappers\MappingTable;

class SchemaDetectorTest extends \PHPUnit_Framework_TestCase
{
    public function testDetect()
    {
        $fileList = array(
            new \SplFileInfo(APP_PATH .'/samples/mo_tree.xml'),
            new \SplFileInfo(APP_PATH .'/samples/datapacket.xml'),



        );


        $schemaDetector = new SchemaDetector($fileList);
        $mappingTableCollection = $schemaDetector->detect();




        $this->assertTrue($mappingTableCollection->mappingColumnsExists(new MappingTable("mo_tree"),
        new MappingColumn("fdn")));



        $this->assertTrue($mappingTableCollection->mappingColumnsExists(new MappingTable("Cabinet"),
            new MappingColumn("RackType")));


        $this->assertTrue($mappingTableCollection->mappingColumnsExists(new MappingTable("Subrack"),
            new MappingColumn("SubrackNo")));

        $this->assertTrue($mappingTableCollection->mappingColumnsExists(new MappingTable("Slot"),
            new MappingColumn("SlotNo")));

}
}