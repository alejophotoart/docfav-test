<?php

declare(strict_types=1);

namespace App\Domain\Event;

class EventDispatcher implements EventDispatcherInterface
{
    /**
     * @var array<string, array<EventListener>>
     */
    private array $listeners = [];

    public function dispatch(object $event): void
    {
        $eventClass = get_class($event);
        
        if (!isset($this->listeners[$eventClass])) {
            return;
        }
        
        foreach ($this->listeners[$eventClass] as $listener) {
            $listener->handle($event);
        }
    }

    public function register(string $eventClass, EventListener $listener): void
    {
        $this->listeners[$eventClass][] = $listener;
    }
}