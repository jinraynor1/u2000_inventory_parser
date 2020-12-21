<?php


namespace App\Loaders;


use App\DatabaseAdapter;
use App\DatabaseInterface;

class FactorySchemaLoader
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
     * @return SchemaLoaderInterface
     * @throws \Exception
     */
    public function getSpecificSchemaLoader()
    {
        $driver =  $this->database->getDriver();

        switch($driver){
            case 'oci' :
                $schemaLoader =  new OracleSchemaLoader($this->database);
            break;

            case 'sqlite' :
                $schemaLoader =  new SqliteSchemaLoader($this->database);
            break;

            default:
                throw new \Exception("Invalid driver");
            break;
        }

        return $schemaLoader;

    }

}