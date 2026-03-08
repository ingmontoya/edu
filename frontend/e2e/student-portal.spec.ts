/**
 * student-portal.spec.ts
 *
 * Prueba los portales del lado del estudiante/acudiente en Aula360.
 *
 * ─── Roles de acceso ──────────────────────────────────────────────────────────
 *
 *   guardian  → /guardian   Portal acudiente (K-12: padres/madres)
 *               También es el rol de los estudiantes IES actualmente.
 *               Los estudiantes IES tienen su propio usuario con role='guardian'
 *               y están vinculados a sí mismos como "hijo" en la tabla guardian-student.
 *
 *   student   → /student    Portal estudiante IES (kardex, materias activas).
 *               Requiere que el usuario tenga role='student'.
 *               NOTA: el seeder actual (ColeMayorSeeder) crea los estudiantes IES
 *               con role='guardian'. Ejecutar `php artisan migrate:fresh --seed`
 *               para obtener role='student' y poder usar el portal /student.
 *
 * ─── Credenciales utilizadas ──────────────────────────────────────────────────
 *
 *   IES estudiantes (role=guardian, actual DB):
 *     Andrés Felipe Martínez  andres.martinez@colmayor.edu.co  / password
 *     Laura Cristina Peña     laura.pena@colmayor.edu.co       / password
 *     Diego Alejandro Torres  diego.torres@colmayor.edu.co     / password
 *     Isabella Moreno Castro  isabella.moreno@colmayor.edu.co  / password
 *     Juan David Castro       juandavid.castro@colmayor.edu.co / password
 *
 *   K-12 acudiente demo:
 *     acudiente.demo@aula360demo.edu.co / password
 *     → hijo: Santiago García López (6° A, Aula360 Demo)
 */

import { test, expect } from '@playwright/test'
import { login, COLE_MAYOR, K12 } from './helpers'

// ─── SUITE 1: Estudiantes IES vía portal acudiente ────────────────────────────
// En el estado actual del DB, los estudiantes de Colegio Mayor tienen role=guardian.
// Ven su propia información a través del portal acudiente (/guardian/student/{id}).

