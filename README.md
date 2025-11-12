# docker-php (PHP 8.3 + Nginx + PostgreSQL)
[![CI](https://github.com/cschubrt/docker-php-nginx-postgresql/actions/workflows/ci.yml/badge.svg)](https://github.com/cschubrt/docker-php-nginx-postgresql/actions/workflows/ci.yml)

This workspace contains a minimal Docker-based setup to run a PHP 8.3 application with Nginx and PostgreSQL.

Files included:
- `Dockerfile` — PHP 8.3 FPM image with pdo_pgsql and composer (development-friendly)
- `Dockerfile.prod` — production-optimized multi-stage image that caches Composer deps
- `docker-compose.yml` — app, web, db services
- `docker/nginx/default.conf` — Nginx config forwarding PHP to php-fpm
- `php.ini` — basic dev PHP config
- `docker/initdb/init.sql` — sample DB init script
- `.dockerignore` — excludes vendor/node_modules/.git from context
- `composer.json` — project file (adds phpunit/phpunit as dev dependency)
- `src/` and `tests/` — sample Database class and PHPUnit test

Quick start (PowerShell):

```powershell
# Build and start services (foreground):
docker compose up --build

# Or run in background:
docker compose up --build -d

# Stop and remove containers (and network):
docker compose down
```

Local web address
- http://localhost:8080

Example page
- The project includes a simple example at `public/index.php` that lists rows from the `users` table (seeded by `docker/initdb/init.sql`).


PostgreSQL defaults (see `docker-compose.yml`):
- DB: `appdb`
- User: `appuser`
- Password: `secret`

Running Composer and tests

When working locally with the code mounted into the container you may need to install Composer dependencies. You can do that inside the `app` service:

```powershell
docker compose run --rm app composer install
```

Run PHPUnit (inside the container):

```powershell
docker compose run --rm app vendor\bin\phpunit --colors=always
```

open an interactive psql session inside the db container
```powershell
docker compose exec db psql -U appuser -d appdb
```

open an interactive shell session inside the container
```powershell
docker compose exec app bash

# Start a new bash session (doesn't require running service)
docker compose run --rm app bash

# list files in app container
docker compose exec app ls -la /var/www/html

# check PHP version in app
docker compose exec app php -v

# run Composer in app
docker compose exec app composer install
```

Inside app container:
cd /var/www/html          # Go to project root
ls -la                    # List files
php -v                    # Check PHP version
composer install          # Install dependencies
vendor/bin/phpunit        # Run tests
tail -f /var/log/nginx/access.log   # View Nginx logs (if you exec into web)
tail -f /var/log/php-fpm/error.log

Inside db container:
psql -U appuser -d appdb  # Already in psql, but can reconnect
\dt                       # List tables
SELECT * FROM users;      # Run SQL query
\q                        # Exit psql

Notes and recommendations

- The `Dockerfile` is development-oriented and mounts the project directory so changes are reflected immediately.
- Use `Dockerfile.prod` to build production images (it installs only production deps and caches Composer layers).
- For production, harden credentials, disable display_errors, and tune PHP-FPM/Nginx.

License

This project is available under a license: Apache-2.0. See `LICENSE-APACHE-2.0` for the full text.