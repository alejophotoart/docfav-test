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
    bashCopygit clone <repository-url>
    cd <repository-directory>

2. Inicializar el proyecto usando Make:
