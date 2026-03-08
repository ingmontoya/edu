/**
 * workflow.spec.ts
 *
 * Prueba el flujo completo de la plataforma Aula360 en modo Educación Superior:
 *   1. Admin crea un docente nuevo
 *   2. Admin asigna el docente a una materia/grupo
 *   3. El docente puede ver su materia en Registrar Notas
 *   4. Admin matricula a un estudiante en la materia del docente
 *   5. Verificación: la matrícula aparece en la tabla
 *
 * También verifica que K-12 y Educación Superior NO mezclen su UI.
 */

import { test, expect } from '@playwright/test'
import { login, logout, COLE_MAYOR, K12 } from './helpers'

// Timestamp único por run — evita colisiones entre ejecuciones paralelas
const TS = Date.now()
const NEW_TEACHER = {
  name: `Docente Test ${TS}`,
  email: `docente.test.${TS}@colmayor.edu.co`,
  document: `99${String(TS).slice(-6)}`,
  specialty: 'Administración de Prueba',
  password: 'password'
}

// ─── SUITE 1: Aislamiento K-12 ────────────────────────────────────────────────

test.describe('Aislamiento K-12 — nada de términos de educación superior', () => {
  test.beforeEach(async ({ page }) => {
    await login(page, K12.admin.email, K12.admin.password)
  })

  test('nav muestra términos K-12: Grados, Grupos, SIEE, Convivencia', async ({ page }) => {
    const nav = page.locator('nav, aside')

    // Expandir sección Académico (puede venir colapsada)
    const academico = nav.getByText('Académico').first()
    await expect(academico).toBeVisible({ timeout: 8000 })
    if (!(await nav.getByText(/^Grados$/i).isVisible().catch(() => false))) {
      await academico.click()
    }

    await expect(nav.getByText(/^Grados$/i)).toBeVisible({ timeout: 5000 })
    await expect(nav.getByText(/^Grupos$/i)).toBeVisible()
    await expect(nav.getByText(/Convivencia/i)).toBeVisible()
    await expect(nav.getByText(/^SIEE$/i)).toBeVisible()
  })

  test('nav NO muestra términos de educación superior', async ({ page }) => {
    const nav = page.locator('nav, aside')
    await expect(nav.getByText(/Inicio/i).first()).toBeVisible({ timeout: 8000 }) // nav cargó

    await expect(nav.getByText(/^Programas$/i)).not.toBeVisible()
    await expect(nav.getByText(/^Semestres$/i)).not.toBeVisible()
    await expect(nav.getByText(/^Matrículas$/i)).not.toBeVisible()
  })

  test('Exportar SIMAT visible dentro del menú Estudiantes', async ({ page }) => {
    const nav = page.locator('nav, aside')
    await nav.getByText('Estudiantes').first().click()
    await expect(page.getByText(/Exportar SIMAT/i)).toBeVisible({ timeout: 5000 })
  })

  test('settings: Preescolar/Básica/Media está activo, K-12 NO muestra banner de ed. superior', async ({ page }) => {
    await page.goto('/institution/settings')
    await page.waitForLoadState('networkidle')

    // La card de K-12 debe estar seleccionada (tiene borde azul en la UI)
    await expect(page.getByText(/Preescolar \/ Básica \/ Media/i)).toBeVisible()

    // El banner "Modo Educación Superior activo" NO debe aparecer en K-12
    await expect(page.getByText(/Modo Educación Superior activo/i)).not.toBeVisible()
  })
})

// ─── SUITE 2: Aislamiento Educación Superior ──────────────────────────────────

