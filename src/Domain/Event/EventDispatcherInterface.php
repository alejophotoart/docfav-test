<?php

declare(strict_types=1);

namespace App\Domain\Event;

interface EventDispatcherInterface
{
    public function dispatch(object $event): void;
    
    public function register(string $eventClass, EventListener $listener): void;
}