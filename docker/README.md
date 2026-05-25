# Docker — Construction Marketplace

> **Full guide (what was built + how to use):** see [DOCKER.md](../DOCKER.md) at the repository root.

Production-ready Docker Compose stack for:

- **construction-marketplace-api** — Laravel 11 API + Filament admin (`/admin`)
- **construction-marketplace-web** — Next.js frontend

## Architecture

| Service        | Role                                      | Internal host | Host ports (default) |
| -------------- | ----------------------------------------- | ------------- | -------------------- |
| `mysql`        | Database                                  | `mysql`       | 3306 (dev only)      |
| `redis`        | Cache, sessions, queues                   | `redis`       | 6379 (dev only)      |
| `backend`      | PHP 8.3-FPM (Laravel)                     | `backend`     | —                    |
| `nginx`        | Reverse proxy                             | `nginx`       | 80, **8000**         |
| `frontend`     | Next.js                                   | `frontend`    | **3000**             |
| `queue-worker` | Supervisor → `queue:work redis`           | —             | —                    |
| `scheduler`    | `schedule:run` every 60s                  | —             | —                    |

- **API / Filament:** http://localhost:8000 (API prefix `/api`, admin `/admin`)
- **Frontend (direct):** http://localhost:3000
- **Unified (via Nginx):** http://localhost:80 → Next.js + Laravel routes

## Quick start (development)

### 1. Environment files

```bash
# Repository root (Compose variables)
cp .env.docker.example .env

# Laravel API
cp construction-marketplace-api/.env.docker.example construction-marketplace-api/.env

# Generate application key
docker compose -f docker-compose.yml -f docker-compose.dev.yml run --rm backend php artisan key:generate
```

Edit passwords in `.env` and `construction-marketplace-api/.env`. Set the same `MYSQL_*` / `DB_*` values.

### 2. Build and run

```bash
docker compose -f docker-compose.yml -f docker-compose.dev.yml up --build
```

First start installs Composer/npm dependencies inside containers (may take several minutes).

### 3. Migrate database

```bash
docker compose -f docker-compose.yml -f docker-compose.dev.yml exec backend php artisan migrate
```

### 4. Filament admin user

```bash
docker compose -f docker-compose.yml -f docker-compose.dev.yml exec backend php artisan make:filament-user
```

Open http://localhost:8000/admin

## Environment profiles

| Profile      | Command                                                                 |
| ------------ | ----------------------------------------------------------------------- |
| Development  | `docker compose -f docker-compose.yml -f docker-compose.dev.yml up`     |
| Staging      | `docker compose -f docker-compose.yml -f docker-compose.staging.yml up -d --build` |
| Production   | `docker compose -f docker-compose.yml -f docker-compose.prod.yml up -d --build`    |

## Build commands

```bash
# All services (development)
docker compose -f docker-compose.yml -f docker-compose.dev.yml build

# Production images only
docker compose -f docker-compose.yml -f docker-compose.prod.yml build

# Individual image
docker build -f docker/backend/Dockerfile --target production -t construction-backend .
docker build -f docker/frontend/Dockerfile -t construction-frontend .
docker build -f docker/nginx/Dockerfile -t construction-nginx .
```

## Artisan commands

```bash
COMPOSE="docker compose -f docker-compose.yml -f docker-compose.dev.yml"

# Migrations
$COMPOSE exec backend php artisan migrate
$COMPOSE exec backend php artisan migrate --force

# Seed (staging only)
$COMPOSE exec backend php artisan db:seed

# Storage symlink (also runs in entrypoint)
$COMPOSE exec backend php artisan storage:link

# Queue restart after deploy
$COMPOSE exec backend php artisan queue:restart

# Cache / optimize (production deploy)
$COMPOSE exec backend php artisan optimize:clear
$COMPOSE exec backend php artisan optimize

# Filament
$COMPOSE exec backend php artisan make:filament-user
$COMPOSE exec backend php artisan filament:assets
```

## Queue workers

- **Production/staging:** `queue-worker` service runs Supervisor with 2× `queue:work redis` processes.
- **Logs:** `construction-marketplace-api/storage/logs/worker.log` (inside volume / bind mount).

```bash
# Manual one-off worker (debugging)
docker compose exec backend php artisan queue:work redis --tries=3

# Restart workers after code deploy
docker compose exec backend php artisan queue:restart
```

