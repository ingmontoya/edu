import { test, expect } from '@playwright/test'

const BASE = 'http://localhost:3002'

test.describe('University public pages navigation', () => {
  test('/universidades carga y tiene navbar correcta', async ({ page }) => {
    await page.goto(`${BASE}/universidades`)
    await expect(page).toHaveTitle(/universidades|Aula360/i)

    // Navbar debe tener los links correctos
    const navLinks = page.locator('header nav a, header a')
    const hrefs = await navLinks.evaluateAll(els =>
      els.map(el => (el as HTMLAnchorElement).getAttribute('href'))
    )
    expect(hrefs).toContain('/universidades/como-funciona')
    expect(hrefs).toContain('/universidades/pricing')
  })

  test('/universidades/pricing carga sin errores', async ({ page }) => {
    const errors: string[] = []
    page.on('console', (msg) => {
      if (msg.type() === 'error') errors.push(msg.text())
    })
    page.on('pageerror', err => errors.push(err.message))

    await page.goto(`${BASE}/universidades/pricing`)
    await page.waitForLoadState('networkidle')

    // Título de la página visible
    await expect(page.locator('h1')).toContainText(/precio|plan/i)

    // No hay errores de JS
    const jsErrors = errors.filter(e => !e.includes('favicon'))
    expect(jsErrors, `JS errors found: ${jsErrors.join(', ')}`).toHaveLength(0)

    // Toggle de facturación funciona
    const toggle = page.getByRole('switch')
    await expect(toggle).toBeVisible()
    await toggle.click()
    await expect(toggle).toHaveAttribute('aria-checked', 'false')

    // Botones de plan activos
    const ctaButtons = page.locator('button').filter({ hasText: /demo|cotización/i })
    await expect(ctaButtons.first()).toBeVisible()
  })

  test('/universidades/como-funciona carga sin errores', async ({ page }) => {
    const failed404: string[] = []
    page.on('response', (res) => {
      if (res.status() === 404 && !res.url().includes('favicon')) {
        failed404.push(`404: ${res.url()}`)
      }
    })

    await page.goto(`${BASE}/universidades/como-funciona`)
    await page.waitForLoadState('networkidle')

    // Título visible
    await expect(page.locator('h1')).toBeVisible()

    // Sidebar visible en desktop
    await expect(page.locator('aside')).toBeVisible()

    // Todos los pasos presentes
    for (const id of ['paso-01', 'paso-02', 'paso-03', 'paso-04', 'paso-05', 'paso-06']) {
      await expect(page.locator(`#${id}`)).toBeVisible()
    }

    // No hay 404s
    expect(failed404, `404s found: ${failed404.join(', ')}`).toHaveLength(0)
  })

  test('navbar link "Precios" navega a /universidades/pricing', async ({ page }) => {
    await page.goto(`${BASE}/universidades`)
    await page.waitForLoadState('networkidle')

    const preciosLink = page.locator('header').getByRole('link', { name: 'Precios' }).first()
    await expect(preciosLink).toBeVisible()
    await preciosLink.click()
    await page.waitForLoadState('networkidle')
    expect(page.url()).toContain('/universidades/pricing')
  })

  test('navbar link "Cómo funciona" navega a /universidades/como-funciona', async ({ page }) => {
    await page.goto(`${BASE}/universidades`)
    await page.waitForLoadState('networkidle')

    const cfLink = page.locator('header').getByRole('link', { name: /cómo funciona/i }).first()
    await expect(cfLink).toBeVisible()
    await cfLink.click()
    await page.waitForLoadState('networkidle')
    expect(page.url()).toContain('/universidades/como-funciona')
  })

  test('sidebar de como-funciona tiene links de anclaje que llevan a cada paso', async ({ page }) => {
    await page.goto(`${BASE}/universidades/como-funciona`)
    await page.waitForLoadState('networkidle')

    const sidebarLinks = page.locator('aside nav a')
    await expect(sidebarLinks).toHaveCount(6)

    // Click en "IA anti-deserción" y verifica que el paso 05 es visible
    await sidebarLinks.filter({ hasText: 'IA anti-deserción' }).click()
    await page.waitForTimeout(500)
    const paso05 = page.locator('#paso-05')
    await expect(paso05).toBeInViewport()
  })

  test('pricing page: link "Ver planes para colegios" apunta a /pricing', async ({ page }) => {
    await page.goto(`${BASE}/universidades/pricing`)
    await page.waitForLoadState('networkidle')

    const colegioLink = page.getByRole('link', { name: /ver planes para colegios/i })
    await expect(colegioLink).toBeVisible()
    await expect(colegioLink).toHaveAttribute('href', '/pricing')
  })
})
