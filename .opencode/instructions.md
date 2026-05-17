# OpenCode Instructions: EduStack Smart Backend

## Stack
- PHP 8.5, Laravel 13
- Inertia.js v2 + React 19, TailwindCSS v4
- Pest v4, Fortify, Scout, Reverb, Sanctum
- Spatie: query-builder, medialibrary, permission

## Modular Architecture (STRICT)

All modules under `app/Modules/<Module>/`:

```
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
├── Http/Controllers/       # Thin: validate, authorize, delegate
├── Http/Requests/          # FormRequest classes
├── Http/Resources/         # API Resources
├── Providers/              # ModuleServiceProvider
└── routes/                 # web.php, api.php, features.php
```

### Core Rules
1. NO Services layer — Use UseCases/Command and UseCases/Query
2. Controllers are thin — validate, authorize, call UseCase
3. Repositories with contracts in Domain/, implementations in Infrastructure/
4. DI via ModuleServiceProvider — bind all contracts
5. Spatie QueryBuilder encapsulated — Options classes for config
6. DTOs in Application/DTOs
7. Architecture tests mandatory — tests/Unit/Architecture/<Module>ArchitectureTest.php

## Conventions
- PHP 8 constructor property promotion
- Explicit return types
- Curly braces for all control structures
- Form Requests for validation
- Eloquent relationships, prevent N+1
- config('key') not env() outside config
- Pest for testing
- Run vendor/bin/pint after PHP changes
