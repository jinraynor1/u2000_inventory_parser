<?php


namespace Files;


use App\DatabaseInterface;
use App\Files\ControlFiles;
use App\Loaders\LoaderResult;

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
        $this->database->delete("ControlFiles");
    }

    public function tearDown()
    {
        $this->database->delete("ControlFiles");
    }

    public function testInsertFiles()
    {
        $loaderResult = $this->getMock(LoaderResult::class);

        $this->controlFiles->insertFile(new \SplFileInfo("file1.xml") ,$loaderResult);
        $this->controlFiles->insertFile(new \SplFileInfo("file2.xml") ,$loaderResult);
        $this->controlFiles->insertFile(new \SplFileInfo("file3.xml") ,$loaderResult);

        $result = $this->database->selectAll("ControlFiles",array("xml"));
        $this->assertCount(3 , $result);


        $fileList = [
            new \SplFileInfo("file1.xml"),
            new \SplFileInfo("file2.xml"),
            new \SplFileInfo("file3.xml"),
            new \SplFileInfo("file4.xml")
        ];

        $filteredFileList = $this->controlFiles->filterFiles($fileList);

        $this->assertCount(1, $filteredFileList);

        $this->assertEquals("file4.xml",$filteredFileList[0]->getBasename());
        

    }


    public function testThrowExceptionForDuplicateFile()
    {

        $loaderResult = $this->getMock(LoaderResult::class);

        $this->controlFiles->insertFile(  new \SplFileInfo("file1.xml"),$loaderResult);

        $this->setExpectedException(\PDOException::class);
        $this->controlFiles->insertFile(  new \SplFileInfo("file1.xml"),$loaderResult);

    }
}