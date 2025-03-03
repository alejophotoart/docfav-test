<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\InMemory;

use App\Domain\Entity\User;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\UserId;

class InMemoryUserRepository implements UserRepositoryInterface
{
    /**
     * @var User[]
     */
    private array $users = [];

    public function save(User $user): void
    {
        $this->users[$user->id()->value()] = $user;
    }

    public function findById(UserId $id): ?User
    {
        return $this->users[$id->value()] ?? null;
    }

    public function findByEmail(Email $email): ?User
    {
        foreach ($this->users as $user) {
            if ($user->email()->equals($email)) {
                return $user;
            }
        }
        
        return null;
    }

    public function delete(UserId $id): void
    {
        unset($this->users[$id->value()]);
    }
}