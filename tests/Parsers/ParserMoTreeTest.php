<?php

namespace Tests;

use App\ParserMoTree;
use PHPUnit\Framework\TestCase;

class ParserMoTreeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \SplFileInfo
     */
    private $xmlFileMoTree;

    private $xmlFileDataPacketTable;

    /**
     * @var \App\Parsers\ParserMoTree
     */
    private $parser;
    public function setUp()
    {
        $this->xmlFileMoTree = new \SplFileInfo(APP_PATH .'/samples/mo_tree.xml');
        $this->parser = $this->getMockBuilder('\\App\\Parsers\\ParserMoTree')
            ->setConstructorArgs(array($this->xmlFileMoTree))
            ->setMethods(null)
            ->getMock();


    }

    public function testParse()
    {
        foreach ($this->parser as $item){
            $this->assertInstanceOf('\\App\\Mappers\\MappingTableInterface',$item);
        }
    }

    public function testSuccessfullyIdentifyXML()
    {
        $this->assertTrue($this->parser->detect());
    }

    public function testFailsIdentifyWrongXML()
    {
        $xmlFileDataPacketTable = new \SplFileInfo(APP_PATH .'/samples/datapacket.xml');

        $wrong_parser = $this->getMockBuilder('\\App\\Parsers\\ParserMoTree')
            ->setConstructorArgs(array($xmlFileDataPacketTable))
            ->setMethods(null)
            ->getMock();
        $this->assertFalse($wrong_parser->detect());
    }
}
