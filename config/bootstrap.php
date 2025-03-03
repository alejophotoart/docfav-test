<?php

declare(strict_types=1);

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

require_once __DIR__ . '/../vendor/autoload.php';

$config = ORMSetup::createXMLMetadataConfiguration(
    [__DIR__ . '/../src/Infrastructure/Persistence/Doctrine/config'],
    true
);

$connectionParams = [
    'driver' => 'pdo_mysql',
    'host' => $_ENV['DB_HOST'] ?? 'db',
    'user' => $_ENV['DB_USER'] ?? 'user',
    'password' => $_ENV['DB_PASSWORD'] ?? 'password',
    'dbname' => $_ENV['DB_NAME'] ?? 'app_db',
];

return EntityManager::create($connectionParams, $config);