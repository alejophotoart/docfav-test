<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Name;
use App\Domain\ValueObject\Password;
use App\Domain\ValueObject\UserId;
use DateTimeImmutable;

class User
{
    private UserId $id;
    private Name $name;
    private Email $email;
    private Password $password;
    private DateTimeImmutable $createdAt;

    private function __construct(
        UserId $id,
        Name $name,
        Email $email,
        Password $password,
        DateTimeImmutable $createdAt
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->createdAt = $createdAt;
    }

    public static function create(
        Name $name,
        Email $email,
        Password $password
    ): self {
        return new self(
            UserId::generate(),
            $name,
            $email,
            $password,
            new DateTimeImmutable()
        );
    }

    public static function reconstitute(
        UserId $id,
        Name $name,
        Email $email,
        Password $password,
        DateTimeImmutable $createdAt
    ): self {
        return new self(
            $id,
            $name,
            $email,
            $password,
            $createdAt
        );
    }

    public function id(): UserId
    {
        return $this->id;
    }

    public function name(): Name
    {
        return $this->name;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function password(): Password
    {
        return $this->password;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}