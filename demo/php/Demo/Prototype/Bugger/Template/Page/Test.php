<h1>Bugger Test Page</h1>

<?php 
use 
	\Doctrine\ORM\Tools\Setup,
	\Doctrine\ORM\EntityManager,
	\Demo\Prototype\Bugger\Model\Doctrine as Model;

// bootstrap_doctrine.php

// Create a simple "default" Doctrine ORM configuration for XML Mapping
$isDevMode = true;
$config = Setup::createAnnotationMetadataConfiguration(array(""), $isDevMode);
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

$prod = new Model\Junk\CodedProduct();
$prod->setName('Product'.time());
$prod->setCode('woot woot');

$entityManager->persist($prod);

$entityManager->flush();

echo "Created User with ID " . $user->getId() . "\n";