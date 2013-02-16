<?php

require_once('/Snap/Lib/Core/Bootstrap.php');

$router = new Demo\Prototype\Bugger\Control\Router();

$router->serve();
/*
use \Doctrine\ORM\Tools\Setup;
use \Doctrine\ORM\EntityManager;

// bootstrap_doctrine.php

// Create a simple "default" Doctrine ORM configuration for XML Mapping
$isDevMode = true;
$config = Setup::createAnnotationMetadataConfiguration(array("/Demo/Model/Doctrine"), $isDevMode);
// or if you prefer yaml or annotations
//$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__."/entities"), $isDevMode);
//$config = Setup::createYAMLMetadataConfiguration(array(__DIR__."/config/yaml"), $isDevMode);

// database configuration parameters
$conn = array(
    'driver'   => 'pdo_mysql',
    'user'     => 'root',
    'password' => 'root',
    'dbname'   => 'foo',
);

// obtaining the entity manager
$entityManager = \Doctrine\ORM\EntityManager::create($conn, $config);
*/