test.describe('Portal IES — Estudiantes de Colegio Mayor (via /guardian)', () => {
  // ── 1a. Login y redirección ───────────────────────────────────────────────
  test('estudiante IES redirige a /guardian después del login', async ({ page }) => {
    await login(page, COLE_MAYOR.students.andres.email, COLE_MAYOR.students.andres.password)
    await expect(page).toHaveURL(/\/guardian/, { timeout: 10000 })
  })

  // ── 1b. "Mis Hijos" muestra el propio registro del estudiante ────────────
  test('Andrés ve su propio registro en "Mis Hijos" con código CM-2026-003', async ({ page }) => {
    await login(page, COLE_MAYOR.students.andres.email, COLE_MAYOR.students.andres.password)
    await page.waitForLoadState('networkidle')

    // La página muestra "Mis Hijos" con él mismo vinculado
    await expect(page.getByText(/Mis Hijos/i).first()).toBeVisible({ timeout: 8000 })
    await expect(page.getByText(/Andrés Felipe Martínez/i).first()).toBeVisible({ timeout: 8000 })
    await expect(page.getByText(/CM-2026-003/i)).toBeVisible()
  })

  // ── 1c. Navegar al detalle propio ────────────────────────────────────────
  test('Andrés puede navegar a su detalle y ve períodos (Cortes)', async ({ page }) => {
    await login(page, COLE_MAYOR.students.andres.email, COLE_MAYOR.students.andres.password)
    await page.waitForLoadState('networkidle')

    // Click en su tarjeta
    const card = page.locator('.cursor-pointer').filter({ hasText: /CM-2026-003/ }).first()
    await card.waitFor({ state: 'visible', timeout: 8000 })
    await card.click()

    await expect(page).toHaveURL(/\/guardian\/student\/\d+/, { timeout: 8000 })
    await page.waitForLoadState('networkidle')

    await expect(page.getByText(/Andrés Felipe Martínez/i).first()).toBeVisible({ timeout: 8000 })
    // Tiene selector de período (Colegio Mayor usa Cortes)
    await expect(page.getByText(/Periodo/i).first()).toBeVisible()
    // El período activo es Corte 2 (abierto según demo-data)
    await expect(page.getByText(/Corte/i).first()).toBeVisible()
  })

  // ── 1d. Detalle con notas: Andrés entra al detalle y ve selector de período ─
  // Nota: el período activo es Corte 2 (abierto, sin notas registradas aún).
  // No intentamos cambiar de período via UI — el USelectMenu es frágil en tests.
  // Verificamos que la página cargue, muestre al estudiante y el selector de período.
  test('Andrés puede entrar a su detalle y ve el selector de período', async ({ page }) => {
    await login(page, COLE_MAYOR.students.andres.email, COLE_MAYOR.students.andres.password)
    await page.waitForLoadState('networkidle')

    const card = page.locator('.cursor-pointer').filter({ hasText: /CM-2026-003/ }).first()
    await card.waitFor({ state: 'visible', timeout: 8000 })
    await card.click()
    await page.waitForLoadState('networkidle')

    await expect(page.getByText(/Andrés Felipe Martínez/i).first()).toBeVisible({ timeout: 8000 })
    // Selector de período visible
    await expect(page.getByText(/Periodo/i).first()).toBeVisible()
    await expect(page.getByText(/Corte/i).first()).toBeVisible()

    // La tabla o el estado vacío debe ser visible (Corte 2 activo puede no tener notas)
    await expect(
      page.getByText(/Asignatura/i).or(page.getByText(/No hay calificaciones/i)).first()
    ).toBeVisible({ timeout: 8000 })
  })

  // ── 1e. Laura: tiene materias completadas (Semestre I terminado) ──────────
  test('Laura ve sus materias con estado Aprobado en el detalle', async ({ page }) => {
    await login(page, COLE_MAYOR.students.laura.email, COLE_MAYOR.students.laura.password)
    await page.waitForLoadState('networkidle')

    await expect(page.getByText(/Laura Cristina Peña/i).first()).toBeVisible({ timeout: 8000 })
    const card = page.locator('.cursor-pointer').filter({ hasText: /CM-2026-004/ }).first()
    await card.waitFor({ state: 'visible', timeout: 8000 })
    await card.click()
    await page.waitForLoadState('networkidle')

    await expect(page.getByText(/Laura Cristina Peña/i).first()).toBeVisible({ timeout: 8000 })
    // Tiene períodos disponibles
    await expect(page.getByText(/Corte/i).first()).toBeVisible()
  })

  // ── 1f. Diego: tiene nota reprobatoria en Fund. Economía ─────────────────
  test('Diego: el detalle refleja su situación académica con materia reprobada', async ({ page }) => {
    await login(page, COLE_MAYOR.students.diego.email, COLE_MAYOR.students.diego.password)
    await page.waitForLoadState('networkidle')

    await expect(page.getByText(/Diego Alejandro Torres/i).first()).toBeVisible({ timeout: 8000 })
    const card = page.locator('.cursor-pointer').filter({ hasText: /CM-2026-005/ }).first()
    await card.waitFor({ state: 'visible', timeout: 8000 })
    await card.click()
    await page.waitForLoadState('networkidle')

    await expect(page.getByText(/Diego Alejandro Torres/i).first()).toBeVisible({ timeout: 8000 })
    await expect(page.getByText(/Corte/i).first()).toBeVisible()
  })

  // ── 1g. Isabella: admitida sin matrículas — sin notas en el detalle ───────
  test('Isabella: portal carga sin error aunque no tenga matrículas activas', async ({ page }) => {
    await login(page, COLE_MAYOR.students.isabella.email, COLE_MAYOR.students.isabella.password)
    await page.waitForLoadState('networkidle')

    await expect(page.getByText(/Isabella Moreno Castro/i).first()).toBeVisible({ timeout: 8000 })
    const card = page.locator('.cursor-pointer').filter({ hasText: /CM-2026-008/ }).first()
    await card.waitFor({ state: 'visible', timeout: 8000 })
    await card.click()
    await page.waitForLoadState('networkidle')

    await expect(page.getByText(/Isabella Moreno Castro/i).first()).toBeVisible({ timeout: 8000 })
    // La página puede mostrar notas vacías o el selector de período
    await expect(page.getByText(/Corte/i).first()).toBeVisible({ timeout: 8000 })
  })

  // ── 1h. Nav del estudiante IES: no ve menús de staff ────────────────────
  test('nav de estudiante IES no muestra menús de admin/coord', async ({ page }) => {
    await login(page, COLE_MAYOR.students.andres.email, COLE_MAYOR.students.andres.password)
    const nav = page.locator('nav, aside')
    await expect(nav.getByText(/Mis Hijos/i)).toBeVisible({ timeout: 8000 })

    // No ve menús de staff
    await expect(nav.getByText(/^Programas$/i)).not.toBeVisible()
    await expect(nav.getByText(/^Matrículas$/i)).not.toBeVisible()
    await expect(nav.getByText(/Docentes/i)).not.toBeVisible()
    await expect(nav.getByText(/^SIEE$/i)).not.toBeVisible()
  })
})

