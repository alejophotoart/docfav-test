<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\ValueObject;

use App\Domain\ValueObject\Name;
use PHPUnit\Framework\TestCase;

class NameTest extends TestCase
{
    public function testFromStringShouldCreateNameFromValidString(): void
    {
        $nameString = 'Inigo Ardanza';
        $name = Name::fromString($nameString);
        
        $this->assertInstanceOf(Name::class, $name);
        $this->assertEquals($nameString, $name->value());
    }

    public function testFromStringShouldThrowExceptionForShortName(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        
        Name::fromString('A');
    }

    public function testFromStringShouldThrowExceptionForInvalidCharacters(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        
        Name::fromString('alejandro@calderon');
    }

    public function testToStringShouldReturnNameValue(): void
    {
        $nameString = 'Inigo Ardanza';
        $name = Name::fromString($nameString);
        
        $this->assertEquals($nameString, (string) $name);
    }
}