# AGENTS.md

## Project

Laravel 13 (PHP 8.3) Vue Starter Kit — an SSE (Server-Sent Events) learning project. Currently at baseline: stock Inertia v3 + Vue 3 app with Fortify auth, no SSE code yet.

## Commands

- `composer setup` — full bootstrap: install, .env, migrate, build
- `composer dev` — runs server + queue + vite concurrently
- `composer test` — full pipeline: config:clear → pint check → phpstan → pest
- `composer ci:check` — CI parity: eslint + prettier + vue-tsc + phpstan + pest
- `composer lint` / `lint:check` — Pint fix / check
- `composer types:check` — phpstan (level 7)
- `npm run types:check` — vue-tsc
- `vendor/bin/pint --dirty --format agent` — run after any PHP edit
- `php artisan test --compact --filter=Name` — single test

## Stack

- Inertia v3 + Vue 3, pages in `resources/js/pages/` (lowercase), `@/` → `resources/js/`
- Tailwind v4 (CSS-based config, no `tailwind.config.js`)
- Wayfinder for typed routes — import from `@/actions/` / `@/routes/`
- shadcn-vue components (reka-ui, lucide icons)
- Fortify for auth (routes/controllers auto-registered in `app/Actions/Fortify/`)
- Demo accounts (password `password`) seeded per role (`admin|operator|penjaga|monitor`@antrian.test); login page auto-fills via `demoAccounts` prop from `FortifyServiceProvider`
- Service Categories CRUD (admin only) — `admin/service-categories` with CRUD, table, `ServiceCategoryController`
- Services CRUD (admin only) — `admin/services` with CRUD, table, `ServiceController` (prefix, digit, reset, category relation)
- Counters CRUD (admin only) — `admin/counters` with CRUD, table, `CounterController` (name, service, status)
- Display Monitors CRUD (admin only) — `admin/display-monitors` with CRUD, table, `DisplayMonitorController` (name, location, mode, many-to-many services via `display_monitor_service` pivot)
- Pest for tests, Larastan (level 7), Pint (laravel preset)
- DB: SQLite default; tests use `sqlite :memory:` (phpunit.xml)

## PHP conventions

- Curly braces always, even single-line bodies
- Constructor property promotion; no empty zero-param constructors
- Explicit param/return types
- TitleCase enum keys; array-shape PHPDoc for array types

## Testing

- New tests: `php artisan make:test --pest Name` (no suite dir in name)
- Use factories before manual model setup
- Never delete tests without approval

## Gotchas

- Frontend changes need `npm run build` / `composer dev` to appear
- Always pass `--no-interaction` to artisan commands
- New models: create factory + seeder alongside
- `Inertia::lazy()` is gone in v3 — use `Inertia::optional()` / `defer()`