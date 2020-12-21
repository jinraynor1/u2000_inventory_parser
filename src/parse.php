<?php

require_once __DIR__ . '/../bootstrap.php';

$db = $container->get('database.master');

//$xmlFileMoTree = new \SplFileInfo(APP_PATH .'/samples/mo_tree.xml');
//$parser = new \App\Parsers\ParserMoTree($xmlFileMoTree);


$xmlFileDataPacket = new \SplFileInfo(APP_PATH .'/samples/datapacket.xml');
$parser = new \App\Parsers\ParserDataPacketTable($xmlFileDataPacket);

/**
 * This must be obtained from database, for now we are building it manually
 */
/*
$tables_schema = new \App\Mappers\MappingTable("mo_tree");
$tables_schema->appendColumn(new \App\Mappers\MappingColumn("fdn"));
$tables_schema->appendColumn(new \App\Mappers\MappingColumn("MOIndex"));

$mappingTableCollection = new \App\Mappers\MappingTableCollection(array($tables_schema));
*/
//$specificSchemaLoader = new \App\Loaders\SqliteSchemaLoader($db);
$specificSchemaLoader = new \App\Loaders\OracleSchemaLoader($db);
$mappingTableCollection = $specificSchemaLoader->getSchemaTables();


$loader = new \App\Loaders\DBLoader($db, $mappingTableCollection);

$loader->loadParserToDatabase($parser);


$nonExistingTables = $loader->getNonExistingTables();
$schemaLoader = new \App\Loaders\SchemaLoader( $nonExistingTables, $specificSchemaLoader);
$schemaLoader->load();




