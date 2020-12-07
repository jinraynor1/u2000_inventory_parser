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
        $this->xmlFileMoTree = new \SplFileInfo(APP_PATH .'/samples/mo_tree.xml');
        $this->xmlFileDataPacketTable = new \SplFileInfo(APP_PATH .'/samples/datapacket.xml');
        $this->parser = $this->getMockBuilder('\\App\\Parsers\\ParserDataPacketTable')
        ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();


    }

    public function testSuccessfullyIdentifyXML()
    {
        $this->assertTrue($this->parser->detect($this->xmlFileDataPacketTable));
    }

    public function testFailsIdentifyWrongXML()
    {
        $this->assertFalse($this->parser->detect($this->xmlFileMoTree));
    }
}