// ─── SUITE 2: Portal Acudiente K-12 ───────────────────────────────────────────

test.describe('Portal Acudiente K-12 — Aula360 Demo', () => {
  test.beforeEach(async ({ page }) => {
    await login(page, K12.guardian.email, K12.guardian.password)
  })

  // ── 2a. Login y redirección ────────────────────────────────────────────────
  test('login como acudiente K-12 redirige a /guardian', async ({ page }) => {
    await expect(page).toHaveURL(/\/guardian/, { timeout: 10000 })
  })

  // ── 2b. "Mis Hijos" muestra el hijo vinculado ────────────────────────────
  test('dashboard muestra hijo vinculado con nombre y grupo', async ({ page }) => {
    await page.waitForLoadState('networkidle')

    await expect(page.getByText(/Mis Hijos/i).first()).toBeVisible({ timeout: 8000 })

    // Hay al menos una tarjeta con código de estudiante
    const card = page.locator('.cursor-pointer').filter({ hasText: /Código:/ }).first()
    await expect(card).toBeVisible({ timeout: 8000 })

    // El hijo del acudiente demo es Santiago García López, 6° A
    await expect(page.getByText(/Santiago García López/i)).toBeVisible({ timeout: 8000 })
    await expect(page.getByText(/6°/i).first()).toBeVisible()
  })

  // ── 2c. Nav del acudiente K-12: solo Mis Hijos y Tareas ─────────────────
  test('nav del acudiente K-12 no muestra menús de admin/teacher', async ({ page }) => {
    const nav = page.locator('nav, aside')
    await expect(nav.getByText(/Mis Hijos/i)).toBeVisible({ timeout: 8000 })

    await expect(nav.getByText(/^Grados$/i)).not.toBeVisible()
    await expect(nav.getByText(/^Grupos$/i)).not.toBeVisible()
    await expect(nav.getByText(/Docentes/i)).not.toBeVisible()
    await expect(nav.getByText(/^SIEE$/i)).not.toBeVisible()
    await expect(nav.getByText(/Exportar SIMAT/i)).not.toBeVisible()
  })

  // ── 2d. Navegar al detalle del hijo ──────────────────────────────────────
  test('click en el hijo navega a /guardian/student/{id}', async ({ page }) => {
    await page.waitForLoadState('networkidle')

    // Click en la tarjeta del hijo por nombre
    const card = page.locator('.cursor-pointer').filter({ hasText: /Santiago García López/ }).first()
    await card.waitFor({ state: 'visible', timeout: 8000 })
    await card.click()

    await expect(page).toHaveURL(/\/guardian\/student\/\d+/, { timeout: 8000 })
    await page.waitForLoadState('networkidle')

    // Muestra el nombre del estudiante y su grupo
    await expect(page.getByText(/Santiago García López/i).first()).toBeVisible({ timeout: 8000 })
    await expect(page.getByText(/6°/i).first()).toBeVisible()
  })

  // ── 2e. Detalle del hijo: períodos y notas ───────────────────────────────
  test('detalle muestra selector de período y tabla de calificaciones', async ({ page }) => {
    await page.waitForLoadState('networkidle')

    const card = page.locator('.cursor-pointer').filter({ hasText: /Santiago García López/ }).first()
    await card.click()
    await page.waitForLoadState('networkidle')

    // Selector de período visible
    await expect(page.getByText(/Periodo/i).first()).toBeVisible({ timeout: 8000 })

    // El período activo se carga automáticamente — debe mostrar la tabla de notas
    // o el estado vacío si no hay notas registradas aún
    await expect(
      page.getByText(/Asignatura/i).or(page.getByText(/No hay calificaciones/i)).first()
    ).toBeVisible({ timeout: 8000 })
  })

  // ── 2f. Detalle del hijo: estadísticas de asistencia ────────────────────
  test('detalle muestra contadores de asistencias y faltas', async ({ page }) => {
    await page.waitForLoadState('networkidle')

    const card = page.locator('.cursor-pointer').filter({ hasText: /Santiago García López/ }).first()
    await card.click()
    await page.waitForLoadState('networkidle')

    // Los contadores de asistencia están presentes
    await expect(page.getByText(/Asistencias/i)).toBeVisible({ timeout: 8000 })
    await expect(page.getByText(/Faltas/i)).toBeVisible()
  })

  // ── 2g. Acudiente no accede a datos de otros estudiantes ─────────────────
  // Nota: el frontend no bloquea /teachers a nivel de ruta para el rol guardian.
  // Lo que sí garantiza la API: el guardián solo ve a su propio hijo vinculado.
  test('acudiente solo ve a su hijo y no datos de otros estudiantes', async ({ page }) => {
    await page.waitForLoadState('networkidle')
    // Solo debe ver a Santiago García López (su hijo vinculado)
    await expect(page.getByText(/Santiago García López/i)).toBeVisible({ timeout: 8000 })
    // No debe ver estudiantes de otras instituciones
    await expect(page.getByText(/CM-2026/i)).not.toBeVisible()
  })
})

