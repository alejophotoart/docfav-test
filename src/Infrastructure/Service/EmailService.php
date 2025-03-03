<?php

declare(strict_types=1);

namespace App\Infrastructure\Service;

use App\Domain\Entity\User;

class EmailService
{
    public function sendWelcomeEmail(User $user): void
    {        
        echo sprintf(
            "Enviando correo electrónico de bienvenida a...\n",
            $user->name()->value(),
            $user->email()->value()
        );
    }
}