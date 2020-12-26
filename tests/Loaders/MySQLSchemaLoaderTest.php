<?php
namespace Test\Loaders;

require_once __DIR__ .'/AbstractSchemaLoader.php';


class MysqlSchemaLoaderTest extends AbstractSchemaLoader
{

    public function getDatabase()
    {
        global $container;
        return $container->get('database.phpunit.mysql');
        //$db = new \PDO("sqlite:database.sqlite");
    }
}