<?php


namespace Test\Files;

use App\Files\SearchLocalFiles;
use App\Helpers\FileSystem;

class SearchLocalFilesTest extends \PHPUnit_Framework_TestCase
{

    private $searchFiles;
    private $searchPath;
    public function setup()
    {
        $this->searchFiles = new SearchLocalFiles();
        $temp_dir = sys_get_temp_dir();
        $this->searchPath = $temp_dir . '/u2000_inventario_xml_tmp_files/';
        FileSystem::rrmdir($this->searchPath);
        mkdir($this->searchPath,0777, true);
    }

    public function tearDown()
    {
        FileSystem::rrmdir($this->searchPath);
    }

    public function testFindFiles()
    {


        mkdir($this->searchPath.'/MBTS_1',0777, true);
        mkdir($this->searchPath.'/MBTS_2',0777, true);


        touch($this->searchPath."/MBTS_1/file1.xml",strtotime("2020-11-30 00:00:00"));
        touch($this->searchPath."/MBTS_2/file2.xml",strtotime("2020-11-30 00:00:00"));
        touch($this->searchPath."/MBTS_2/file3.xml",strtotime("2020-12-01 01:30:00"));


        $fromTime= new \DateTime("2020-12-01 01:00:00");
        $endTime=new \DateTime("2020-12-01 02:00:00");

        $file_list = $this->searchFiles->findFiles($this->searchPath, $fromTime, $endTime, "xml");
        $this->assertNotEmpty($file_list);
        $this->assertCount(1,$file_list);
        $file = reset($file_list);
        $this->assertEquals('file3.xml',$file->getBasename());


    }
}