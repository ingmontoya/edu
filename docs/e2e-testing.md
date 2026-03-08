# E2E Testing — Aula360

Documentación de la suite de pruebas end-to-end con Playwright. Cubre la arquitectura, los archivos de prueba, las decisiones de diseño y los problemas resueltos.

---

## Archivos de prueba

| Archivo | Tests | Qué cubre |
|---------|-------|-----------|
| `e2e/higher-ed.spec.ts` | 16 | Flujo de Educación Superior (Colegio Mayor): nav, matrículas, programas, roles |
| `e2e/workflow.spec.ts` | 19 | Flujo completo K-12 + Superior: aislamiento de UI, crear docente, asignar materia, matricular estudiante |
| `e2e/public-uni-pages.spec.ts` | 7 | Páginas públicas: `/universidades`, `/universidades/pricing`, `/universidades/como-funciona` |
| `e2e/student-portal.spec.ts` | 23 (6 skipped) | Portales de estudiantes/acudientes: IES vía `/guardian`, K-12 acudiente, aislamiento de roles |

**Total: 65 tests — 59 passing, 6 skipped (Suite 3 requiere `migrate:fresh --seed`).**

---

## Ejecutar las pruebas

```bash
# Todos los tests
cd frontend && node_modules/.bin/playwright test e2e/

# Un archivo específico
node_modules/.bin/playwright test e2e/workflow.spec.ts

# Un test específico (por nombre)
node_modules/.bin/playwright test e2e/higher-ed.spec.ts --grep "Diego"

# Con reporte detallado
node_modules/.bin/playwright test e2e/ --reporter=line
```

> **Importante:** Usar siempre `node_modules/.bin/playwright`, NO `npx playwright`.
> `npx playwright` puede resolver una versión global distinta (aunque ambas reporten "1.58.2") y produce el error `test.describe() called outside context`.

---

## Helpers compartidos (`e2e/helpers.ts`)

```typescript
export const COLE_MAYOR = {
  admin: { email: 'admin@colmayor.edu.co', password: 'password' },
  coordinator: { email: 'coordinador@colmayor.edu.co', password: 'password' },
  teacher: { email: 'docente.luis@colmayor.edu.co', password: 'password' },
}

export const K12 = {
  admin: { email: 'admin@example.com', password: 'password' },
  teacher: { email: 'teacher@example.com', password: 'password' },
}
```

`login()` navega a `/login`, llena el form y espera que la URL sea `/dashboard` o `/guardian`. `logout()` limpia `localStorage` y navega a `/login`.

---

## Arquitectura de `workflow.spec.ts`

### Suite 1: Aislamiento K-12
Verifica que el admin de K-12 vea exactamente los términos K-12 (Grados, Grupos, SIEE, Convivencia) y **no** los de educación superior (Programas, Semestres, Matrículas).

### Suite 2: Aislamiento Educación Superior
Verifica que el admin de Colegio Mayor vea los términos de superior y **no** los de K-12. Incluye verificación por rol: docente y estudiante (guardian) también tienen vistas correctamente filtradas.

### Suite 3: Flujo completo (serial)
`test.describe.serial` garantiza orden y estado compartido entre tests:

| # | Test | Método |
|---|------|--------|
| 1 | Admin crea nuevo docente | UI (form `/teachers`) |
| 2 | Admin asigna materia al docente | **API directa** vía `page.evaluate` |
| 3 | Nuevo docente inicia sesión y ve su nav | UI |
| 3b | Docente navega a Registrar Notas | UI |
| 4 | Admin matricula a Isabella Moreno | **API directa** vía `page.evaluate` |
| 5 | La matrícula persiste tras recargar | UI (`/enrollments`) |
| 6 | Isabella puede iniciar sesión | UI |
| cleanup | Eliminar matrícula de Isabella | **API directa** |

### Suite 4: Verificación cruzada
Login como K-12 admin → verificar nav K-12 → logout → login como Colegio Mayor admin → verificar nav changed.

---

## Decisiones de diseño clave

### Locator del sidebar: `nav, aside`

`UDashboardSidebar` de Nuxt UI v4 renderiza como `<nav>`, no como `<aside>`. Usar `page.locator('aside')` devuelve 0 elementos. Todos los locators del sidebar usan:

