<script setup lang="ts">
const emit = defineEmits<{ 'open-demo': [] }>()

const route = useRoute()
const mobileMenuOpen = ref(false)
const isScrolled = ref(false)

onMounted(() => {
  const onScroll = () => {
    isScrolled.value = window.scrollY > 80
  }
  window.addEventListener('scroll', onScroll, { passive: true })
  onUnmounted(() => window.removeEventListener('scroll', onScroll))
})

const navLinks = [
  { label: 'Inteligencia Artificial', href: '/#ia' },
  { label: 'Módulos', href: '/#modulos' },
  { label: 'Cómo funciona', href: '/como-funciona' },
  { label: 'Universidades', href: '/universidades' },
  { label: 'Precios', href: '/pricing' },
  { label: 'Contacto', href: '/#contacto' }
]

function isActive(href: string) {
  if (href.startsWith('/#')) return false
  return route.path === href
}
</script>

<template>
  <header
    class="fixed top-0 left-0 right-0 z-50 bg-white border-b border-slate-200 transition-all duration-300"
    :class="isScrolled ? 'shadow-sm' : ''"
  >
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" aria-label="Navegación principal">
      <div class="flex items-center justify-between h-16">
        <a href="/" aria-label="Aula360 - Ir al inicio">
          <span class="text-xl font-bold text-slate-900">Aula<span class="text-blue-600">360</span></span>
        </a>

        <!-- Desktop links -->
        <div class="hidden md:flex items-center gap-6">
          <a
            v-for="link in navLinks"
            :key="link.href"
            :href="link.href"
            class="text-sm transition-colors duration-200"
            :class="isActive(link.href)
              ? 'text-slate-900 font-semibold border-b-2 border-blue-600 pb-0.5'
              : 'text-slate-600 hover:text-slate-900'"
          >
            {{ link.label }}
          </a>
        </div>

        <!-- Actions -->
        <div class="flex items-center gap-3">
          <a
            href="/login"
            class="hidden sm:inline-flex items-center px-4 py-2 text-sm font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-lg transition-colors duration-200"
          >
            Iniciar Sesión
          </a>
          <button
            type="button"
            class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-500 rounded-lg transition-colors duration-200 cursor-pointer"
            @click="emit('open-demo')"
          >
            Solicitar Demo
          </button>

          <!-- Hamburger -->
          <button
            type="button"
            class="md:hidden inline-flex items-center justify-center w-10 h-10 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-colors duration-200 cursor-pointer"
            :aria-expanded="mobileMenuOpen"
            aria-label="Abrir menú"
            @click="mobileMenuOpen = !mobileMenuOpen"
          >
            <svg
              v-if="!mobileMenuOpen"
              xmlns="http://www.w3.org/2000/svg"
              class="w-5 h-5"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
              stroke-width="2"
            >
              <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <svg
              v-else
              xmlns="http://www.w3.org/2000/svg"
              class="w-5 h-5"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
              stroke-width="2"
            >
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>

      <!-- Mobile menu -->
      <Transition name="mobile-menu">
        <div v-if="mobileMenuOpen" class="md:hidden border-t border-slate-200 py-4">
          <div class="flex flex-col gap-1">
            <a
              v-for="link in navLinks"
              :key="link.href"
              :href="link.href"
              class="px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200"
              :class="isActive(link.href)
                ? 'text-blue-600 font-semibold bg-blue-50'
                : 'text-slate-700 hover:text-slate-900 hover:bg-slate-50'"
              @click="mobileMenuOpen = false"
            >
              {{ link.label }}
            </a>
            <div class="pt-3 mt-1 border-t border-slate-200 flex flex-col gap-2">
              <a href="/login" class="px-4 py-3 text-sm font-medium text-slate-700 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-colors duration-200">
                Iniciar Sesión
              </a>
              <button
                type="button"
                class="mx-4 px-4 py-3 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-500 rounded-lg transition-colors duration-200 cursor-pointer text-center"
                @click="emit('open-demo'); mobileMenuOpen = false"
              >
                Solicitar Demo Gratuita
              </button>
            </div>
          </div>
        </div>
      </Transition>
    </nav>
  </header>
</template>

<style scoped>
.mobile-menu-enter-active,
.mobile-menu-leave-active {
  transition: opacity 0.15s ease, transform 0.15s ease;
}
.mobile-menu-enter-from,
.mobile-menu-leave-to {
  opacity: 0;
  transform: translateY(-8px);
}
</style>
