<?php


namespace App\Parsers;


class FactoryParser
{
    /**
     * @param \SplFileInfo $fileInfo
     * @return InventoryParserInterface|false
     */
    public static function getParserForFile(\SplFileInfo $fileInfo)
    {

        $parserDataPacket = new ParserDataPacketTable($fileInfo);
        $parserMoTree = new ParserMoTree($fileInfo);

        if($parserDataPacket->detect()) {
            $detected_parser = $parserDataPacket;
        }elseif ($parserMoTree->detect()){
            $detected_parser = $parserMoTree;
        }else{
            // no parser for the file ignore it
            return false;
        }
        return $detected_parser;
    }
}