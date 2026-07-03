# EduOS Foundation (Step 1)

EduOS is an AI-powered, multi-tenant, white-label Education SaaS platform.

This repository contains the production-ready Step 1 foundation only:

- Modular monolith backend using Laravel + Nwidart Modules
- API-first architecture
- Shared-database multi-tenancy using tenant isolation primitives
- React + TypeScript frontend inside the same Laravel app
- Theme and localization foundations
- Base RBAC setup with Spatie Permission

## Tech Stack

- PHP 8.3+
- Laravel 13
- MySQL 8+
- Redis
- Laravel Sanctum
- Spatie Permission
- Nwidart Laravel Modules
- Node.js LTS
- React 19 + TypeScript
- Vite
- Tailwind CSS 4

## Architecture Decisions

### 1) Modular Monolith + DDD-inspired Structure

The codebase uses modules under `Modules/`:

- `Shared`
- `Identity`
- `Tenant`
- `Institute`
- `Settings`

Each module contains the requested bounded-context-oriented folders:

- `Application/` (actions, services, DTOs, contracts)
- `Domain/` (models, enums, events, exceptions)
- `Infrastructure/` (repositories, persistence, external services)
- `Http/` (controllers, requests, resources, middleware)
- plus `Database`, `Events`, `Listeners`, `Jobs`, `Policies`, `Providers`, `Routes`

Note: Nwidart runtime autoload uses each module `app/` directory. Critical runtime classes are mirrored there, while root module folders provide the DDD foundation layout for future implementation.

### 2) Multi-Tenancy (Shared DB)

The tenancy foundation is interface-driven:

- `TenantResolverInterface`
- `TenantContextInterface`
- `StorageIsolationInterface`
- `CacheIsolationInterface`
- `QueueIsolationInterface`

Implemented foundation components:

- `ResolveTenant` middleware
- `TenantContext` request-scoped context service
- Domain resolver (`DomainTenantResolver`)
- Isolation helpers for storage/cache/queue key prefixing
- Tenant event dispatch (`TenantResolved`) for event-driven communication

### 3) Event-Driven Module Communication

`ResolveTenant` dispatches a domain event after tenant resolution.
Tenant module includes `LogTenantResolved` listener wiring and provider bindings to demonstrate module-to-module decoupling through events and contracts.

### 4) Frontend Foundation

React + TypeScript is integrated in Laravel through Vite.

Frontend structure in `resources/js`:

- `app`, `modules`, `shared`, `components`, `layouts`
- `providers`, `hooks`, `services`, `stores`
- `themes`, `locales`, `routes`

Feature modules scaffolded:

- `auth`
- `dashboard`
- `institutes`
- `settings`

### 5) Theme and Localization

Theme foundation includes:

- light + dark theme tokens
- tenant branding token overrides (primary/secondary colors)
- runtime theme switching
- local persistence for user preference

Localization foundation includes:

- English (`en`) and Hindi (`hi`) resources
- runtime language switching
- persistence for user preferred language
- RTL-ready document direction handling for future locale enablement

### 6) Security and Access Foundation

- Sanctum installed and published
- Spatie Permission installed and published
- Default roles seeded:
	- Super Admin
	- Institute Admin
	- Teacher
	- Student
	- Parent

## Database Foundation

Created tables and updates:

- `tenants`
	- `id`, `name`, `slug`, `domain`, `status`, timestamps
- `tenant_settings`
	- `id`, `tenant_id`, `theme`, `language`, `timezone`, `logo`, `favicon`, `primary_color`, `secondary_color`, timestamps
- `users` updates
	- `tenant_id`, `status`

Indexes:

- tenant IDs are indexed where introduced
- tenant settings enforces one-to-one per tenant

## Config and Tooling

Implemented:

- TypeScript config + path aliases (`tsconfig.json`)
- Vite aliases (`vite.config.js`)
- ESLint flat config (`eslint.config.js`)
- Prettier (`.prettierrc`, `.prettierignore`)
- EditorConfig (existing)
- Composer module autoload merging (`Modules/*/composer.json`)
- Module statuses/registration via Nwidart
- Strict tenant-aware repository base (`Modules/Shared/app/Infrastructure/Repositories/AbstractTenantAwareRepository.php`)
- Tenant migration helper base (`app/Support/Tenancy/Database/TenantMigration.php`)
- OpenAPI scaffold (`openapi/openapi.yaml`)

## Setup Instructions

1. Copy environment and configure infrastructure:

```bash
cp .env.example .env
```

Set in `.env`:

- `DB_CONNECTION=mysql`
- `DB_HOST=...`
- `DB_PORT=3306`
- `DB_DATABASE=eduos`
- `DB_USERNAME=...`
- `DB_PASSWORD=...`
- `REDIS_HOST=...`
- `REDIS_PASSWORD=...`
- `REDIS_PORT=6379`

2. Install dependencies:

```bash
composer install
npm install
```

3. App key + migrations + seed:

```bash
php artisan key:generate
php artisan migrate
php artisan db:seed
```

### Demo Tenants and Users

The project includes a dedicated seeder for realistic multi-tenant demo access:

- Seeder: `Database\\Seeders\\DemoUsersSeeder`
- Run only demo users/tenants seeder:

```bash
php artisan db:seed --class='Database\Seeders\DemoUsersSeeder'
```

Demo credentials:

- Shared password for all accounts: `password123`

Demo tenants:

- `north-campus-demo.edus.test` (`north-campus-demo`)
- `south-campus-demo.edus.test` (`south-campus-demo`)

Demo accounts:

| Email | Role | Tenant | Status |
| --- | --- | --- | --- |
| `admin.demo@edus.test` | Super Admin | North Campus Demo | active |
| `manager.demo@edus.test` | Institute Admin | North Campus Demo | active |
| `coordinator.demo@edus.test` | Institute Admin | North Campus Demo | active |
| `admissions.demo@edus.test` | Institute Admin | North Campus Demo | active |
| `faculty.demo@edus.test` | Teacher | North Campus Demo | active |
| `registrar.demo@edus.test` | Institute Admin | South Campus Demo | active |
| `student.demo@edus.test` | Student | South Campus Demo | active |
| `parent.demo@edus.test` | Parent | South Campus Demo | active |
| `inactive.demo@edus.test` | Student | South Campus Demo | inactive |
| `suspended.demo@edus.test` | Parent | South Campus Demo | suspended |

4. Run development stack:

```bash
composer run dev
```

Or separately:

```bash
php artisan serve
npm run dev
```

5. Quality checks:

```bash
npm run typecheck
npm run lint
php artisan test
```

## What Is Intentionally Not Implemented

This step does not include:

- authentication business flows
- student/teacher management
- courses/LMS
- AI modules
- exams/assignments
- CRM/analytics
- mobile apps

This repository is the foundational platform skeleton for future vertical implementation.

## Step 8 Remaining TODO

Step 8 hardening and production follow-up tasks are tracked in:

- `docs/STEP8_REMAINING_TODO.md`
