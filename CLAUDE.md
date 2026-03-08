# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

---

## Workflow Orchestration

### 1. Plan Mode Default
- Enter plan mode for ANY non-trivial task (3+ steps or architectural decisions)
- If something goes sideways, STOP and re-plan immediately – don't keep pushing
- Use plan mode for verification steps, not just building
- Write detailed specs upfront to reduce ambiguity

### 2. Subagent Strategy
- Use subagents liberally to keep main context window clean
- Offload research, exploration, and parallel analysis to subagents
- For complex problems, throw more compute at it via subagents
- One task per subagent for focused execution

### 3. Self-Improvement Loop
- After ANY correction from the user: update `tasks/lessons.md` with the pattern
- Write rules for yourself that prevent the same mistake
- Ruthlessly iterate on these lessons until mistake rate drops
- Review lessons at session start for relevant project

### 4. Verification Before Done
- Never mark a task complete without proving it works
- Diff behavior between main and your changes when relevant
- Ask yourself: "Would a staff engineer approve this?"
- Run tests, check logs, demonstrate correctness

### 5. Demand Elegance (Balanced)
- For non-trivial changes: pause and ask "is there a more elegant way?"
- If a fix feels hacky: "Knowing everything I know now, implement the elegant solution"
- Skip this for simple, obvious fixes – don't over-engineer
- Challenge your own work before presenting it

### 6. Autonomous Bug Fixing
- When given a bug report: just fix it. Don't ask for hand-holding
- Point at logs, errors, failing tests – then resolve them
- Zero context switching required from the user
- Go fix failing CI tests without being told how

## Task Management

1. **Plan First**: Write plan to `tasks/todo.md` with checkable items
2. **Verify Plan**: Check in before starting implementation
3. **Track Progress**: Mark items complete as you go
4. **Explain Changes**: High-level summary at each step
5. **Document Results**: Add review section to `tasks/todo.md`
6. **Capture Lessons**: Update `tasks/lessons.md` after corrections

## Core Principles

- **Simplicity First**: Make every change as simple as possible. Impact minimal code.
- **No Laziness**: Find root causes. No temporary fixes. Senior developer standards.
- **Minimal Impact**: Changes should only touch what's necessary. Avoid introducing bugs.

---

## Module Development Workflow (Spec-First)

### Core Rule
Before writing any code for a new module, the OpenAPI contract MUST be defined.
**No subagent starts implementation without an approved spec.**

### Phase 1 — Contract Design
1. Create `/docs/specs/{module}.yaml` with all endpoints for the module
2. Include: routes, HTTP methods, request body, responses, error codes
3. Review the spec before proceeding to Phase 2

### Phase 2 — Parallel Implementation (with subagents)
Once the spec is approved:
- **Backend subagent**: implements Laravel endpoints following `/docs/specs/{module}.yaml`
  - Apply thin-controller → service pattern
  - Apply `BelongsToTenant` on new models
- **Frontend subagent**: builds composables and pages in Nuxt
  - Read the spec — never assume response shapes
  - Use generated types: `npx openapi-typescript docs/specs/{module}.yaml -o frontend/types/{module}.d.ts`
  - Use spec mocks while backend is not ready

### Phase 3 — Integration
- Replace mocks with real endpoints
- Verify real responses match the spec
- Run Backend and Frontend verification checklists

### Forbidden Anti-patterns
- ❌ Never create TypeScript types manually if the spec exists
- ❌ Never work in parallel without a previously approved spec
- ❌ Never assume endpoint structure — always read `/docs/specs/`

---

## Project Overview

**Aula360** is an academic management platform for Colombian educational institutions. It manages students, teachers, grades, attendance, report cards, and the Colombian SIEE (Sistema Institucional de Evaluación de Estudiantes).

## Stack

- **Backend**: Laravel 12 (PHP 8.2+) — API-only, serves on port 9090
- **Frontend**: Nuxt 3 + Vue 3 + TypeScript + Nuxt UI + Pinia — serves on port 3000
- **Auth**: Laravel Sanctum (Bearer tokens, stored in localStorage)
- **PDF generation**: barryvdh/laravel-dompdf
- **DB**: SQLite (dev) / PostgreSQL (production via Docker Sail)

## Repository Structure

```
/                    # Laravel 12 backend (REST API)
/frontend/           # Nuxt 3 + Vue 3 SPA (dashboard app)
/tasks/              # Task tracking and lessons learned
  todo.md            # Current task plan with checkable items
  lessons.md         # Accumulated patterns and corrections
```

## Development Commands

### Run everything together (recommended)
```bash
composer run dev
# Starts: Laravel (9090), queue worker, Pail log viewer, and Nuxt dev server concurrently
```

### Backend only
```bash
sail artisan serve --port=9090
```

### Frontend only
```bash
cd frontend && npm run dev
```

### Setup from scratch
```bash
composer run setup
```

