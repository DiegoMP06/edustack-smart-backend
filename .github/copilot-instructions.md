# GitHub Copilot Instructions

## Project: EduStack Smart Backend

A modular Laravel 13 application with Inertia.js v2 + React 19 frontend.

## Tech Stack
- PHP 8.5, Laravel 13
- Inertia.js v2, React 19, TailwindCSS v4
- Pest v4 (testing), Laravel Fortify (auth), Scout (search), Reverb (WebSockets)
- Spatie: laravel-query-builder, laravel-medialibrary, laravel-permission

## Modular Architecture (STRICT)

Every module in `app/Modules/<Module>/` follows this structure:

```
app/Modules/<Module>/
├── Domain/Contracts/       # Interfaces
├── Domain/Policies/        # Authorization
├── Domain/Rules/           # Validation rules
├── Application/DTOs/       # Data Transfer Objects
├── Application/UseCases/Command/  # Write operations
├── Application/UseCases/Query/    # Read operations
├── Application/Support/    # Mappers, Transformers
├── Infrastructure/Repositories/   # Eloquent implementations
├── Infrastructure/Queries/Options/ # QueryBuilder config
├── Infrastructure/Analytics/      # External integrations
├── Http/Controllers/       # Thin controllers
├── Http/Requests/          # FormRequests
├── Http/Resources/         # API Resources
├── Providers/              # DI bindings, policies, routes
└── routes/                 # Route files
```

### Rules
1. NO Services layer — use UseCases
2. Thin controllers — validate, authorize, delegate
3. Repository pattern with contracts
4. DI via ModuleServiceProvider
5. Spatie QueryBuilder encapsulated with Options classes
6. DTOs in Application/DTOs
7. Architecture tests required per module

## Conventions
- PHP 8 constructor property promotion, explicit return types
- Form Requests for validation
- Eloquent relationships, prevent N+1
- Pest for testing
- Run `vendor/bin/pint` after PHP changes
