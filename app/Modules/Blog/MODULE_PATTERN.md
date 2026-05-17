# Patrón de Módulo — EduStack Smart v3.0

Este documento define la arquitectura estándar que **todos los módulos** deben seguir.
El módulo Blog es la implementación de referencia.

---

## Estructura de carpetas

```
Modules/
└── {Module}/
    ├── Application/
    │   ├── DTOs/                        # Spatie Laravel Data
    │   ├── Support/                     # {Model}DataMapper.php
    │   └── UseCases/
    │       ├── Command/                 # Mutaciones (Create, Update, Delete...)
    │       └── Query/                   # Lecturas (List, Show, GetOptions...)
    ├── Domain/
    │   ├── Contracts/                   # Interfaces (repositorios, servicios)
    │   └── Policies/                    # Laravel Policies
    ├── Http/
    │   ├── Controllers/
    │   ├── Requests/                    # Form Requests separados
    │   └── Resources/                   # API Resources / Collections
    ├── Infrastructure/
    │   ├── Analytics/                   # Implementaciones de contadores, trackers
    │   ├── Queries/Options/             # Implementaciones de QueryOptionsContract
    │   └── Repositories/               # Implementaciones Eloquent de contratos
    ├── Providers/
    │   └── {Module}Provider.php         # Módulo: binds + policies + routes
    └── routes/
        ├── web.php
        ├── web.features.php             # Rutas secundarias (sub-recursos)
        └── api.php
```

---

## Reglas de arquitectura

### 1. Flujo de dependencias
```
Http → Application → Domain ← Infrastructure
```
- **Http** solo conoce Application.
- **Application** solo conoce Domain (contratos).
- **Infrastructure** implementa Domain.
- **Domain no importa nada de Http** (sin `Request`, sin `Response`).

### 2. Controllers
- Delegan a un Use Case por método.
- Autorizan con `$this->authorize()` antes del use case.
- Usan **siempre** el `Request $request` inyectado, nunca el helper `request()`.
- Extraen primitivos del Request antes de pasarlos al use case
  (ej: `$request->ip()`, `$request->userAgent()`).

### 3. Use Cases
- **Command** → recibe DTO, no retorna datos de negocio (solo el modelo creado/actualizado si la capa lo necesita).
- **Query** → recibe parámetros primitivos o DTOs, retorna datos serializados (PostData, etc.).
- **Sin dependencias HTTP** — ni `Request` ni `Response`.

### 4. Domain Contracts (interfaces)
- Definen **qué** se puede hacer, sin saber **cómo**.
- **Sin tipos del framework** — no `Request`, no `Collection` de Eloquent si puede evitarse.
- `PostViewCounter` recibe `string $ip, string $userAgent`, no `Request`.

### 5. DTOs — Spatie Laravel Data
- Uno por recurso principal: `{Model}Data`.
- Lazy loading para relaciones: `Lazy::create(fn() => ...)`.
- `{Model}DataMapper` centraliza todos los contextos de serialización.
- Nunca llamar `PostData::from($model)` directo en controllers — usar el Mapper.

### 6. Infrastructure
- Implementa contratos del Domain.
- `EloquentPost{Read|Write}Repository` separan lecturas de escrituras.
- `{Model}IndexQueryOptions` implementa `QueryOptionsContract` (type-safe).
- **Escrituras** usan `DB::transaction`.
- **Lecturas** usan `QueryBuilder` de Spatie.

### 7. Provider como Módulo
- Registra todos los binds `Contrato → Implementación`.
- Registra Policies con `Gate::policy()`.
- Carga los 3 archivos de rutas con `$this->loadRoutesFrom()`.
- **No usar `require` ni `file_exists()` en los archivos de rutas**.

---

## Contratos base reutilizables (Shared)

Estos contratos viven en `Modules/Shared` y se reutilizan en todos los módulos:

| Contrato | Descripción |
|---|---|
| `QueryOptionsContract` | Filters, includes y sorts para el index |
| `ListCollectionQueryParamsData` | Parámetros estándar de paginación/búsqueda |
| `ModelContentFormData` | DTO genérico para editores de contenido |
| `GlobalScoutFilter` | Filtro de búsqueda con Scout/MeiliSearch |

---

## Checklist al crear un módulo nuevo

- [ ] `{Model}Data.php` con `fromModel()` y Lazy relations
- [ ] `{Model}DataMapper.php` con todos los contextos necesarios
- [ ] Contrato `{Model}ReadRepository` con métodos de lectura
- [ ] Contrato `{Model}WriteRepository` con métodos de escritura
- [ ] `EloquentPost{Read|Write}Repository` implementando los contratos
- [ ] `{Model}IndexQueryOptions` implementando `QueryOptionsContract`
- [ ] Un Use Case por acción (no un Service God Object)
- [ ] Form Requests separados del Controller
- [ ] Policy en `Domain/Policies/` registrada en el Provider
- [ ] Provider carga rutas explícitamente (sin `require`)
- [ ] Domain contracts **sin imports de Http**
- [ ] Controllers usan `$request` inyectado (no helper global)
