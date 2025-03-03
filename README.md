API de registro de usuarios - DDD y arquitectura limpia
Este proyecto implementa una API de registro de usuarios siguiendo los principios de diseño impulsado por el dominio (DDD) y arquitectura limpia. Está construido sin marcos (excepto Doctrine ORM) según los requisitos.
Estructura del proyecto
El proyecto sigue los principios de DDD y arquitectura limpia con una clara separación entre:

* Capa de dominio: contiene entidades, objetos de valor, interfaces de repositorio y eventos de dominio
* Capa de aplicación: contiene casos de uso y DTO
* Capa de infraestructura: contiene implementaciones de repositorios, controladores y escuchas de eventos

* Requisitos

1. PHP 8.1 o superior
2. Docker y Docker Compose
3. Composer

* Configuración e instalación

1. Clonar el repositorio:
    bashCopygit clone https://github.com/alejophotoart/docfav-test.git
    cd https://github.com/alejophotoart/docfav-test.git

2. Inicializar el proyecto usando Make:
    make init

Este comando:

* Iniciará los contenedores de Docker
* Instalará dependencias
* Creará el esquema de la base de datos

Ejecutará pruebas
Para ejecutar todas las pruebas:
    make test
Para ejecutar solo pruebas unitarias:
    make test-unit
Para ejecutar solo pruebas de integración:
    make test-integration

Uso de la API
La API proporciona un punto final para registrar usuarios:

Registrar un nuevo usuario

http
-------------------------//-------------------------------
POST /register
Content-Type: application/json

{
    "name": "Alejandro Calderon R",
    "email": "alejandronba98@@gmail.com",
    "password": "Hola@Mundo1"
}

-------------------------//-------------------------------
Respuesta exitosa:

{
    "status": "success",
    "data": {
        "id": "550e8400-e29b-41d4-a716-446655440000",
        "name": "Alejandro Calderon R",
        "email": "alejandronba98@@gmail.com",
        "created_at": "2023-03-01T12:00:00+00:00"
    }
}

Respuesta de error:

* Correo electrónico ya en uso:
-------------------------//-------------------------------
{
    "status": "error",
    "code": 409,
    "message": "Ya existe un usuario con este correo electrónico"
}


* Email invalido
-------------------------//-------------------------------
{
    "status": "error",
    "code": 400,
    "message": "Formato de correo electrónico no válido"
}


* Contraseña débil
-------------------------//-------------------------------
{
    "status": "error",
    "code": 400,
    "message": "La contraseña debe contener al menos una letra mayúscula, un número y un carácter especial"
}


Detalles del proyecto
Capa de dominio

*    Entidades: Usuario
*    Objetos de valor: UserId, Email, Name, Password
*    Interfaces de repositorio: UserRepositoryInterface
*    Eventos: UserRegisteredEvent

Capa de aplicación

*    Casos de uso: RegisterUserUseCase
*    DTO: RegisterUserRequest, UserResponseDTO

Capa de infraestructura

*    Controladores: RegisterUserController
*    Repositorios: DoctrineUserRepository, InMemoryUserRepository
*    Escuchadores de eventos: UserRegisteredEmailListener

Características clave

* Entidades inmutables con objetos de valor
* Implementación de patrones de repositorio
* Eventos de dominio para operaciones acopladas de forma flexible
* Separación clara de preocupaciones
* Cobertura de pruebas completa
* Contenedorización de Docker
