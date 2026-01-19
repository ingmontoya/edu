export default defineNuxtPlugin(() => {
  const auth = useAuthStore()

  // Inicializar auth desde localStorage al cargar la app
  auth.initFromStorage()
})
