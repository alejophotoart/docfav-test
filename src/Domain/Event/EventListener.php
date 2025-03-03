<?php

declare(strict_types=1);

namespace App\Domain\Event;

interface EventListener
{
    public function handle(object $event): void;
}