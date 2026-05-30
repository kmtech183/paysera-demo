# Paysera Transaction Demo

Symfony 7 app demonstrating account-to-account transfers.

## Run with Docker
```bash
docker-compose up --build
```
Visit: http://localhost:8080

## Features
- Account balance management
- Transfer validation (insufficient funds, same-account check)
- Transaction history
- Dockerized with MySQL + Nginx