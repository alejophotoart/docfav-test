<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\ValueObject;

use App\Domain\Exception\InvalidEmailException;
use App\Domain\ValueObject\Email;
use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{
    public function testFromStringShouldCreateEmailFromValidString(): void
    {
        $emailString = 'test@example.com';
        $email = Email::fromString($emailString);
        
        $this->assertInstanceOf(Email::class, $email);
        $this->assertEquals($emailString, $email->value());
    }

    public function testFromStringShouldThrowExceptionForInvalidEmail(): void
    {
        $this->expectException(InvalidEmailException::class);
        
        Email::fromString('email-no-valido');
    }

    public function testEqualsShouldReturnTrueForSameEmail(): void
    {
        $emailString = 'alejo@example.com';
        $email1 = Email::fromString($emailString);
        $email2 = Email::fromString($emailString);
        
        $this->assertTrue($email1->equals($email2));
    }

    public function testEqualsShouldReturnFalseForDifferentEmail(): void
    {
        $email1 = Email::fromString('jose@example.com');
        $email2 = Email::fromString('inigo@example.com');
        
        $this->assertFalse($email1->equals($email2));
    }

    public function testToStringShouldReturnEmailValue(): void
    {
        $emailString = 'alejo@example.com';
        $email = Email::fromString($emailString);
        
        $this->assertEquals($emailString, (string) $email);
    }
}