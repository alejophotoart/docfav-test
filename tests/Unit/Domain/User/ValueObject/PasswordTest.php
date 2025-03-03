<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\ValueObject;

use App\Domain\Exception\WeakPasswordException;
use App\Domain\ValueObject\Password;
use PHPUnit\Framework\TestCase;

class PasswordTest extends TestCase
{
    public function testFromPlainPasswordShouldCreatePasswordFromValidString(): void
    {
        $plainPassword = 'StrongP@ss1';
        $password = Password::fromPlainPassword($plainPassword);
        
        $this->assertInstanceOf(Password::class, $password);
        $this->assertTrue($password->verify($plainPassword));
    }

    public function testFromPlainPasswordShouldThrowExceptionForShortPassword(): void
    {
        $this->expectException(WeakPasswordException::class);
        
        Password::fromPlainPassword('holamundo123!');
    }

    public function testFromPlainPasswordShouldThrowExceptionForPasswordWithoutUppercase(): void
    {
        $this->expectException(WeakPasswordException::class);
        
        Password::fromPlainPassword('Holamundo321!');
    }

    public function testFromPlainPasswordShouldThrowExceptionForPasswordWithoutNumber(): void
    {
        $this->expectException(WeakPasswordException::class);
        
        Password::fromPlainPassword('HolaMundo!');
    }

    public function testFromPlainPasswordShouldThrowExceptionForPasswordWithoutSpecialChar(): void
    {
        $this->expectException(WeakPasswordException::class);
        
        Password::fromPlainPassword('HolaMundo');
    }

    public function testFromHashShouldCreatePasswordFromHash(): void
    {
        $hash = password_hash('StrongP@ss1', PASSWORD_BCRYPT);
        $password = Password::fromHash($hash);
        
        $this->assertInstanceOf(Password::class, $password);
        $this->assertEquals($hash, $password->value());
    }

    public function testVerifyShouldReturnTrueForCorrectPassword(): void
    {
        $plainPassword = 'StrongP@ss1';
        $password = Password::fromPlainPassword($plainPassword);
        
        $this->assertTrue($password->verify($plainPassword));
    }

    public function testVerifyShouldReturnFalseForIncorrectPassword(): void
    {
        $plainPassword = 'StrongP@ss1';
        $password = Password::fromPlainPassword($plainPassword);
        
        $this->assertFalse($password->verify('WrongP@ss1'));
    }
}