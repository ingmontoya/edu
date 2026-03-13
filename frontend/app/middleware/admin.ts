export default defineNuxtRouteMiddleware(() => {
  if (import.meta.server) return

  const auth = useAuthStore()

  if (!auth.isAuthenticated) {
    const token = localStorage.getItem('auth_token')
    const user = localStorage.getItem('auth_user')
    if (token && user) {
      auth.token = token
      auth.user = JSON.parse(user)
      auth.isAuthenticated = true
    } else {
      return navigateTo('/login')
    }
  }

  if (!auth.isAdmin && !auth.isCoordinator) {
    return navigateTo('/login')
  }
})
