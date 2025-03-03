<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

final class Name
{
    private string $name;

    private function __construct(string $name)
    {
        $this->name = $name;
    }

    public static function fromString(string $name): self
    {
        if (strlen($name) < 2) {
            throw new \InvalidArgumentException('El nombre debe tener al menos 2 caracteres.');
        }

        if (!preg_match('/^[a-zA-Z0-9\s\-\'_]+$/', $name)) {
            throw new \InvalidArgumentException('El nombre contiene caracteres no válidos');
        }

        return new self($name);
    }

    public function value(): string
    {
        return $this->name;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}