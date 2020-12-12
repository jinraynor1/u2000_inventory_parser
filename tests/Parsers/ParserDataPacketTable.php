<?php

namespace Tests;

use App\ParserMoTree;
use PHPUnit\Framework\TestCase;

class ParserDataPacketTable extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \SplFileInfo
     */
    private $xmlFileMoTree;

    private $xmlFileDataPacketTable;

    /**
     * @var \App\Parsers\ParserDataPacketTable
     */
    private $parser;
    public function setUp()
    {
        $this->xmlFileDataPacketTable = new \SplFileInfo(APP_PATH .'/samples/datapacket.xml');
        $this->parser = $this->getMockBuilder('\\App\\Parsers\\ParserDataPacketTable')
            ->setConstructorArgs(array($this->xmlFileDataPacketTable))
            ->setMethods(null)
            ->getMock();


    }
    public function testParse()
    {
        foreach ($this->parser as $item){
            var_dump($item);
            $this->assertInstanceOf('\\App\\Mappers\\MappingTableInterface',$item);
        }
    }
    public function testSuccessfullyIdentifyXML()
    {
        $this->assertTrue($this->parser->detect());
    }

    public function testFailsIdentifyWrongXML()
    {
        $xmlFileMoTree = new \SplFileInfo(APP_PATH .'/samples/mo_tree.xml');

        $wrong_parser = $this->getMockBuilder('\\App\\Parsers\\ParserDataPacketTable')
            ->setConstructorArgs(array($xmlFileMoTree))
            ->setMethods(null)
            ->getMock();
        $this->assertFalse($wrong_parser->detect());

    }
}
