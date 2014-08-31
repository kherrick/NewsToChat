<?php
$autoload = '/vendor/autoload.php';

if (file_exists(__DIR__ . $autoload)) {
    $loader = require_once __DIR__ . $autoload;
} else {
    echo "You must set up the project dependencies.\n";
    exit(1);
}

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

$dbParams = array(
    'driver' => 'pdo_sqlite',
    'path' => __DIR__ . '/databases/db.sqlite',
);

$isDevMode = false;
$configPaths = array(__DIR__ . '/config/xml');
$config = Setup::createXMLMetadataConfiguration($configPaths, $isDevMode);
$entityManager = EntityManager::create($dbParams, $config);
