<?php


return [
    'database.master' => function (\Psr\Container\ContainerInterface $c) {

            $config = $c->get('app.config');

            $db_username = $config->get('database.master.username');
            $db_password = $config->get('database.master.password');
            $db_driver = $config->get('database.master.driver');
            $db_port = $config->get('database.master.port');
            $db_hostname = $config->get('database.master.hostname');
            $tns = "";

            $conn = new \PDO("driver:dbname=$tns",$db_username,$db_password);
            return $conn;

    },
];



