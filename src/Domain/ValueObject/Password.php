<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use App\Domain\Exception\WeakPasswordException;

final class Password
{
    private string $hashedPassword;

    private function __construct(string $hashedPassword)
    {
        $this->hashedPassword = $hashedPassword;
    }

    public static function fromPlainPassword(string $plainPassword): self
    {
        self::validate($plainPassword);
        
        $hashedPassword = password_hash($plainPassword, PASSWORD_BCRYPT);
        
        return new self($hashedPassword);
    }

    public static function fromHash(string $hashedPassword): self
    {
        return new self($hashedPassword);
    }

    public function value(): string
    {
        return $this->hashedPassword;
    }

    public function verify(string $plainPassword): bool
    {
        return password_verify($plainPassword, $this->hashedPassword);
    }

    private static function validate(string $password): void
    {
        if (strlen($password) < 8) {
            throw new WeakPasswordException('La contraseña debe tener al menos 8 caracteres.');
        }

        if (!preg_match('/[A-Z]/', $password)) {
            throw new WeakPasswordException('La contraseña debe contener al menos una letra mayúscula');
        }

        if (!preg_match('/[0-9]/', $password)) {
            throw new WeakPasswordException('La contraseña debe contener al menos un número');
        }

        if (!preg_match('/[^a-zA-Z0-9]/', $password)) {
            throw new WeakPasswordException('La contraseña debe contener al menos un carácter especial');
        }
    }
}