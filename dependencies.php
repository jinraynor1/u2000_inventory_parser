<?php


return [

    'internal.fetchAdapter' => function (\Psr\Container\ContainerInterface $c) {
        return function ($db_driver, $db_dsn, $db_username, $db_password, $quote_identifier_character, $queries_session = false) {

            $db = new \PDO("$db_driver:$db_dsn", $db_username, $db_password);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            if ($queries_session) {
                foreach ($queries_session as $query) {
                    $db->query($query);
                }
            }

            $adapter = new \App\DatabaseAdapter($db, $quote_identifier_character);
            return $adapter;
        };
    },
    'database.master' => function (\Psr\Container\ContainerInterface $c) {

        $config = $c->get('app.config');

        $db_username = $config->get('database.master.username');
        $db_password = $config->get('database.master.password');
        $db_driver = $config->get('database.master.driver');
        $db_dsn = $config->get('database.master.dsn');

        $quote_identifier_character = $config->get('database.master.quote_identifier_character');


        $queries_session = $config->get('database.master.queries_session');

        return $c->get('internal.fetchAdapter')
        ($db_driver, $db_dsn, $db_username, $db_password, $quote_identifier_character, $queries_session);

    },


    'database.phpunit.oracle' => function (\Psr\Container\ContainerInterface $c) {


        if (!defined('PHPUNIT_YOURAPPLICATION_TESTSUITE') || !PHPUNIT_YOURAPPLICATION_TESTSUITE)
            throw new Exception("you cannot fetch database in non phpunit mote ");


        $db_username = $_ENV['DATABASE_ORACLE_USER'];
        $db_password = $_ENV['DATABASE_ORACLE_PASS'];
        $db_driver = $_ENV['DATABASE_ORACLE_DRIVER'];
        $db_dsn = $_ENV['DATABASE_ORACLE_DSN'];

        $quote_identifier_character = $_ENV['DATABASE_ORACLE_QUOTE_IDENTIFIER'];
        $queries_session = json_decode($_ENV['DATABASE_ORACLE_QUERIES_SESSION'], true);


        return $c->get('internal.fetchAdapter')
        ($db_driver, $db_dsn, $db_username, $db_password, $quote_identifier_character, $queries_session);
    },


    'database.phpunit.mysql' => function (\Psr\Container\ContainerInterface $c) {

        if (!defined('PHPUNIT_YOURAPPLICATION_TESTSUITE') || !PHPUNIT_YOURAPPLICATION_TESTSUITE)
            throw new Exception("you cannot fetch database in non phpunit mote ");


        $db_username = $_ENV['DATABASE_MYSQL_USER'];
        $db_password = $_ENV['DATABASE_MYSQL_PASS'];
        $db_driver = $_ENV['DATABASE_MYSQL_DRIVER'];
        $db_dsn = $_ENV['DATABASE_MYSQL_DSN'];

        $quote_identifier_character = $_ENV['DATABASE_MYSQL_QUOTE_IDENTIFIER'];
        $queries_session = null;

        return $c->get('internal.fetchAdapter')
        ($db_driver, $db_dsn, $db_username, $db_password, $quote_identifier_character, $queries_session);
    },
    'database.phpunit.sqlite' => function (\Psr\Container\ContainerInterface $c) {

        if (!defined('PHPUNIT_YOURAPPLICATION_TESTSUITE') || !PHPUNIT_YOURAPPLICATION_TESTSUITE)
            throw new Exception("you cannot fetch database in non phpunit mote ");



        $db_username = $_ENV['DATABASE_SQLITE_USER'];
        $db_password = $_ENV['DATABASE_SQLITE_PASS'];
        $db_driver = $_ENV['DATABASE_SQLITE_DRIVER'];
        $db_dsn = $_ENV['DATABASE_SQLITE_DSN'];

        $quote_identifier_character = $_ENV['DATABASE_SQLITE_QUOTE_IDENTIFIER'];
        

        $queries_session = null;

        return $c->get('internal.fetchAdapter')
        ($db_driver, $db_dsn, $db_username, $db_password, $quote_identifier_character, $queries_session);
    }
];



