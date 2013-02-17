<h1>Bugger Test Page</h1>

<?php 
use 
	\Doctrine\ORM\Tools\Setup,
	\Doctrine\ORM\EntityManager,
	\Demo\Prototype\Bugger\Model;

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
    'user'     => 'user',
    'password' => 'password',
    'dbname'   => 'yourschema',
);

// obtaining the entity manager
$entityManager = \Doctrine\ORM\EntityManager::create($conn, $config);

$user = new Model\User();
$user->setName('Yay, some guy');

$entityManager->persist($user);
$entityManager->flush();

echo "Created User with ID " . $user->getId() . "\n";