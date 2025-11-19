# docker-php (PHP 8.3 + Nginx + PostgreSQL)

[![CI](https://github.com/cschubrt/docker-php-nginx-postgresql/actions/workflows/ci.yml/badge.svg)](https://github.com/cschubrt/docker-php-nginx-postgresql/actions/workflows/ci.yml)
[![License: Apache-2.0](https://img.shields.io/badge/License-Apache%202.0-blue.svg)](https://opensource.org/licenses/Apache-2.0)
[![PHP 8.3](https://img.shields.io/badge/PHP-8.3-777BB4?logo=php&logoColor=white)](https://www.php.net/)
[![Docker Compose](https://img.shields.io/badge/Docker-Compose-2496ED?logo=docker&logoColor=white)](https://docs.docker.com/compose/)
[![PostgreSQL 15](https://img.shields.io/badge/PostgreSQL-15-336791?logo=postgresql&logoColor=white)](https://www.postgresql.org/)
[![Nginx](https://img.shields.io/badge/Nginx-stable-009639?logo=nginx&logoColor=white)](https://nginx.org/)

This workspace contains a Docker-based setup to run a PHP 8.3 application with Nginx and PostgreSQL.
When commits are pushed to the main branch, CI/CD builds and pushes both development and production images to Docker Hub: [craigschubert/docker-php](https://hub.docker.com/repository/docker/craigschubert/docker-php)

Quick start (PowerShell):

```powershell
# Build and start services (foreground):
docker compose up --build

# Or run in background:
docker compose up --build -d

# Stop and remove containers (and network):
docker compose down
```

PostgreSQL defaults (see `docker-compose.yml`):
- DB: `appdb`
- User: `appuser`
- Password: `secret`

Running Composer and tests

When working locally with the code mounted into the container you may need to install Composer dependencies. You can do that inside the `create-dev` service:

```powershell
docker compose run --rm create-dev composer install
```

Run PHPUnit (inside the container):

```powershell
docker compose run --rm create-dev vendor\bin\phpunit --colors=always
```

Open an interactive psql session inside the db container:
```powershell
docker compose exec db-dev psql -U appuser -d appdb
```

Open an interactive shell session inside the container:
```powershell
docker compose exec create-dev bash
```

Start a new bash session (doesn't require a running service):
```powershell
docker compose run --rm create-dev bash
```

List files in the create-dev container:
```powershell
docker compose exec create-dev ls -la /var/www/html
```

Check PHP version in create-dev:
```powershell
docker compose exec create-dev php -v
```

Run Composer in create-dev:
```powershell
docker compose exec create-dev composer install
```

Notes and recommendations

Added seperate production and dev services

**Development stack:**
```powershell
docker compose up --build create-dev db-dev web
# Visit: http://localhost:8080
```

**Production stack (local testing):**
```powershell
# Build production image
docker compose build create-prodm

# Start production services with web-prod on port 8081
docker compose up create-prodm db-prodm web-prod
docker compose --env-file .env.prod up --build create-prodm db-prodm web-prod

# Visit: http://localhost:8081
```

**Using custom .env for production:**
```powershell
docker compose --env-file .env.prod up --build create-prodm db-prodm web-prod
```

- The `Dockerfile` is development-oriented and mounts the project directory so changes are reflected immediately.
- Use `Dockerfile.prod` to build production images (it installs only production deps and caches Composer layers).
- `web_prod` service runs on port 8081 and connects to `create-prodm` (production PHP service) for full-stack testing.
- For production, harden credentials, disable display_errors, and tune PHP-FPM/Nginx.

License

This project is available under the Apache-2.0 license. See `LICENSE-APACHE-2.0` for the full text.