```typescript
const nav = page.locator('nav, aside')
```

Esto es compatible con cualquier versión futura que pueda cambiar el elemento.

### Operaciones críticas vía API directa

Para operaciones que implican formularios complejos con selects en cascada o labels que cambian según datos (ej. créditos añadidos al nombre de la materia), se usa `page.evaluate` para llamar directamente a la API del backend:

```typescript
const result = await page.evaluate(async ({ token, payload }) => {
  const resp = await fetch('http://localhost:9090/api/enrollments', {
    method: 'POST',
    headers: {
      'Authorization': `Bearer ${token}`,
      'Accept': 'application/json',
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(payload)
  })
  return { status: resp.status, body: await resp.json() }
}, { token, payload })

if (result.status !== 201) throw new Error(`API failed: ${JSON.stringify(result.body)}`)
```

El token se obtiene con:
```typescript
const token = await page.evaluate(() => localStorage.getItem('auth_token'))
```

**¿Por qué no usar el formulario UI?** Los `USelectMenu` de Nuxt UI v4 con `value-key` pueden tener labels que no coinciden exactamente con el texto visible (ej. `"Introducción a la Administración (3 cr.)"` en lugar de `"Introducción a la Administración"`). Usar la API directa elimina esta fragilidad.

### Cleanup de datos entre tests

Los tests del flujo serial crean datos reales en la base de datos compartida. Para evitar fallos por constraint unique en reruns:

1. **Al inicio de test 4**: se eliminan TODAS las matrículas previas de Isabella (independientemente del status) antes de crear la nueva.
2. **Al final (cleanup test)**: se eliminan las matrículas con `status === 'enrolled'` de Isabella para no afectar `higher-ed.spec.ts`.

```typescript
// Cleanup al inicio de test 4
const isabellaEnrollments = prevEnrollments.filter(
  (e: { student?: { user?: { name?: string } } }) =>
    e.student?.user?.name?.includes('Isabella')
)
for (const e of isabellaEnrollments) {
  await page.evaluate(async ({ t, id }) => {
    await fetch(`http://localhost:9090/api/enrollments/${id}`, {
      method: 'DELETE',
      headers: { Authorization: `Bearer ${t}`, Accept: 'application/json' }
    })
  }, { t: token, id: e.id })
}
```

---

## Bugs encontrados y resueltos durante el desarrollo de los tests

### 1. `SubjectController` sin tenant scoping

**Síntoma:** El dropdown de materias en el formulario de matrículas de Colegio Mayor mostraba ~240 materias del colegio K-12 en lugar de las 10 propias.

**Causa:** `SubjectController::index` no filtraba por institución. El modelo `Subject` no tiene `institution_id` propio; se escopa a través de la relación `grade.institution_id`.

**Fix en `app/Http/Controllers/Api/SubjectController.php`:**
```php
$institutionId = TenantService::getInstitutionId();
$query = Subject::with(['area', 'grade'])
    ->whereHas('grade', fn ($q) => $q->where('institution_id', $institutionId));
