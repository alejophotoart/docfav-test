<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Entity;

use App\Domain\Entity\User;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Name;
use App\Domain\ValueObject\Password;
use App\Domain\ValueObject\UserId;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testCreateShouldCreateNewUserWithGivenValues(): void
    {
        $name = Name::fromString('Alejandro Calderon R');
        $email = Email::fromString('alejandronba98@@gmail.com');
        $password = Password::fromPlainPassword('Hola@Mundo1');
        
        $user = User::create($name, $email, $password);
        
        $this->assertInstanceOf(User::class, $user);
        $this->assertInstanceOf(UserId::class, $user->id());
        $this->assertSame($name, $user->name());
        $this->assertSame($email, $user->email());
        $this->assertSame($password, $user->password());
        $this->assertInstanceOf(DateTimeImmutable::class, $user->createdAt());
    }

    public function testReconstituteShouldRecreateUserWithExistingValues(): void
    {
        $id = UserId::generate();
        $name = Name::fromString('Alejandro Calderon R');
        $email = Email::fromString('alejandronba98@@gmail.com');
        $password = Password::fromPlainPassword('Hola@Mundo1');
        $createdAt = new DateTimeImmutable('2025-02-28 12:00:00');
        
        $user = User::reconstitute($id, $name, $email, $password, $createdAt);
        
        $this->assertInstanceOf(User::class, $user);
        $this->assertSame($id, $user->id());
        $this->assertSame($name, $user->name());
        $this->assertSame($email, $user->email());
        $this->assertSame($password, $user->password());
        $this->assertSame($createdAt, $user->createdAt());
    }
}