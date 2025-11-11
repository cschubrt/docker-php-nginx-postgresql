# docker-php (PHP 8.3 + Nginx + PostgreSQL)

This workspace contains a minimal Docker-based setup to run a PHP 8.3 application with Nginx and PostgreSQL.

Files added:
- `Dockerfile` — PHP 8.3 FPM image with pdo_pgsql and composer
- `docker-compose.yml` — app, web, db
- `docker/nginx/default.conf` — Nginx config forwarding PHP to php-fpm
- `php.ini` — basic dev PHP config
- `docker/initdb/init.sql` — sample DB init script
- `.dockerignore` — excludes vendor/node_modules/.git from context

Quick start (PowerShell):
docker compose up --build

# Stop and remove containers (and network):
docker compose down

Defaults:
- PostgreSQL: user `appuser`, password `secret`, database `appdb` (see `docker-compose.yml`).
- Nginx exposed on port 8080 on the host.

http://localhost:8080

# To go inside PHP container
docker-compose exec php bash


For production you should harden passwords, disable display_errors, tune PHP-FPM, and serve static assets efficiently.