```

### 2. Labels de materias incluyen créditos

**Síntoma:** El formulario de matrícula renderiza `"Introducción a la Administración (3 cr.)"` pero el test buscaba `"Introducción a la Administración"`. El select nunca se llenaba, `formData.subject_id` quedaba `undefined`, y el handler devolvía un error silencioso. El form nunca se cerraba.

**Falso positivo:** La aserción `toBeAttached` del test encontraba "Isabella Moreno Castro" dentro del dropdown abierto (no en la tabla), pasando el test incorrectamente. El test siguiente (`persiste tras recargar`) fallaba porque la matrícula nunca se creó.

**Fix:** Test 4 reescrito para crear la matrícula vía `POST /api/enrollments` directamente y luego verificar el resultado en la tabla de `/enrollments`.

### 3. Endpoint incorrecto para asignación de docentes

**Síntoma:** `POST /api/teachers/{id}/assignments` devolvía 405 Method Not Allowed.

**Causa:** El endpoint correcto es `POST /api/teachers/{teacher}/assign` (sin 's' al final).

**Fix:** Corregida la URL en `page.evaluate` del test 2.

### 4. `enrollment.final_grade.toFixed is not a function`

**Síntoma:** Error en runtime en la página `/enrollments` al intentar mostrar notas finales.

**Causa:** `final_grade` llega como string desde la API (serialización de `decimal:2`), no como número.

**Fix en `frontend/app/pages/enrollments/index.vue`:**
```html
{{ Number(enrollment.final_grade).toFixed(1) }}
```

---

## Arquitectura de `student-portal.spec.ts`

### Suite 1: Estudiantes IES vía `/guardian`

En el estado actual del DB, los estudiantes de Colegio Mayor tienen `role='guardian'` (el seeder más reciente crea `role='student'`, pero el DB demo fue sembrado con una versión anterior). Ven su propia información a través del portal acudiente (`/guardian/student/{id}`).

Tests: login → redirección a `/guardian`, "Mis Hijos" con código propio, navegación al detalle, selector de período, variantes por estudiante (Laura, Diego, Isabella), aislamiento de nav.

### Suite 2: Portal Acudiente K-12

Usa `acudiente.demo@aula360demo.edu.co` — vinculado a Santiago García López (6° A, Aula360 Demo). Tests: login, "Mis Hijos", nav sin menús de staff, navegación al detalle, períodos y notas, asistencia.

### Suite 3: Portal `/student` (skipped — requiere fresh seed)

`test.describe.skip` — requiere que los estudiantes IES tengan `role='student'` en la DB. Activar ejecutando `php artisan migrate:fresh --seed`. Cubre: `/student` dashboard, `/student/kardex`, nav del portal.

### Suite 4: Aislamiento de roles

Verifica que el admin no filtre datos de estudiantes al acceder a `/student` y que el admin no vea datos de estudiantes vinculados al acceder a `/guardian`.

### Decisiones de diseño en student-portal.spec.ts

**`.first()` en todos los `getByText` por nombre**: Los nombres de estudiantes aparecen en múltiples elementos (nav selector label + heading de bienvenida + tarjeta). Siempre usar `.first()` para evitar `strict mode violation`.

**`.first()` en `or()` compuesto**: `getByText(/Asignatura/).or(getByText(/No hay calificaciones/))` puede resolver dos elementos simultáneamente (la página muestra ambos en estado vacío). Agregar `.first()` al final del `or()`.

**Locator de tarjetas por nombre, no por clase**: `.cursor-pointer` filtrado por nombre del estudiante (ej. `/Santiago García López/`) es más robusto que filtrar por `/Código:/` que puede tener formato variable entre instituciones.

**No cambiar período vía UI**: Los `USelectMenu` de Nuxt UI son frágiles en tests. Las pruebas solo verifican que el selector de período esté visible, no intentan cambiarlo.

---

## Aislamiento de datos entre archivos de test

Los cuatro archivos de prueba comparten la misma base de datos. El orden de ejecución es alfabético:

1. `higher-ed.spec.ts`
2. `public-uni-pages.spec.ts`
3. `student-portal.spec.ts`
4. `workflow.spec.ts`

`workflow.spec.ts` crea y limpia sus propios datos (docente nuevo + matrícula de Isabella). El cleanup final elimina la matrícula de Isabella con `status === 'enrolled'` para que el test `"Isabella no aparece en tabla activa"` de `higher-ed.spec.ts` siga pasando en reruns.

> Si se necesita cambiar el orden de ejecución, verificar que el cleanup de `workflow.spec.ts` cubra todos los estados posibles de las matrículas de Isabella.

---

## Configuración (`playwright.config.ts`)

```typescript
export default defineConfig({
  testDir: './e2e',
  fullyParallel: false,
  retries: 0,
  workers: 1,
  use: {
    baseURL: 'http://localhost:3002',
    screenshot: 'only-on-failure',
  },
})
```

Los tests corren en un solo worker (secuenciales) contra el servidor de desarrollo (`localhost:3002`). El backend debe estar corriendo en `localhost:9090`.

---

## Prerrequisitos para correr los tests

1. Backend corriendo: `composer run dev` (o `sail artisan serve --port=9090`)
2. Frontend corriendo: `cd frontend && npm run dev` (puerto 3002 según config)
3. Base de datos sembrada: `php artisan migrate:fresh --seed`
4. Chromium instalado: `node_modules/.bin/playwright install chromium`
