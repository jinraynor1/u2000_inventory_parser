<?php

require_once __DIR__ . '/../bootstrap.php';

$db = $container->get('database.master');

$xmlFileMoTree = new \SplFileInfo(APP_PATH .'/samples/mo_tree.xml');
$parser = new \App\Parsers\ParserMoTree($xmlFileMoTree);

/**
 * This must be obtained from database, for now we are building it manually
 */
$tables_schema = new \App\Mappers\MappingTable("mo_tree");
$tables_schema->appendColumn(new \App\Mappers\MappingColumn("fdn"));
$tables_schema->appendColumn(new \App\Mappers\MappingColumn("MOIndex"));


$mappingTableCollection = new \App\Mappers\MappingTableCollection(array($tables_schema));

$loader = new \App\Loaders\DBLoader($mappingTableCollection);

$loader->loadParserToDatabase($parser);






