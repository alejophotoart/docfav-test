<?php

declare(strict_types=1);

namespace Tests\Integration\Persistence;

use App\Domain\Entity\User;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Name;
use App\Domain\ValueObject\Password;
use App\Domain\ValueObject\UserId;
use App\Infrastructure\Persistence\Doctrine\DoctrineUserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\Setup;
use PHPUnit\Framework\TestCase;

class DoctrineUserRepositoryTest extends TestCase
{
    private EntityManagerInterface $entityManager;
    private DoctrineUserRepository $repository;

    protected function setUp(): void
    {
        $isDevMode = true;
        $config = Setup::createAnnotationMetadataConfiguration([__DIR__ . '/../../../src'], $isDevMode);
        
        $connection = [
            'driver' => 'pdo_sqlite',
            'memory' => true,
        ];
        
        $this->entityManager = EntityManager::create($connection, $config);
        
        $schemaTool = new SchemaTool($this->entityManager);
        $classes = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool->createSchema($classes);
        
        $this->repository = new DoctrineUserRepository($this->entityManager);
    }

    public function testSaveShouldPersistUser(): void
    {
        $user = User::create(
            Name::fromString('Alejandro Calderon R'),
            Email::fromString('alejandronba98@@gmail.com'),
            Password::fromPlainPassword('Hola@Mundo1')
        );
        
        $this->repository->save($user);
        
        $this->entityManager->clear();
        
        $foundUser = $this->repository->findById($user->id());
        
        $this->assertNotNull($foundUser);
        $this->assertEquals($user->id()->value(), $foundUser->id()->value());
        $this->assertEquals($user->name()->value(), $foundUser->name()->value());
        $this->assertEquals($user->email()->value(), $foundUser->email()->value());
    }

    public function testFindByIdShouldReturnNullWhenUserDoesNotExist(): void
    {
        $nonExistentId = UserId::generate();
        
        $result = $this->repository->findById($nonExistentId);
        
        $this->assertNull($result);
    }

    public function testFindByEmailShouldReturnUserWhenEmailExists(): void
    {
        $user = User::create(
            Name::fromString('Alejandro Calderon R'),
            Email::fromString('alejandronba98@@gmail.com'),
            Password::fromPlainPassword('Hola@Mundo1')
        );
        
        $this->repository->save($user);
        
        $this->entityManager->clear();
        
        $email = Email::fromString('alejandronba98@@gmail.com');
        $foundUser = $this->repository->findByEmail($email);
        
        $this->assertNotNull($foundUser);
        $this->assertEquals($user->id()->value(), $foundUser->id()->value());
    }

    public function testFindByEmailShouldReturnNullWhenEmailDoesNotExist(): void
    {
        $nonExistentEmail = Email::fromString('noexiste@gmail.com');
        
        $result = $this->repository->findByEmail($nonExistentEmail);
        
        $this->assertNull($result);
    }

    public function testDeleteShouldRemoveUser(): void
    {
        $user = User::create(
            Name::fromString('Alejandro Calderon R'),
            Email::fromString('alejandronba98@gmail.com'),
            Password::fromPlainPassword('Hola@Mundo1')
        );
        
        $this->repository->save($user);
        
        $this->assertNotNull($this->repository->findById($user->id()));
        
        $this->repository->delete($user->id());
        
        $this->assertNull($this->repository->findById($user->id()));
    }
}