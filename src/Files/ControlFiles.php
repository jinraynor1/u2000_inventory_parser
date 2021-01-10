<?php


namespace App\Files;


use App\DatabaseInterface;
use App\Loaders\LoaderResult;

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

            $result = $this->database->selectOne("ControlFiles",array("xml"),array("xml"=>$file->getBasename() ));

            if(!$result){
                $nonExistingFiles[] = $file;
            }
        }
        return $nonExistingFiles;
    }


    public function insertFile(\SplFileInfo $fileInfo, LoaderResult $loaderResult)
    {
        $this->database->insert('ControlFiles',array(
            'xml'=>$fileInfo->getBasename(),
            'registerDate' => date('Y-m-d H:i:s'),
            'recordsInserted'=> $loaderResult->getSuccess(),
            'recordsFailed'=> $loaderResult->getFailed(),
            'recordsSkipped'=> $loaderResult->getSkipped(),
        ));


    }

    public function insertFileDetail(\SplFileInfo $fileInfo, LoaderResult $loaderResult)
    {
        foreach ($loaderResult->getResultsDetailed() as $result){
            $this->database->insert('ControlFilesDetails',array(
                'xml'=>$fileInfo->getBasename(),
                'table'=>$result->getTableName(),
                'recordsInserted'=> $result->getSuccess(),
                'recordsFailed'=> $result->getFailed(),
                'recordsSkipped'=> $result->getSkipped(),
            ));


        }


    }

}