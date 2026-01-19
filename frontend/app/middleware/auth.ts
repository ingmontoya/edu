export default defineNuxtRouteMiddleware((to) => {
  // Solo ejecutar en el cliente
  if (import.meta.server) {
    return
  }

  const auth = useAuthStore()

  // Si no está autenticado, verificar localStorage directamente
  if (!auth.isAuthenticated) {
    const token = localStorage.getItem('auth_token')
    const user = localStorage.getItem('auth_user')

    if (token && user) {
      // Restaurar estado
      auth.token = token
      auth.user = JSON.parse(user)
      auth.isAuthenticated = true
    } else {
      // No hay sesión, redirigir a login
      return navigateTo('/login')
    }
  }
})