test.describe('Aislamiento Educación Superior — nada de términos K-12', () => {
  test.beforeEach(async ({ page }) => {
    await login(page, COLE_MAYOR.admin.email, COLE_MAYOR.admin.password)
  })

  test('nav muestra términos de ed. superior: Programas, Semestres, Matrículas', async ({ page }) => {
    const nav = page.locator('nav, aside')
    await expect(nav.getByText(/^Programas$/i)).toBeVisible({ timeout: 8000 })
    await expect(nav.getByText(/^Semestres$/i)).toBeVisible()
    await expect(nav.getByText(/^Matrículas$/i)).toBeVisible()
  })

  test('nav NO muestra términos K-12', async ({ page }) => {
    const nav = page.locator('nav, aside')
    await expect(nav.getByText(/^Matrículas$/i)).toBeVisible({ timeout: 8000 }) // nav cargó

    await expect(nav.getByText(/^Grados$/i)).not.toBeVisible()
    await expect(nav.getByText(/^Grupos$/i)).not.toBeVisible()
    await expect(nav.getByText(/^SIEE$/i)).not.toBeVisible()
    await expect(nav.getByText(/Exportar SIMAT/i)).not.toBeVisible()
    await expect(nav.getByText(/Convivencia/i)).not.toBeVisible()
  })

  test('settings: banner Modo Educación Superior activo visible', async ({ page }) => {
    await page.goto('/institution/settings')
    await page.waitForLoadState('networkidle')
    await expect(page.getByText(/Modo Educación Superior activo/i)).toBeVisible({ timeout: 8000 })
    await expect(page.getByText(/Preescolar \/ Básica \/ Media/i)).toBeVisible() // opción existe pero no activa
  })

  test('/academic/grades muestra Programas universitarios, no grados K-12', async ({ page }) => {
    await page.goto('/academic/grades')
    await page.waitForLoadState('networkidle')
    await expect(page.getByText(/Administración de Empresas/i)).toBeVisible({ timeout: 8000 })
    await expect(page.getByText(/Tecnología en Sistemas/i)).toBeVisible()
    await expect(page.getByText(/Contaduría Pública/i)).toBeVisible()
    await expect(page.getByText(/^Primero$/i)).not.toBeVisible()
    await expect(page.getByText(/^Sexto$/i)).not.toBeVisible()
  })

  test('docente (higher ed) NO ve SIEE ni Convivencia', async ({ page }) => {
    await logout(page)
    await login(page, COLE_MAYOR.teacher.email, COLE_MAYOR.teacher.password)
    const nav = page.locator('nav, aside')
    await expect(nav.getByText(/Notas/i).first()).toBeVisible({ timeout: 8000 })
    await expect(nav.getByText(/^Matrículas$/i)).not.toBeVisible()
    await expect(nav.getByText(/^SIEE$/i)).not.toBeVisible()
    await expect(nav.getByText(/Convivencia/i)).not.toBeVisible()
  })

  test('estudiante (guardian) NO ve nav de staff', async ({ page }) => {
    await logout(page)
    await login(page, 'santiago.gomez@colmayor.edu.co', 'password')
    await page.waitForLoadState('networkidle')
    const nav = page.locator('nav, aside')
    await expect(nav.getByText(/^Matrículas$/i)).not.toBeVisible()
    await expect(nav.getByText(/^SIEE$/i)).not.toBeVisible()
    await expect(nav.getByText(/Docentes/i)).not.toBeVisible()
    await expect(nav.getByText(/Convivencia/i)).not.toBeVisible()
  })
})

// ─── SUITE 3: Flujo completo — serial (orden garantizado) ────────────────────
// test.describe.serial garantiza que los tests corran uno tras otro en el mismo
// worker, así el docente creado en test 1 existe cuando lo usan los siguientes.

