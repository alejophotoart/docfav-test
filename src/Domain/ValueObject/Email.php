<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use App\Domain\Exception\InvalidEmailException;

final class Email
{
    private string $email;

    private function __construct(string $email)
    {
        $this->email = $email;
    }

    public static function fromString(string $email): self
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidEmailException('Formato de correo electrónico no válido');
        }

        return new self($email);
    }

    public function value(): string
    {
        return $this->email;
    }

    public function equals(Email $other): bool
    {
        return $this->email === $other->email;
    }

    public function __toString(): string
    {
        return $this->email;
    }
}