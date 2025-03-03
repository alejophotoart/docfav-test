.PHONY: up down install test test-unit test-integration init reset

up:
	docker-compose up -d

down:
	docker-compose down

install:
	docker-compose exec app composer install

schema:
	docker-compose exec app php vendor/bin/doctrine orm:schema-tool:create

test:
	docker-compose exec app composer test

test-unit:
	docker-compose exec app composer test-unit

test-integration:
	docker-compose exec app composer test-integration

init: up install schema

reset:
	docker-compose down -v
	docker-compose up -d
	docker-compose exec app composer install
	docker-compose exec app php vendor/bin/doctrine orm:schema-tool:create