<?php


return [
    'database.master' => function (\Psr\Container\ContainerInterface $c) {

        $config = $c->get('app.config');

        if (defined('PHPUNIT_YOURAPPLICATION_TESTSUITE') && PHPUNIT_YOURAPPLICATION_TESTSUITE)
        {
            $db_username = $_ENV['DATABASE_USER'];
            $db_password = $_ENV['DATABASE_PASS'];
            $db_driver = $_ENV['DATABASE_DRIVER'];
            $db_dsn = $_ENV['DATABASE_DSN'];

            $quote_identifier_character = $_ENV['DATABASE_QUOTE_IDENTIFIER'];

        }else{

            $db_username = $config->get('database.master.username');
            $db_password = $config->get('database.master.password');
            $db_driver = $config->get('database.master.driver');
            $db_dsn = $config->get('database.master.dsn');

            $quote_identifier_character = $config->get('database.master.quote_identifier_character');
        }

        $queries_session = $config->get('database.master.queries_session');



            $db = new \PDO("$db_driver:$db_dsn",$db_username,$db_password);
            //$db = new \PDO("sqlite:database.sqlite");
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            if($queries_session){
                foreach ($queries_session as $query){
                    $db->query($query);
                }
            }

            $adapter = new \App\DatabaseAdapter($db, $quote_identifier_character);
            return $adapter;

    },
];



