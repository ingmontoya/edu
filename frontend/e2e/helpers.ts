import type { Page } from '@playwright/test'

export const COLE_MAYOR = {
  admin: { email: 'admin@colmayor.edu.co', password: 'password' },
  coordinator: { email: 'coordinador@colmayor.edu.co', password: 'password' },
  teacher: { email: 'docente.luis@colmayor.edu.co', password: 'password' },
  // Estudiantes IES (rol: student → /student)
  students: {
    santiago: { email: 'santiago.gomez@colmayor.edu.co', name: 'Santiago Gómez Ríos', password: 'password' },
    andres: { email: 'andres.martinez@colmayor.edu.co', name: 'Andrés Felipe Martínez', password: 'password' },
    laura: { email: 'laura.pena@colmayor.edu.co', name: 'Laura Cristina Peña', password: 'password' },
    diego: { email: 'diego.torres@colmayor.edu.co', name: 'Diego Alejandro Torres', password: 'password' },
    isabella: { email: 'isabella.moreno@colmayor.edu.co', name: 'Isabella Moreno Castro', password: 'password' },
    juandavid: { email: 'juandavid.castro@colmayor.edu.co', name: 'Juan David Castro Vargas', password: 'password' }
  }
}

export const K12 = {
  admin: { email: 'admin@example.com', password: 'password' },
  teacher: { email: 'teacher@example.com', password: 'password' },
  // Acudiente demo — vinculado a Santiago García López (6° A)
  guardian: { email: 'acudiente.demo@aula360demo.edu.co', password: 'password' }
}

export async function login(page: Page, email: string, password: string) {
  await page.goto('/login')
  await page.waitForLoadState('networkidle')
  await page.locator('input[type="email"]').fill(email)
  await page.locator('input[type="password"]').fill(password)
  await page.locator('button[type="submit"]').click()
  // Roles: admin/coord/teacher → /dashboard, guardian → /guardian, student → /student
  await page.waitForURL(/dashboard|guardian|student/, { timeout: 15_000 })
}

export async function logout(page: Page) {
  await page.evaluate(() => localStorage.clear())
  await page.goto('/login')
  await page.waitForLoadState('networkidle')
}