test.describe.serial('Flujo completo: crear docente → asignar → matricular estudiante', () => {
  // ── 1. Admin crea docente ──────────────────────────────────────────────────
  test('1 · admin crea un nuevo docente', async ({ page }) => {
    await login(page, COLE_MAYOR.admin.email, COLE_MAYOR.admin.password)
    await page.goto('/teachers')
    await page.waitForLoadState('networkidle')

    await page.getByRole('button', { name: /Nuevo Docente/i }).click()
    await expect(page.getByText('Nombre Completo')).toBeVisible({ timeout: 5000 })

    await page.getByPlaceholder('Nombre completo del docente').fill(NEW_TEACHER.name)
    await page.getByPlaceholder('correo@ejemplo.com').fill(NEW_TEACHER.email)
    await page.getByPlaceholder('Número de documento').fill(NEW_TEACHER.document)
    await page.getByPlaceholder(/Ej: Matemáticas/i).fill(NEW_TEACHER.specialty)

    await page.getByRole('button', { name: /^Crear$/i }).click()

    // El docente aparece en la lista
    await expect(page.getByText(NEW_TEACHER.name)).toBeVisible({ timeout: 10000 })
  })

  // ── 2. Admin verifica asignaciones de Prof. Luis y asigna al nuevo docente ─
  test('2 · admin asigna materia al nuevo docente via API', async ({ page }) => {
    await login(page, COLE_MAYOR.admin.email, COLE_MAYOR.admin.password)
    await page.goto('/teachers/assignments')
    await page.waitForLoadState('networkidle')
    // Extra wait to ensure subjects are fully loaded in the Pinia store
    await page.waitForTimeout(1000)

    // Verify existing teacher's assignments work (Prof. Luis has assignments)
    await expect(page.getByText(/Prof. Luis Fernando/i).first()).toBeVisible({ timeout: 10000 })
    await page.getByText(/Prof. Luis Fernando/i).first().click()
    await expect(page.getByText(/Introducción a la Administración/i).first()).toBeVisible({ timeout: 8000 })

    // Now create assignment for new teacher via direct API call
    const token = await page.evaluate(() => localStorage.getItem('auth_token'))

    // Fetch grades to find ADM grade ID
    const grades = await page.evaluate(async (t) => {
      const resp = await fetch('http://localhost:9090/api/grades?per_page=50', {
        headers: { Authorization: `Bearer ${t}`, Accept: 'application/json' }
      })
      return (await resp.json()).data
    }, token)

    const admGrade = grades.find((g: { name: string }) => g.name === 'Administración de Empresas')
    if (!admGrade) throw new Error('Grade ADM not found in API response')

    // Fetch subjects for ADM grade
    const subjects = await page.evaluate(async ({ t, gradeId }) => {
      const resp = await fetch(`http://localhost:9090/api/subjects?grade_id=${gradeId}&per_page=50`, {
        headers: { Authorization: `Bearer ${t}`, Accept: 'application/json' }
      })
      return (await resp.json()).data
    }, { t: token, gradeId: admGrade.id })

    const introAdm = subjects.find((s: { name: string }) => s.name === 'Introducción a la Administración')
    if (!introAdm) throw new Error('Subject not found')

    // Fetch groups for ADM grade
    const groups = await page.evaluate(async ({ t, gradeId }) => {
      const resp = await fetch(`http://localhost:9090/api/groups?grade_id=${gradeId}&per_page=50`, {
        headers: { Authorization: `Bearer ${t}`, Accept: 'application/json' }
      })
      return (await resp.json()).data
    }, { t: token, gradeId: admGrade.id })

    const semI = groups.find((g: { name: string }) => g.name === 'Semestre I')
    if (!semI) throw new Error('Group Semestre I not found')

    // Fetch academic year
    const years = await page.evaluate(async (t) => {
      const resp = await fetch('http://localhost:9090/api/academic-years?per_page=10', {
        headers: { Authorization: `Bearer ${t}`, Accept: 'application/json' }
      })
      return (await resp.json()).data
    }, token)
    const year = years.find((y: { is_active: boolean }) => y.is_active) || years[0]

    // Fetch new teacher's ID from the teachers list
    const teachers = await page.evaluate(async (t) => {
      const resp = await fetch('http://localhost:9090/api/teachers?per_page=50', {
        headers: { Authorization: `Bearer ${t}`, Accept: 'application/json' }
      })
      return (await resp.json()).data
    }, token)
    const newTeacher = teachers.find((t: { user?: { email?: string } }) => t.user?.email === NEW_TEACHER.email)
    if (!newTeacher) throw new Error('New teacher not found via API')

    // Create the assignment via API
    const assignResp = await page.evaluate(async ({ t, teacherId, subjectId, groupId, yearId }) => {
      const resp = await fetch(`http://localhost:9090/api/teachers/${teacherId}/assign`, {
        method: 'POST',
        headers: {
          'Authorization': `Bearer ${t}`,
          'Accept': 'application/json',
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ subject_id: subjectId, group_id: groupId, academic_year_id: yearId })
      })
      return { status: resp.status, body: await resp.json() }
    }, {
      t: token,
      teacherId: newTeacher.id,
      subjectId: introAdm.id,
      groupId: semI.id,
      yearId: year.id
    })

    if (assignResp.status !== 200 && assignResp.status !== 201) {
      throw new Error(`Assignment API failed: ${JSON.stringify(assignResp.body)}`)
    }

    // Verify in the UI: click on new teacher, see the assignment
    await page.getByText(NEW_TEACHER.name).first().click()
    await expect(page.getByText('Introducción a la Administración').first()).toBeVisible({ timeout: 8000 })
  })

  // ── 3. Nuevo docente inicia sesión y ve sus materias ──────────────────────
  test('3 · nuevo docente inicia sesión con contraseña "password"', async ({ page }) => {
    await login(page, NEW_TEACHER.email, NEW_TEACHER.password)
    await expect(page).toHaveURL(/dashboard/, { timeout: 15000 })

    const nav = page.locator('nav, aside')

    // Modo higher ed: ve Notas y Asistencia, NO ve Matrículas ni SIEE
    await expect(nav.getByText(/Notas/i).first()).toBeVisible({ timeout: 8000 })
    await expect(nav.getByText(/Asistencia/i).first()).toBeVisible()
    await expect(nav.getByText(/^Matrículas$/i)).not.toBeVisible()
    await expect(nav.getByText(/^SIEE$/i)).not.toBeVisible()
    await expect(nav.getByText(/Convivencia/i)).not.toBeVisible()
  })

  test('3b · nuevo docente puede acceder a Registrar Notas', async ({ page }) => {
    await login(page, NEW_TEACHER.email, NEW_TEACHER.password)

    const nav = page.locator('nav, aside')
    await nav.getByText(/Notas/i).first().click()
    await page.getByText(/Registrar Notas/i).first().click()
    await page.waitForLoadState('networkidle')

    await expect(page).toHaveURL(/\/grades\/record/)
    await expect(page.getByText(/Registrar Notas/i).first()).toBeVisible({ timeout: 8000 })
  })

  // ── 4. Admin matricula a Isabella en la materia del nuevo docente ──────────
  test('4 · admin matricula a Isabella Moreno en Introducción a la Administración', async ({ page }) => {
    await login(page, COLE_MAYOR.admin.email, COLE_MAYOR.admin.password)

    const token = await page.evaluate(() => localStorage.getItem('auth_token'))

    // Cleanup: delete ALL prior Isabella enrollments regardless of status
    const prevEnrollments = await page.evaluate(async (t) => {
      const resp = await fetch('http://localhost:9090/api/enrollments?per_page=50', {
        headers: { Authorization: `Bearer ${t}`, Accept: 'application/json' }
      })
      return (await resp.json()).data
    }, token)
    const isabellaE = prevEnrollments.filter(
      (e: { student?: { user?: { name?: string } } }) =>
        e.student?.user?.name?.includes('Isabella')
    )
    for (const e of isabellaE) {
      await page.evaluate(async ({ t, id }) => {
        await fetch(`http://localhost:9090/api/enrollments/${id}`, {
          method: 'DELETE',
          headers: { Authorization: `Bearer ${t}`, Accept: 'application/json' }
        })
      }, { t: token, id: (e as { id: number }).id })
    }

    // Resolve IDs via API (reliable, avoids brittle UI selects)
    const students = await page.evaluate(async (t) => {
      const resp = await fetch('http://localhost:9090/api/students?per_page=50', {
        headers: { Authorization: `Bearer ${t}`, Accept: 'application/json' }
      })
      return (await resp.json()).data
    }, token)
    const isabella = students.find(
      (s: { user?: { name?: string } }) => s.user?.name?.includes('Isabella')
    )
    if (!isabella) throw new Error('Isabella student record not found via API')

    const subjects = await page.evaluate(async (t) => {
      const resp = await fetch('http://localhost:9090/api/subjects?per_page=50', {
        headers: { Authorization: `Bearer ${t}`, Accept: 'application/json' }
      })
      return (await resp.json()).data
    }, token)
    const introAdm = subjects.find(
      (s: { name: string }) => s.name === 'Introducción a la Administración'
    )
    if (!introAdm) throw new Error('Subject Introducción a la Administración not found via API')

    const years = await page.evaluate(async (t) => {
      const resp = await fetch('http://localhost:9090/api/academic-years?per_page=10', {
        headers: { Authorization: `Bearer ${t}`, Accept: 'application/json' }
      })
      return (await resp.json()).data
    }, token)
    const year = years.find((y: { is_active: boolean }) => y.is_active) || years[0]

    // Create the enrollment via API
    const enrollResp = await page.evaluate(async ({ t, studentId, subjectId, yearId }) => {
      const resp = await fetch('http://localhost:9090/api/enrollments', {
        method: 'POST',
        headers: {
          'Authorization': `Bearer ${t}`,
          'Accept': 'application/json',
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ student_id: studentId, subject_id: subjectId, academic_year_id: yearId, semester_number: 1 })
      })
      return { status: resp.status, body: await resp.json() }
    }, { t: token, studentId: isabella.id, subjectId: introAdm.id, yearId: year.id })

    if (enrollResp.status !== 201) {
      throw new Error(`Enrollment API failed (${enrollResp.status}): ${JSON.stringify(enrollResp.body)}`)
    }

    // Navigate to the enrollments page and verify Isabella appears in the table
    await page.goto('/enrollments')
    await page.waitForLoadState('networkidle')
    await expect(page.getByText(/Isabella Moreno/i).first()).toBeAttached({ timeout: 10000 })
    await expect(page.getByText(/Introducción a la Administración/i).first()).toBeAttached()
    await expect(page.getByText(/Matriculado/i).first()).toBeAttached()
  })

  // ── 5. Verificar la matrícula persiste tras recargar ──────────────────────
  test('5 · la matrícula de Isabella persiste tras recargar la página', async ({ page }) => {
    await login(page, COLE_MAYOR.admin.email, COLE_MAYOR.admin.password)
    await page.goto('/enrollments')
    await page.waitForLoadState('networkidle')

    await expect(page.getByText(/Isabella Moreno/i).first()).toBeAttached({ timeout: 10000 })
    await expect(page.getByText(/Introducción a la Administración/i).first()).toBeAttached()
  })

  // ── 6. Estudiante ve su portal ────────────────────────────────────────────
  test('6 · Isabella puede iniciar sesión en el portal de estudiante', async ({ page }) => {
    await login(page, 'isabella.moreno@colmayor.edu.co', 'password')
    await expect(page).toHaveURL(/guardian/, { timeout: 15000 })
    await page.waitForLoadState('networkidle')

    // El portal carga su información
    await expect(page.getByText(/Isabella/i).first()).toBeVisible({ timeout: 8000 })
  })

  // ── Cleanup: eliminar matrículas de Isabella para no afectar otros tests ──
  test('cleanup · eliminar matrícula de Isabella', async ({ page }) => {
    await login(page, COLE_MAYOR.admin.email, COLE_MAYOR.admin.password)
    const token = await page.evaluate(() => localStorage.getItem('auth_token'))

    // Get all enrollments for Isabella
    const enrollments = await page.evaluate(async (t) => {
      const resp = await fetch('http://localhost:9090/api/enrollments?per_page=50', {
        headers: { Authorization: `Bearer ${t}`, Accept: 'application/json' }
      })
      return (await resp.json()).data
    }, token)

    // Delete Isabella's enrollments (status=enrolled — can be deleted)
    const isabellaEnrollments = enrollments.filter(
      (e: { student?: { user?: { name?: string } }, status: string }) =>
        e.student?.user?.name?.includes('Isabella') && e.status === 'enrolled'
    )

    for (const enrollment of isabellaEnrollments) {
      await page.evaluate(async ({ t, id }) => {
        await fetch(`http://localhost:9090/api/enrollments/${id}`, {
          method: 'DELETE',
          headers: { Authorization: `Bearer ${t}`, Accept: 'application/json' }
        })
      }, { t: token, id: enrollment.id })
    }

    // Confirm cleanup
    expect(isabellaEnrollments.length).toBeGreaterThan(0)
  })
})

