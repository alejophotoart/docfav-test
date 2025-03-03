<?php

declare(strict_types=1);

use App\Application\UseCase\RegisterUserUseCase;
use App\Domain\Event\EventDispatcher;
use App\Infrastructure\Controller\RegisterUserController;
use App\Infrastructure\Listener\UserRegisteredEmailListener;
use App\Infrastructure\Persistence\Doctrine\DoctrineUserRepository;
use App\Infrastructure\Service\EmailService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

require_once __DIR__ . '/../vendor/autoload.php';

$config = ORMSetup::createXMLMetadataConfiguration(
    [__DIR__ . '/../src/Infrastructure/Persistence/Doctrine/config'],
    true
);

$connectionParams = [
    'driver' => 'pdo_mysql',
    'host' => 'db',
    'user' => 'user',
    'password' => 'password',
    'dbname' => 'app_db',
];

$entityManager = EntityManager::create($connectionParams, $config);

$userRepository = new DoctrineUserRepository($entityManager);
$eventDispatcher = new EventDispatcher();
$emailService = new EmailService();

$userRegisteredListener = new UserRegisteredEmailListener($emailService);
$eventDispatcher->register(
    \App\Domain\Event\UserRegisteredEvent::class,
    $userRegisteredListener
);

$registerUserUseCase = new RegisterUserUseCase($userRepository, $eventDispatcher);

$registerUserController = new RegisterUserController($registerUserUseCase);

$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['REQUEST_URI'] ?? '/';

if ($method === 'POST' && $path === '/register') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true) ?? [];
    
    $response = $registerUserController($data);
    
    header('Content-Type: application/json');
    http_response_code($response['status'] === 'error' ? $response['code'] : 200);
    
    echo json_encode($response);
    exit;
}

header('Content-Type: application/json');
http_response_code(404);
echo json_encode([
    'status' => 'error',
    'code' => 404,
    'message' => 'Not Found'
]);