<?php

use App\Files\SearchLocalFiles;
use App\Files\ControlFiles;
use App\Loaders\FactorySchemaLoader;
use App\Parsers\FactoryParser;
use \App\Loaders\DBLoader;
use \App\Loaders\SchemaLoader;
use \App\Mappers\MappingTableCollection;
require_once __DIR__ . '/../bootstrap.php';

global $container;
global $logger;
global $config;
try {

    $db = $container->get('database.master');

    $searchPath = $config->get('file_scanner.path');
    $fromTime = new DateTime($config->get('file_scanner.search_time'));
    $toTime = new DateTime("now");
    $searchFiles = new SearchLocalFiles();
    $fileList = $searchFiles->findFiles($searchPath, $fromTime, $toTime, "xml");

    $controlFiles = new ControlFiles($db);
    $fileList = $controlFiles->filterFiles($fileList);

    $factorySchemaLoader = new FactorySchemaLoader($db);
    $specificSchemaLoader = $factorySchemaLoader->getSpecificSchemaLoader();

    $fileCount = count($fileList);


    if(!$fileCount){
        $logger->info("no new files founded, ending for now");
        exit;
    }

    $logger->info("founded $fileCount new files");

    $mappingTableCollection = new MappingTableCollection();
    $schemaLoader = new SchemaLoader($specificSchemaLoader);

    foreach ($fileList as $fileInfo) {
        $parser = FactoryParser::getParserForFile($fileInfo);
        if(!$parser){
            $logger->warning("schema step: no parser for file {$fileInfo->getBasename()}");
            continue;
        }
        foreach ($parser as $table) {
            $schemaLoader->loadTableSchema($table);
        }
    }

    $start = microtime(true);
    $databaseMappingTableCollection = $specificSchemaLoader->getSchemaTables();
    $loader = new DBLoader($db, $databaseMappingTableCollection);
    $end = microtime(true);
    $elapsedSeconds = $end - $start;
    $logger->info("get schema tables in $elapsedSeconds seconds");

    foreach ($fileList as $fileInfo) {
        $parser = FactoryParser::getParserForFile($fileInfo);
        if(!$parser){
            $logger->warning("loading step: no parser for file {$fileInfo->getBasename()}");
            continue;
        }
        $loader->loadParserToDatabase($parser);
        $logger->info("processing file {$fileInfo->getBasename()}");
        $controlFiles->insertSingleFile($fileInfo);
    }



} catch (Exception $e) {
    $logger->critical($e);
}