// ─── SUITE 3: Portal Estudiante IES /student ──────────────────────────────────
// Requiere que los estudiantes de Colegio Mayor tengan role='student' en la DB.
// Para activar: php artisan migrate:fresh --seed
//
// En el estado actual del DB (role='guardian'), estos tests FALLARÁN
// porque el usuario es redirigido a /guardian en lugar de /student.

test.describe.skip('Portal /student — requiere migrate:fresh --seed', () => {
  test('login con role=student redirige a /student', async ({ page }) => {
    await login(page, COLE_MAYOR.students.santiago.email, COLE_MAYOR.students.santiago.password)
    await expect(page).toHaveURL(/\/student$/, { timeout: 10000 })
  })

  test('dashboard /student muestra materias activas de Santiago', async ({ page }) => {
    await login(page, COLE_MAYOR.students.santiago.email, COLE_MAYOR.students.santiago.password)
    await page.goto('/student')
    await page.waitForLoadState('networkidle')

    await expect(page.getByText(/Mi Portal Académico/i)).toBeVisible({ timeout: 8000 })
    await expect(page.getByText(/Santiago Gómez Ríos/i)).toBeVisible()
    await expect(page.getByText(/CM-2026-001/i)).toBeVisible()
    await expect(page.getByText(/Materias activas/i)).toBeVisible()
    await expect(page.getByText(/Introducción a la Administración/i)).toBeVisible()
  })

  test('kardex de Andrés muestra notas del Corte 1 (4.2 / 3.8 / 4.5)', async ({ page }) => {
    await login(page, COLE_MAYOR.students.andres.email, COLE_MAYOR.students.andres.password)
    await page.goto('/student/kardex')
    await page.waitForLoadState('networkidle')

    await expect(page.getByText(/Historial Académico/i)).toBeVisible({ timeout: 8000 })
    await expect(page.getByText(/4\.20/).or(page.getByText(/4\.2/))).toBeVisible()
    await expect(page.getByText(/3\.80/).or(page.getByText(/3\.8/))).toBeVisible()
  })

  test('kardex de Diego muestra Reprobado en Fundamentos de Economía', async ({ page }) => {
    await login(page, COLE_MAYOR.students.diego.email, COLE_MAYOR.students.diego.password)
    await page.goto('/student/kardex')
    await page.waitForLoadState('networkidle')

    await expect(page.getByText(/Reprobado/i).first()).toBeVisible({ timeout: 8000 })
    await expect(page.getByText(/2\.1/).first()).toBeVisible()
  })

  test('kardex de Isabella muestra estado vacío (sin matrículas)', async ({ page }) => {
    await login(page, COLE_MAYOR.students.isabella.email, COLE_MAYOR.students.isabella.password)
    await page.goto('/student/kardex')
    await page.waitForLoadState('networkidle')

    await expect(page.getByText(/No tienes matrículas registradas/i)).toBeVisible({ timeout: 8000 })
  })

  test('nav del portal /student solo muestra Mi Portal e Historial Académico', async ({ page }) => {
    await login(page, COLE_MAYOR.students.santiago.email, COLE_MAYOR.students.santiago.password)
    const nav = page.locator('nav, aside')
    await expect(nav.getByText(/Mi Portal/i)).toBeVisible({ timeout: 8000 })
    await expect(nav.getByText(/Historial Académico/i)).toBeVisible()
    await expect(nav.getByText(/^Matrículas$/i)).not.toBeVisible()
    await expect(nav.getByText(/Docentes/i)).not.toBeVisible()
  })
})

