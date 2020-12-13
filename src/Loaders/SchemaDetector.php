<?php


namespace App\Loaders;


use App\Mappers\MappingTableCollection;
use App\Parsers\ParserDataPacketTable;
use App\Parsers\ParserMoTree;

class SchemaDetector
{
    /**
     * @var array
     */
    private $fileList = array();

    public function __construct(array $fileList = array())
    {
        $this->fileList = $fileList;
    }

    /**
     * @return MappingTableCollection
     */
    public function detect()
    {
        $mappingTableCollection = new MappingTableCollection(array());

        foreach ($this->fileList as $fileInfo){

            $detected_parser = null;

            $parserDataPacket = new ParserDataPacketTable($fileInfo);
            $parserMoTree = new ParserMoTree($fileInfo);

            if($parserDataPacket->detect()) {
                $detected_parser = $parserDataPacket;
            }elseif ($parserMoTree->detect()){
                $detected_parser = $parserMoTree;
            }else{
                // no parser for the file ignore it
                break;
            }

            foreach ($detected_parser as $table){
                if(!isset($mappingTableCollection[$table->getName()])){
                    $mappingTableCollection[$table->getName()] = $table;
                }else{
                    $existingTable = $mappingTableCollection[$table->getName()];
                    //$existingColumns = $existingTable->getColumns();

                    foreach($table->getColumns() as $column){
                        if(!$existingTable->columnExist($column)){
                            $existingTable->appendColumn($column);
                        }
                    }

                }

            }


        }

        return $mappingTableCollection;
    }

}