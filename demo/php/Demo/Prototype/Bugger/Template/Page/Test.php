<h1>Bugger Test Page</h1>

<?php 
use 
	\Doctrine\ORM\Tools\Setup,
	\Doctrine\ORM\EntityManager,
	\Demo\Prototype\Bugger\Model\Doctrine as Model;

// bootstrap_doctrine.php

// Create a simple "default" Doctrine ORM configuration for XML Mapping

// or if you prefer yaml or annotations
//$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__."/entities"), $isDevMode);
//$config = Setup::createYAMLMetadataConfiguration(array(__DIR__."/config/yaml"), $isDevMode);

// database configuration parameters

$user = new Model\User();
$user->setName( 'Umble' );

$user->persist();

$user = new Model\User();
$user->setName( 'Brian' );

$user->persist();

$bug = new Model\Bug();
$bug->setDescription("Something does not work!");
$bug->setCreated(new DateTime("now"));
$bug->setStatus("OPEN");

$bug->setReporter( $user );

$bug->persist();

echo "user : " . $user->getId() . "<br/>";
echo "bug  : " . $bug->getId() . "<br/>";