## Scheduler

The `scheduler` container runs `php artisan schedule:run` every 60 seconds. Add real schedules in `bootstrap/app.php` → `->withSchedule()`.

## Next.js ↔ Laravel API

| Variable              | Where        | Example (local)                |
| --------------------- | ------------ | ------------------------------ |
| `NEXT_PUBLIC_API_URL` | Browser/axios | `http://localhost:8000/api` |

Configured in root `.env` and baked into the frontend production build. Must be reachable from the **user's browser**, not internal Docker DNS.

## Persistent volumes

| Volume             | Purpose                    |
| ------------------ | -------------------------- |
| `mysql_data`       | MySQL data files           |
| `redis_data`       | Redis AOF/RDB (prod)       |
| `backend_storage`  | `storage/app` (uploads)    |

Backup `mysql_data` and `backend_storage` before major upgrades.

## SSL (staging / production)

1. Place certificates in `docker/nginx/certs/` (`fullchain.pem`, `privkey.pem`).
2. Uncomment server blocks in `docker/nginx/conf.d/ssl.conf`.
3. Mount certs via `nginx_certs` volume (see `docker-compose.staging.yml`).
4. Expose port `443` (`NGINX_HTTPS_PORT`).

Prefer terminating TLS at a cloud load balancer (ALB, Cloudflare) when possible.

## Security recommendations

- Never commit `.env` files; use `.env.docker.example` as templates only.
- Use strong `MYSQL_PASSWORD` and `REDIS_PASSWORD` in staging/production.
- Set `APP_DEBUG=false` outside local development.
- Do not publish MySQL/Redis ports in production (`docker-compose.prod.yml` clears them).
- Restrict Filament `/admin` via VPN, IP allowlist, or SSO in production.
- Store secrets in GitHub Actions / your orchestrator, not Docker build-args.
- Scan images with Trivy (see `.github/workflows/docker.yml`).
- Run `php artisan config:cache` only after environment variables are final on the server.

## Deployment best practices

1. Build images in CI with immutable tags (`git sha` or `v1.2.3`).
2. Push to GHCR (workflow included).
3. On the server: `docker compose pull && docker compose up -d`.
4. Run migrations explicitly or set `RUN_MIGRATIONS=true` for one deploy.
5. Run `php artisan queue:restart` after backend/worker image updates.
6. Use `optimize` in production after env is stable.
7. Keep staging compose identical to production with anonymized data.

## CI/CD (GitHub Actions)

Workflow: [`.github/workflows/docker.yml`](../.github/workflows/docker.yml)

- Builds backend, worker, frontend, nginx images on push/PR.
- Pushes to `ghcr.io/<owner>/<repo>/...` on branch pushes.
- Trivy scan on backend image (CRITICAL/HIGH).

Configure repository variable `NEXT_PUBLIC_API_URL` for staging/production frontend builds.

## Folder structure

```
construction/
├── construction-marketplace-api/
├── construction-marketplace-web/
├── docker/
│   ├── backend/          # PHP Dockerfiles, entrypoint, php.ini
│   ├── frontend/         # Node Dockerfiles
│   ├── nginx/            # Reverse proxy configs + certs/
│   ├── supervisor/       # Queue worker programs
│   ├── scheduler/        # schedule:run loop script
│   └── mysql/init/       # Optional SQL seed on first boot
├── docker-compose.yml
├── docker-compose.dev.yml
├── docker-compose.staging.yml
├── docker-compose.prod.yml
└── .env.docker.example
```

## Troubleshooting

| Issue | Fix |
| ----- | --- |
| `MySQL not ready` | Wait for healthcheck; verify `DB_*` matches `MYSQL_*`. |
| 502 from Nginx | Ensure `backend` php-fpm is running: `docker compose ps`. |
| Frontend cannot reach API | Use `http://localhost:8000/api`, not `http://backend:8000`. |
| Permission errors on `storage/` | `docker compose exec backend chown -R www-data:www-data storage bootstrap/cache` |
| Windows file watch lag | `WATCHPACK_POLLING=true` is set in dev compose. |

## Stop and clean up

```bash
docker compose -f docker-compose.yml -f docker-compose.dev.yml down

# Remove volumes (destroys database data)
docker compose -f docker-compose.yml -f docker-compose.dev.yml down -v
```
