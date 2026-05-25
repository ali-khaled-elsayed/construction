# Construction Marketplace

Monorepo for the Construction Marketplace platform.

| Project | Path | Stack |
|---------|------|-------|
| API + Admin | [construction-marketplace-api/](construction-marketplace-api/) | Laravel 11, PHP 8.3, Filament |
| Web app | [construction-marketplace-web/](construction-marketplace-web/) | Next.js 16, React 19 |

## Run with Docker (recommended)

See **[DOCKER.md](DOCKER.md)** for:

- What was added to the repo (services, files, architecture)
- First-time setup step by step
- Development, staging, and production commands
- How the frontend connects to the API

**Quick start:**

```bash
cp .env.docker.example .env
cp construction-marketplace-api/.env.docker.example construction-marketplace-api/.env
docker compose -f docker-compose.yml -f docker-compose.dev.yml up --build
```

Operational cheat sheet: [docker/README.md](docker/README.md).

## Run without Docker

- API: see [construction-marketplace-api/README.md](construction-marketplace-api/README.md)
- Web: `cd construction-marketplace-web && npm install && npm run dev`
