<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Application\DTO\RegisterUserRequest;
use App\Application\UseCase\RegisterUserUseCase;
use App\Domain\Exception\InvalidEmailException;
use App\Domain\Exception\UserAlreadyExistsException;
use App\Domain\Exception\WeakPasswordException;

class RegisterUserController
{
    private RegisterUserUseCase $registerUserUseCase;

    public function __construct(RegisterUserUseCase $registerUserUseCase)
    {
        $this->registerUserUseCase = $registerUserUseCase;
    }

    public function __invoke(array $requestData): array
    {
        try {
            if (!isset($requestData['name']) || !isset($requestData['email']) || !isset($requestData['password'])) {
                return $this->errorResponse(400, 'Faltan campos obligatorios');
            }

            $request = new RegisterUserRequest(
                $requestData['name'],
                $requestData['email'],
                $requestData['password']
            );

            $response = $this->registerUserUseCase->execute($request);

            return [
                'status' => 'success',
                'data' => $response->toArray()
            ];
        } catch (InvalidEmailException $e) {
            return $this->errorResponse(400, $e->getMessage());
        } catch (WeakPasswordException $e) {
            return $this->errorResponse(400, $e->getMessage());
        } catch (UserAlreadyExistsException $e) {
            return $this->errorResponse(409, $e->getMessage());
        } catch (\InvalidArgumentException $e) {
            return $this->errorResponse(400, $e->getMessage());
        } catch (\Exception $e) {
            return $this->errorResponse(500, 'Se produjo un error inesperado');
        }
    }

    private function errorResponse(int $statusCode, string $message): array
    {
        return [
            'status' => 'error',
            'code' => $statusCode,
            'message' => $message
        ];
    }
}