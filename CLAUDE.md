# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

OHSANSI is a REST API backend for managing a student science olympiad competition system (Bolivia). Built with Laravel 11.31, PHP 8.2+. All routes are versioned under `/api/v1/`.

## Common Commands

```bash
# Development (servidor + WebSocket + scheduler en paralelo)
composer dev

# Testing
composer test                                        # config:clear + artisan test
php artisan test                                     # suite completa
php artisan test tests/Feature/Auth/LoginTest.php   # un solo test

# Análisis estático
composer analizar                                    # PHPStan nivel 5 sobre app/

# Base de datos
php artisan migrate:fresh --seed                    # reset + seed completo
php artisan db:seed --seeder=RolesSeeder            # seeder específico
php artisan tinker                                  # REPL interactivo

# Workers (producción)
php artisan queue:work --queue=default              # procesar jobs de cola
php artisan reverb:start                            # servidor WebSocket
php artisan schedule:work                           # scheduler de tareas

# Comandos personalizados
php artisan ohsansi:auto-iniciar-examenes           # iniciar exámenes programados
```

## Architecture

Patrón **Controller → Service → Repository**:

- **Controllers** (`app/Http/Controllers/`) — manejadores HTTP delgados; delegan toda la lógica a Services. No deben tener `try/catch` para errores de dominio (el handler global los captura).
- **Services** (`app/Services/`) — lógica de negocio, transiciones de estado, orquestación. Lanzan excepciones de dominio (ver abajo), nunca códigos HTTP.
- **Repositories** (`app/Repositories/`) — acceso a datos sobre Eloquent.
- **Models** (`app/Model/`) — PKs personalizadas (`id_olimpiada`, `id_usuario`, etc.).
- **Requests** (`app/Http/Requests/`) — validación + autorización por permiso Spatie.
- **Exceptions** (`app/Exceptions/Dominio/`) — jerarquía de excepciones de dominio.
- **Events** (`app/Events/`) — eventos de broadcasting en tiempo real via Reverb.

## Exception Hierarchy

```
AppException (base, HTTP 400)
├── CompetenciaException   (422)
├── EvaluacionException    (409)
├── ExamenException        (422)
├── EvaluadorException     (422)
├── AutorizacionException  (403)
└── RecursoNoEncontradoException (404)
```

El handler en `bootstrap/app.php` captura `AppException` y devuelve `{ "mensaje": "..." }` con el código HTTP correspondiente. Los controllers no necesitan `try/catch` para estas excepciones.

## Key Domain Modules

| Module | Controller | Service | Notes |
|---|---|---|---|
| Olimpiadas | `OlimpiadaController` | `OlimpiadaService` | Eventos anuales; workflow de activación |
| Competencias | `CompetenciaController` | `CompetenciaService`, `CierreCompetenciaService` | Máquina de estados: Borrador → Publicada → En Proceso → Cerrada → Avalada |
| Exámenes | `ExamenController` | `ExamenService` | Ciclo de vida de exámenes por competencia |
| Evaluación | `EvaluacionController` | `EvaluacionService` | Scoring en tiempo real; semáforo rojo/verde con `lockForUpdate()` |
| Evaluadores | `EvaluadorController` | `EvaluadorService` | Asignación de jueces por área/nivel |
| Roles/Permisos | `RolAccionController` | `RolAccionService` | Permisos por fase via `configuracion_accion` |

## Key Conventions

- **Autenticación**: Sanctum token-based. Login: `POST /api/v1/auth/login` (throttle 10/min). Perfil: `GET /api/v1/auth/me`.
- **Autorización**: `auth()->id()` siempre — nunca `$request->input('user_id')`. Los FormRequests usan `$this->user()->can('CODIGO_ACCION')`.
- **Permisos Spatie**: los códigos de acción vienen de la tabla `accion_sistema` (`COMPETENCIAS`, `EXAMENES`, `SALA_EVALUACION`, `RESPONSABLES`, `EVALUADORES`, `INSCRIPCION`, `PARAMETROS`, `MEDALLERO`, `ACTIVIDADES_FASES`, `GESTIONAR_ROLES`, `CRONOGRAMA`, `REPORTES_CAMBIOS`).
- **Respuesta JSON exitosa**: `{ "mensaje": "...", "datos": {...} }`. En errores de dominio el handler devuelve `{ "mensaje": "..." }`.
- **Paginación**: todos los listados usan `paginate($porPagina)`. Query param `?por_pagina=15`.
- **Transiciones de estado**: rutas con sufijo de acción (`/publicar`, `/iniciar`, `/cerrar`, `/avalar`).
- **Auditoría**: cambios de nota en `log_cambio_nota`. Descalificaciones en `descalificacion_administrativa`.
- **N+1 prevention**: usar `Evaluacion::upsert()` para actualizaciones masivas. Cargar relaciones con `with()` antes de los loops.

## Database

- Migración base: `database/migrations/2025_10_20_update_data_base.php` (~45 tablas). **No modificar**.
- Nuevas migraciones se crean con fecha actual: `database/migrations/2026_04_16_000001_*.php`.
- Índices de performance agregados en `2026_04_16_000001_agregar_indices_performance.php`.
- Orden de seeders: FaseGlobal → Roles → RolAccion → ConfiguracionAccion → AccionSistema → datos geográficos → Usuarios.

## Production Checklist

```bash
APP_DEBUG=false
LOG_LEVEL=error
SESSION_ENCRYPT=true
QUEUE_CONNECTION=redis        # no usar sync ni database en producción
CORS_ALLOWED_ORIGINS=https://tu-dominio.com

# Workers que deben correr como servicios del sistema:
php artisan queue:work        # procesar emails, broadcasts pesados
php artisan reverb:start      # WebSockets
php artisan schedule:work     # scheduler (examenes:auto-iniciar)
```

## Code Quality

```bash
# Formateo de código (Laravel Pint)
./vendor/bin/pint

# Análisis estático (PHPStan nivel 5)
composer analizar
# equivalente: ./vendor/bin/phpstan analyse --memory-limit=1G
```

## Manual API Testing

Archivos `.http` en `/ZhttpApi/` para VS Code REST Client o JetBrains HTTP Client. Actualizar la URL base a `/api/v1/` en las peticiones.
