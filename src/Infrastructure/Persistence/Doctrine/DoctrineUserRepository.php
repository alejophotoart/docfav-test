<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\Entity\User;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Name;
use App\Domain\ValueObject\Password;
use App\Domain\ValueObject\UserId;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineUserRepository implements UserRepositoryInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function save(User $user): void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function findById(UserId $id): ?User
    {
        return $this->entityManager->find(User::class, $id->value());
    }

    public function findByEmail(Email $email): ?User
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        
        $queryBuilder
            ->select('u')
            ->from(User::class, 'u')
            ->where('u.email = :email')
            ->setParameter('email', $email->value());
        
        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    public function delete(UserId $id): void
    {
        $user = $this->findById($id);
        
        if ($user !== null) {
            $this->entityManager->remove($user);
            $this->entityManager->flush();
        }
    }
}