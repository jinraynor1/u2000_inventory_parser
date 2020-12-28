<?php


namespace Files;


use App\DatabaseInterface;
use App\Files\ControlFiles;

class ControlFilesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ControlFiles
     */
    private $controlFiles;

    /**
     * @var DatabaseInterface
     */
    private $database;

    public function setup()
    {
        global $container;
        $this->database  = $container->get('database.phpunit.oracle');

        $this->controlFiles = new ControlFiles($this->database);
        $this->database->delete("control_files");
    }

    public function tearDown()
    {
        $this->database->delete("control_files");
    }

    public function testInsertFiles()
    {
        $fileList = [
            new \SplFileInfo("file1.xml"),
            new \SplFileInfo("file2.xml"),
            new \SplFileInfo("file3.xml"),
        ];

        $this->controlFiles->insertFiles($fileList);

        $result = $this->database->selectAll("control_files",array("xmlFile"));
        $this->assertCount(3 , $result);

        array_push($fileList,
                new \SplFileInfo("file4.xml")
        );

        $filteredFileList = $this->controlFiles->filterFiles($fileList);

        $this->assertCount(1, $filteredFileList);

        $this->assertEquals("file4.xml",$filteredFileList[0]->getBasename());
        

    }


    public function testThrowExceptionForDuplicateFile()
    {
        $fileList = [
            new \SplFileInfo("file1.xml"),
        ];

        $this->controlFiles->insertFiles($fileList);

        $this->setExpectedException(\PDOException::class);
        $this->controlFiles->insertFiles($fileList);

    }
}