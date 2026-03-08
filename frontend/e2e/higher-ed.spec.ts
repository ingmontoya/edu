import { test, expect } from '@playwright/test'
import { login, logout, COLE_MAYOR, K12 } from './helpers'

// ─── SUITE 1: K-12 sigue funcionando ──────────────────────────────────────────
test.describe('K-12 no roto', () => {
  test('admin K-12 ve navegación K-12 (Grados, SIEE, SIMAT)', async ({ page }) => {
    await login(page, K12.admin.email, K12.admin.password)
    const nav = page.locator('nav, aside')
    await expect(nav.getByText(/Grados/i)).toBeVisible()
    await expect(nav.getByText(/SIEE/i)).toBeVisible()
    // SIMAT is inside the "Estudiantes" dropdown — expand it first
    await nav.getByText('Estudiantes').click()
    await expect(page.getByText(/Exportar SIMAT/i)).toBeVisible({ timeout: 5000 })
    await expect(nav.getByText(/Matrículas/i)).not.toBeVisible()
    await logout(page)
  })
})

// ─── SUITE 2: Colegio Mayor — Admin ───────────────────────────────────────────
test.describe('Colegio Mayor — Admin', () => {
  test.beforeEach(async ({ page }) => {
    await login(page, COLE_MAYOR.admin.email, COLE_MAYOR.admin.password)
  })

  test('ve terminología de educación superior en navegación', async ({ page }) => {
    const nav = page.locator('nav, aside')
    await expect(nav.getByText(/Programas/i)).toBeVisible()
    await expect(nav.getByText(/Semestres/i)).toBeVisible()
    await expect(nav.getByText(/Matrículas/i)).toBeVisible()
    // K-12 específicos NO deben aparecer
    await expect(nav.getByText(/^SIEE$/i)).not.toBeVisible()
    await expect(nav.getByText(/Exportar SIMAT/i)).not.toBeVisible()
    await expect(nav.getByText(/Convivencia/i)).not.toBeVisible()
  })

  test('página de matrículas carga y muestra tabla', async ({ page }) => {
    await page.goto('/enrollments')
    await page.waitForLoadState('networkidle')
    await expect(page.getByText(/matrícula|enrollment/i).first()).toBeVisible()
    // Debe ver tabla con datos de estudiantes matriculados
    await expect(page.getByText(/Santiago/i).first()).toBeAttached({ timeout: 10000 })
  })

  test('estudiante con todas las materias matriculadas — Santiago', async ({ page }) => {
    await page.goto('/enrollments')
    await page.waitForLoadState('networkidle')
    await expect(page.getByText(/Santiago Gómez/i).first()).toBeAttached({ timeout: 10000 })
    await expect(page.getByText(/Introducción a la Administración/i).first()).toBeAttached({ timeout: 10000 })
  })

  test('estudiante retirado — María Fernanda tiene materia RETIRADA', async ({ page }) => {
    await page.goto('/enrollments')
    await page.waitForLoadState('networkidle')
    await expect(page.getByText(/María Fernanda/i).first()).toBeAttached({ timeout: 10000 })
    await expect(page.getByText(/Retirado|withdrawn/i).first()).toBeAttached({ timeout: 10000 })
  })

  test('estudiante sin matrículas — Isabella no aparece en tabla activa', async ({ page }) => {
    await page.goto('/enrollments')
    await page.waitForLoadState('networkidle')
    // Isabella no tiene matrículas activas, no debe aparecer en la tabla
    await expect(page.getByText(/Isabella Moreno/i)).not.toBeAttached()
  })

  test('estudiante que reprobó y se re-matriculó — Diego con failed + enrolled', async ({ page }) => {
    await page.goto('/enrollments')
    await page.waitForLoadState('networkidle')
    await expect(page.getByText(/Diego Alejandro/i).first()).toBeAttached({ timeout: 10000 })
    await expect(page.getByText(/Reprobado|failed/i).first()).toBeAttached({ timeout: 10000 })
  })

  test('estudiante con materias completadas — Laura tiene completed', async ({ page }) => {
    await page.goto('/enrollments')
    await page.waitForLoadState('networkidle')
    await expect(page.getByText(/Laura Cristina/i).first()).toBeAttached({ timeout: 10000 })
    await expect(page.getByText(/Completado|completed/i).first()).toBeAttached({ timeout: 10000 })
  })

  test('settings muestra modalidad Educación Superior activa', async ({ page }) => {
    await page.goto('/institution/settings')
    await expect(page.getByText(/Educación Superior/i).first()).toBeVisible()
    await expect(page.getByText(/Modo Educación Superior activo/i)).toBeVisible()
  })

  test('programas muestra Administración, Sistemas, Contaduría', async ({ page }) => {
    await page.goto('/academic/grades')
    await expect(page.getByText(/Administración de Empresas/i)).toBeVisible()
    await expect(page.getByText(/Tecnología en Sistemas/i)).toBeVisible()
    await expect(page.getByText(/Contaduría Pública/i)).toBeVisible()
  })

  test('materias muestra campo créditos', async ({ page }) => {
    await page.goto('/academic/subjects')
    // En modo higher ed debe verse el campo créditos
    await expect(page.getByText(/crédito|credits/i).first()).toBeVisible()
  })

  test('listado de estudiantes muestra los 10 del Colegio Mayor', async ({ page }) => {
    await page.goto('/students')
    await expect(page.getByText(/Santiago Gómez/i)).toBeVisible()
    await expect(page.getByText(/Valentina Ríos/i)).toBeVisible()
    await expect(page.getByText(/Juan David Castro/i)).toBeVisible()
  })
})

// ─── SUITE 3: Colegio Mayor — Coordinador ────────────────────────────────────
test.describe('Colegio Mayor — Coordinador', () => {
  test.beforeEach(async ({ page }) => {
    await login(page, COLE_MAYOR.coordinator.email, COLE_MAYOR.coordinator.password)
  })

  test('coordinador ve Matrículas en nav', async ({ page }) => {
    const nav = page.locator('nav, aside')
    await expect(nav.getByText(/Matrículas/i)).toBeVisible()
  })

  test('coordinador puede acceder a /enrollments', async ({ page }) => {
    await page.goto('/enrollments')
    await expect(page).not.toHaveURL('/login')
  })
})

// ─── SUITE 4: Colegio Mayor — Docente ────────────────────────────────────────
test.describe('Colegio Mayor — Docente', () => {
  test.beforeEach(async ({ page }) => {
    await login(page, COLE_MAYOR.teacher.email, COLE_MAYOR.teacher.password)
  })

  test('docente NO ve Matrículas en nav', async ({ page }) => {
    const nav = page.locator('nav, aside')
    await expect(nav.getByText(/Matrículas/i)).not.toBeVisible()
  })

  test('docente ve Notas y Asistencia', async ({ page }) => {
    const nav = page.locator('nav, aside')
    await expect(nav.getByText(/Notas/i)).toBeVisible()
    await expect(nav.getByText(/Asistencia/i)).toBeVisible()
  })
})
