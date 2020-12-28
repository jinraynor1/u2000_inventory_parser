<?php


namespace App\Files;


use App\DatabaseInterface;

class ControlFiles
{
    /**
     * @var DatabaseInterface
     */
    private $database;

    public function __construct(DatabaseInterface $database)
    {

        $this->database = $database;
    }

    /**
     * @param \SplFileInfo[] $fileList
     * @return array
     */
    public function filterFiles(array $fileList)
    {
        $nonExistingFiles = [];

        foreach ($fileList as $file){

            $result = $this->database->selectOne("control_files",array("xmlFile"),array("xmlFile"=>$file->getBasename() ));

            if(!$result){
                $nonExistingFiles[] = $file;
            }
        }
        return $nonExistingFiles;
    }

    public function insertFiles(array $fileList)
    {
        foreach ($fileList as $file){
           $this->insertSingleFile($file);
        }

    }

    public function insertSingleFile(\SplFileInfo $fileInfo)
    {
        $this->database->insert('control_files',array(
            'xmlFile'=>$fileInfo->getBasename(),
            'registerDate' => date('Y-m-d H:i:s')
        ));
    }

}