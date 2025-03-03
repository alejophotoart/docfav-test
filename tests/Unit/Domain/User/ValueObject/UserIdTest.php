<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\ValueObject;

use App\Domain\ValueObject\UserId;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class UserIdTest extends TestCase
{
    public function testGenerateShouldReturnValidUserId(): void
    {
        $userId = UserId::generate();
        
        $this->assertInstanceOf(UserId::class, $userId);
        $this->assertTrue(Uuid::isValid($userId->value()));
    }

    public function testFromStringShouldCreateUserIdFromValidUuid(): void
    {
        $uuid = Uuid::uuid4()->toString();
        $userId = UserId::fromString($uuid);
        
        $this->assertInstanceOf(UserId::class, $userId);
        $this->assertEquals($uuid, $userId->value());
    }

    public function testFromStringShouldThrowExceptionForInvalidUuid(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        
        UserId::fromString('uuid-no-valido');
    }

    public function testEqualsShouldReturnTrueForSameId(): void
    {
        $uuid = Uuid::uuid4()->toString();
        $userId1 = UserId::fromString($uuid);
        $userId2 = UserId::fromString($uuid);
        
        $this->assertTrue($userId1->equals($userId2));
    }

    public function testEqualsShouldReturnFalseForDifferentId(): void
    {
        $userId1 = UserId::generate();
        $userId2 = UserId::generate();
        
        $this->assertFalse($userId1->equals($userId2));
    }

    public function testToStringShouldReturnIdValue(): void
    {
        $uuid = Uuid::uuid4()->toString();
        $userId = UserId::fromString($uuid);
        
        $this->assertEquals($uuid, (string) $userId);
    }
}