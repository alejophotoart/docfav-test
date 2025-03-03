<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\DTO\RegisterUserRequest;
use App\Application\DTO\UserResponseDTO;
use App\Domain\Entity\User;
use App\Domain\Event\EventDispatcherInterface;
use App\Domain\Event\UserRegisteredEvent;
use App\Domain\Exception\UserAlreadyExistsException;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Name;
use App\Domain\ValueObject\Password;

class RegisterUserUseCase
{
    private UserRepositoryInterface $userRepository;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(
        UserRepositoryInterface $userRepository,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->userRepository = $userRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function execute(RegisterUserRequest $request): UserResponseDTO
    {
        $email = Email::fromString($request->email());
        
        if ($this->userRepository->findByEmail($email) !== null) {
            throw new UserAlreadyExistsException('Ya existe un usuario con este correo electrónico');
        }
        
        $user = User::create(
            Name::fromString($request->name()),
            $email,
            Password::fromPlainPassword($request->password())
        );
        
        $this->userRepository->save($user);
        
        $this->eventDispatcher->dispatch(new UserRegisteredEvent($user));
        
        return UserResponseDTO::fromUser($user);
    }
}