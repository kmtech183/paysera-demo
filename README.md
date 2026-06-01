# Paysera Transaction Demo

Symfony 7 app demonstrating account-to-account transfers.

## Run with Docker
```bash
git clone https://github.com/kmtech183/paysera-demo
cd paysera-demo
cp .env.example .env
docker-compose up -d
docker compose exec app bash -c "php bin/console doctrine:migrations:migrate --no-interaction && php bin/console doctrine:fixtures:load --no-interaction"
```
Visit: http://localhost:8080

## Features
- Account balance management
- Transfer validation (insufficient funds, same-account check)
- Transaction history
- Dockerized with MySQL + Nginx