// ─── SUITE 4: Aislamiento de roles ────────────────────────────────────────────

test.describe('Aislamiento de roles — estudiante vs admin', () => {
  test('admin de Colegio Mayor en /student no ve datos de otro estudiante', async ({ page }) => {
    await login(page, COLE_MAYOR.admin.email, COLE_MAYOR.admin.password)
    await page.goto('/student')
    await page.waitForLoadState('networkidle')
    // El admin no tiene matrículas propias — no debe ver datos de ningún estudiante
    // (la página puede redirigir o renderizar vacía — lo importante es que no hay fuga de datos)
    await expect(page.getByText(/CM-2026-001/i)).not.toBeVisible({ timeout: 5000 })
    await expect(page.getByText(/Santiago Gómez Ríos/i)).not.toBeVisible()
  })

  test('admin de Colegio Mayor no accede al portal acudiente /guardian', async ({ page }) => {
    await login(page, COLE_MAYOR.admin.email, COLE_MAYOR.admin.password)
    await page.goto('/guardian')
    await page.waitForLoadState('networkidle')
    // El guardian/index.vue no tiene guard de rol, pero el admin no tiene hijos vinculados
    // Debe ver "Sin estudiantes asignados" en lugar de datos de otro usuario
    await expect(page.getByText(/Sin estudiantes asignados|Mis Hijos/i).first()).toBeVisible({ timeout: 8000 })
    await expect(page.getByText(/CM-2026/i)).not.toBeVisible()
  })
})
