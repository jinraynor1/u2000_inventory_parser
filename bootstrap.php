<?php

require_once(__DIR__ . '/vendor/autoload.php');



// configuration manager
$config = new \Config\Repository(
    new \Config\Loader\FileLoader(APP_PATH . '/config')
);

$logger = new Monolog\Logger('default_logger');
$logger->pushHandler(new \Monolog\Handler\StreamHandler('php://stdout', \Monolog\Logger::INFO));

$logger->pushHandler(new \Monolog\Handler\RotatingFileHandler(
    APP_PATH . '/logs/app.log', 7, \Monolog\Logger::INFO, true, 0666
));




// build dependencies
$builder = new DI\ContainerBuilder();
$builder->setDefinitionCache(new Doctrine\Common\Cache\ArrayCache());
$builder->useAutowiring(false);
$builder->useAnnotations(false);
$builder->addDefinitions(ROOT_PATH . '/dependencies.php');
$container = $builder->build();

$container->set('app.config', $config);
