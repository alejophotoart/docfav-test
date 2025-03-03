<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use Ramsey\Uuid\Uuid;

final class UserId
{
    private string $id;

    private function __construct(string $id)
    {
        $this->id = $id;
    }

    public static function generate(): self
    {
        return new self(Uuid::uuid4()->toString());
    }

    public static function fromString(string $id): self
    {
        if (!Uuid::isValid($id)) {
            throw new \InvalidArgumentException('Formato de ID de usuario no válido');
        }

        return new self($id);
    }

    public function value(): string
    {
        return $this->id;
    }

    public function equals(UserId $other): bool
    {
        return $this->id === $other->id;
    }

    public function __toString(): string
    {
        return $this->id;
    }
}