### Tests
```bash
composer run test                    # all tests
sail artisan test --filter=TestName   # single test
```

### Lint (Laravel Pint)
```bash
./vendor/bin/pint           # fix PHP code style
./vendor/bin/pint --test    # check without fixing
```

### Database
```bash
sail artisan migrate
sail artisan db:seed         # seeds with SchoolSeeder (demo data)
sail artisan migrate:fresh --seed   # reset and reseed
```

---

## Architecture

### Multi-Tenancy

All protected routes use the `tenant` middleware (`TenantMiddleware`). It resolves the current `Institution` from the authenticated user and stores it in `TenantService` (static, request-scoped). Most Eloquent models use the `BelongsToTenant` trait (`app/Models/Traits/BelongsToTenant.php`), which:
- Automatically scopes all queries to `institution_id` of the current tenant
- Automatically sets `institution_id` on creation
- Use `withoutGlobalScope('tenant')` or `->withoutTenant()` when cross-tenant queries are needed

> ⚠️ **Critical Rule:** Never bypass tenant scoping without explicit justification. Every new model with institution data MUST use `BelongsToTenant`. Violations are security bugs — students from one institution must NEVER appear in another.

### API Layer
- All routes are in `routes/api.php` under the `auth:sanctum` + `tenant` middleware group
- Auth routes use `->withoutMiddleware('tenant')` since they don't need institution context
- Controllers follow the pattern: thin controllers delegating logic to Services when complex

### Key Services
- `TenantService` — static service managing per-request institution context
- `GradeCalculatorService` — weighted grade averages, ranking, performance levels
- `ReportCardPdfService` — PDF generation for boletines

### Grade Scale (Colombian)
| Range | Level |
|-------|-------|
| 1.0–2.9 | Bajo (failing) |
| 3.0–3.9 | Básico |
| 4.0–4.5 | Alto |
| 4.6–5.0 | Superior |

Passing grade: **3.0**. See `app/Enums/PerformanceLevel.php`.

### Frontend Structure
- **`composables/useApi.ts`** — central HTTP client; injects Bearer token from auth store, handles 401 redirects
- **`stores/auth.ts`** — Pinia store; persists token/user in localStorage, provides role getters (`isAdmin`, `isTeacher`, `isGuardian`, etc.)
- **`stores/academic.ts`**, **`stores/institution.ts`** — domain state stores
- **`composables/use*.ts`** — domain composables wrapping API calls (useGrades, useAttendance, useAcademic, useReports, useSiee)
- **`types/school.ts`** — all shared TypeScript types

### User Roles
`admin` | `coordinator` | `teacher` | `guardian`

The `guardian` role has a restricted portal (`/guardian/*` routes in `PortalController`). Staff roles are `admin`, `coordinator`, `teacher`.

### Nuxt Proxy
The frontend proxies `/api/**` → `http://localhost:9090/api/**` via `routeRules` in `nuxt.config.ts`, so both services share the same origin in development.

---

## Verification Checklists

### Backend — Before marking any task complete:
1. Run `composer run test` — all tests must pass
2. Run `./vendor/bin/pint --test` — no lint errors
3. Verify multi-tenancy: confirm `institution_id` scoping works correctly
4. Check guardian isolation: guardians must only see their own children's data
5. Validate grade calculations against the Colombian scale (3.0 passing threshold)
6. Ensure API responses follow thin-controller → service delegation pattern

### Frontend — Before marking any task complete:
1. Run `npm run lint` from `/frontend/` — no lint errors
2. Run `npm run typecheck` from `/frontend/` — no TypeScript errors
3. Verify the feature works for all relevant roles: admin, coordinator, teacher, guardian
4. Check responsive behavior if UI was modified
5. Confirm `useApi` composable is used (never raw `fetch` / `axios`)
6. Verify guardian portal restrictions are not bypassed

---

## Test Credentials (from seeder)
| Role | Email | Password |
|------|-------|----------|
| Admin | admin@aula360.com | password |
| Coordinator | coordinator@aula360.com | password |
| Teacher | teacher@aula360.com | password |
| Guardian | guardian@aula360.com | password |

---

## Common Pitfalls (Lessons Baseline)

- **Never** create a model with institution data without `BelongsToTenant` trait
- **Never** use raw `fetch` or `axios` in the frontend — always use `useApi`
- **Never** hardcode `institution_id` — it comes from `TenantService` via the authenticated user
- **Never** bypass the `tenant` middleware on protected routes without explicit justification
- **Never** assume grade thresholds — always reference `PerformanceLevel` enum (3.0 = passing)
- **Always** delegate complex logic to Services — controllers stay thin
- **Always** validate that guardian users can only access their own children's data
- **Always** run both `pint --test` and `composer run test` before considering backend work done
- **Always** run both `npm run lint` and `npm run typecheck` before considering frontend work done
- **Always** test with all 4 roles (admin, coordinator, teacher, guardian) when touching permissions