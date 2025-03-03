<?php

declare(strict_types=1);

namespace App\Infrastructure\Listener;

use App\Domain\Event\EventListener;
use App\Domain\Event\UserRegisteredEvent;
use App\Infrastructure\Service\EmailService;

class UserRegisteredEmailListener implements EventListener
{
    private EmailService $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    public function handle(object $event): void
    {
        if (!$event instanceof UserRegisteredEvent) {
            return;
        }

        $this->emailService->sendWelcomeEmail($event->user());
    }
}