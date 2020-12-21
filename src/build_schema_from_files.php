<?php
use \App\Parsers\ParserMoTree;
use \App\Parsers\ParserDataPacketTable;
require_once __DIR__ . '/../bootstrap.php';

$fileList = array(
    new \SplFileInfo(APP_PATH .'/samples/mo_tree.xml'),
    new \SplFileInfo(APP_PATH .'/samples/datapacket.xml'),
    new \SplFileInfo(APP_PATH .'/samples/datapacket_2.xml')


);
$mappingTableCollection = new \App\Mappers\MappingTableCollection(array());
foreach ($fileList as $fileInfo){

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
            $existingColumns = $existingTable->getColumns();

            foreach($table->getColumns() as $column){
                if(!$existingTable->columnExist($column)){
                    $existingTable->appendColumn($column);
                }
            }

        }

    }


}

$database = $container->get('database.master');
$specificSchemaLoader = new \App\Loaders\OracleSchemaLoader($database);
//$specificSchemaLoader = new \App\Loaders\SqliteSchemaLoader($database);
$schemaLoader = new \App\Loaders\SchemaLoader( $mappingTableCollection, $specificSchemaLoader);
$schemaLoader->load();



