# APP TEMPLATE - Laravel + Nuxt

## Project Overview

This is a full-stack template for building modern web applications using Laravel as the backend/API and Nuxt as the frontend SPA.

### Tech Stack
- **Backend:** Laravel 12 + PHP 8.2 + PostgreSQL
- **Frontend:** Nuxt 4 + Vue 3 + TypeScript + Nuxt UI + Pinia
- **Authentication:** Laravel Sanctum with 2FA support
- **Architecture:** REST API + SPA
- **DevOps:** Laravel Sail (Docker)

### Project Structure
```
appTemplate/
├── app/                          # Laravel app
│   ├── Http/
│   │   └── Controllers/Api/      # API Controllers
│   └── Models/                   # Eloquent Models
├── database/
│   ├── migrations/               # Database migrations
│   └── seeders/                  # Database seeders
├── routes/api.php                # API routes
├── compose.yaml                  # Docker Compose (Sail)
│
└── frontend/                     # Nuxt 4
    └── app/
        ├── components/           # Vue components
        ├── composables/          # Composables (useApi, etc.)
        ├── layouts/              # App layouts
        ├── pages/                # Pages/routes
        ├── stores/               # Pinia stores
        └── middleware/           # Route middleware
```

---

## Quick Start with Sail

### Prerequisites
- Docker Desktop installed and running

### Backend Setup (Sail)
```bash
# Copy environment file
cp .env.example .env

# Install Composer dependencies (using Docker)
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php82-composer:latest \
    composer install --ignore-platform-reqs

# Start Sail containers
./vendor/bin/sail up -d

# Install Laravel Boost (dev tools)
./vendor/bin/sail composer require laravel/boost --dev

# Generate app key
./vendor/bin/sail artisan key:generate

# Run migrations and seeders
./vendor/bin/sail artisan migrate --seed
```

### Frontend Setup
```bash
cd frontend

# Install dependencies
pnpm install

# Copy environment file
cp .env.example .env

# Start dev server
pnpm dev
```

---

## Sail Commands

```bash
# Start containers
./vendor/bin/sail up -d

# Stop containers
./vendor/bin/sail down

# Run artisan commands
./vendor/bin/sail artisan <command>

# Run composer
./vendor/bin/sail composer <command>

# Access PostgreSQL
./vendor/bin/sail psql

# View logs
./vendor/bin/sail logs
```

### Sail Alias (recommended)
Add to your `~/.bashrc` or `~/.zshrc`:
```bash
alias sail='./vendor/bin/sail'
```

Then use:
```bash
sail up -d
sail artisan migrate
sail down
```

---

## Ports

| Service    | Port |
|------------|------|
| Laravel API| 9090 |
| PostgreSQL | 5432 |
| Frontend   | 3000 |

---

## Default Credentials
- **Email:** admin@example.com
- **Password:** password

---

## API Endpoints

### Public
- `GET /api/health` - Health check
- `POST /api/auth/login` - Login
- `POST /api/auth/forgot-password` - Password reset

### Protected (requires Bearer token)
- `POST /api/auth/logout` - Logout
- `GET /api/auth/me` - Current user
- `PUT /api/auth/profile` - Update profile
- `PUT /api/auth/password` - Change password
