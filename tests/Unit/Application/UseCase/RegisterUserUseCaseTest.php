<?php

declare(strict_types=1);

namespace Tests\Unit\Application\UseCase;

use App\Application\DTO\RegisterUserRequest;
use App\Application\DTO\UserResponseDTO;
use App\Application\UseCase\RegisterUserUseCase;
use App\Domain\Entity\User;
use App\Domain\Event\EventDispatcherInterface;
use App\Domain\Event\UserRegisteredEvent;
use App\Domain\Exception\InvalidEmailException;
use App\Domain\Exception\UserAlreadyExistsException;
use App\Domain\Exception\WeakPasswordException;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\Email;
use PHPUnit\Framework\TestCase;

class RegisterUserUseCaseTest extends TestCase
{
    private UserRepositoryInterface $userRepository;
    private EventDispatcherInterface $eventDispatcher;
    private RegisterUserUseCase $useCase;

    protected function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
        $this->eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $this->useCase = new RegisterUserUseCase($this->userRepository, $this->eventDispatcher);
    }

    public function testExecuteShouldRegisterUserSuccessfully(): void
    {
        $request = new RegisterUserRequest('Alejandro Calderon R', 'alejandronba98@@gmail.com', 'Hola@Mundo1');
        
        $this->userRepository
            ->expects($this->once())
            ->method('findByEmail')
            ->with($this->callback(function (Email $email) {
                return $email->value() === 'alejandronba98@@gmail.com';
            }))
            ->willReturn(null);
        
        $this->userRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(User::class));
        
        $this->eventDispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->isInstanceOf(UserRegisteredEvent::class));
        
        $response = $this->useCase->execute($request);
        
        $this->assertInstanceOf(UserResponseDTO::class, $response);
    }

    public function testExecuteShouldThrowExceptionWhenEmailIsInvalid(): void
    {
        $request = new RegisterUserRequest('Alejandro Calderon R', 'invalid-email', 'Hola@Mundo1');
        
        $this->expectException(InvalidEmailException::class);
        
        $this->useCase->execute($request);
    }

    public function testExecuteShouldThrowExceptionWhenPasswordIsWeak(): void
    {
        $request = new RegisterUserRequest('Alejandro Calderon R', 'alejandronba98@@gmail.com', 'weak');
        
        $this->expectException(WeakPasswordException::class);
        
        $this->useCase->execute($request);
    }

    public function testExecuteShouldThrowExceptionWhenUserAlreadyExists(): void
    {
        $request = new RegisterUserRequest('Alejandro Calderon R', 'alejandronba98@@gmail.com', 'Hola@Mundo1');
        
        $existingUser = $this->createMock(User::class);
        
        $this->userRepository
            ->expects($this->once())
            ->method('findByEmail')
            ->willReturn($existingUser);
        
        $this->expectException(UserAlreadyExistsException::class);
        
        $this->useCase->execute($request);
    }
}