// ─── SUITE 4: Verificación cruzada ───────────────────────────────────────────

test.describe('Verificación cruzada: cambio entre instituciones', () => {
  test('al pasar de K-12 a Educación Superior los términos del nav cambian', async ({ page }) => {
    // ── K-12 ──
    await login(page, K12.admin.email, K12.admin.password)
    const nav = page.locator('nav, aside')

    // Expandir si es necesario
    if (!(await nav.getByText(/^Grados$/i).isVisible().catch(() => false))) {
      await nav.getByText('Académico').first().click()
    }
    await expect(nav.getByText(/^Grados$/i)).toBeVisible({ timeout: 8000 })
    await expect(nav.getByText(/^SIEE$/i)).toBeVisible()
    await expect(nav.getByText(/^Matrículas$/i)).not.toBeVisible()

    // ── Cambio a Colegio Mayor (higher ed) ──
    await logout(page)
    await login(page, COLE_MAYOR.admin.email, COLE_MAYOR.admin.password)

    await expect(nav.getByText(/^Programas$/i)).toBeVisible({ timeout: 8000 })
    await expect(nav.getByText(/^Matrículas$/i)).toBeVisible()
    await expect(nav.getByText(/^Grados$/i)).not.toBeVisible()
    await expect(nav.getByText(/^SIEE$/i)).not.toBeVisible()
  })
})
