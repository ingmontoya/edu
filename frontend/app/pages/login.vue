<template>
  <div
    class="min-h-screen flex"
    style="font-family: 'Figtree', sans-serif; color-scheme: light;"
  >
    <link
      href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700;800;900&display=swap"
      rel="stylesheet"
    >

    <!-- Left Panel - Branding -->
    <div class="hidden lg:flex lg:w-[52%] bg-slate-900 p-14 flex-col justify-between relative overflow-hidden">
      <!-- Decorative accent -->
      <div class="absolute -top-20 -right-20 w-80 h-80 rounded-full bg-blue-600/10 pointer-events-none" />
      <div class="absolute -bottom-28 -left-12 w-72 h-72 rounded-full bg-blue-600/10 pointer-events-none" />

      <!-- Logo -->
      <div>
        <span class="text-2xl font-bold text-white tracking-tight">
          Aula<span class="text-blue-400">360</span>
        </span>
      </div>

      <!-- Main copy -->
      <div class="space-y-8 relative z-10">
        <div class="space-y-4">
          <h1 class="text-4xl font-extrabold text-white leading-tight tracking-tight">
            Gestión académica<br>inteligente para<br>tu institución
          </h1>
          <p class="text-lg text-slate-400 max-w-sm leading-relaxed">
            Notas, asistencia, boletines y reportes en un solo lugar — diseñado para Colombia.
          </p>
        </div>

        <!-- Feature list -->
        <ul class="space-y-4">
          <li
            v-for="feat in features"
            :key="feat.text"
            class="flex items-center gap-3"
          >
            <div class="w-8 h-8 rounded-lg bg-slate-800 border border-slate-700 flex items-center justify-center shrink-0">
              <UIcon
                :name="feat.icon"
                class="size-4 text-blue-400"
              />
            </div>
            <span class="text-slate-300 text-sm font-medium">{{ feat.text }}</span>
          </li>
        </ul>
      </div>

      <p class="text-slate-600 text-xs relative z-10">
        &copy; {{ new Date().getFullYear() }} Aula360 · Todos los derechos reservados
      </p>
    </div>

    <!-- Right Panel - Login Form -->
    <div class="w-full lg:w-[48%] flex items-center justify-center p-8 bg-white">
      <div class="w-full max-w-sm">
        <!-- Mobile Logo -->
        <div class="lg:hidden text-center mb-10">
          <span class="text-2xl font-bold text-slate-900">
            Aula<span class="text-blue-600">360</span>
          </span>
        </div>

        <!-- Header -->
        <div class="mb-8">
          <h2 class="text-2xl font-bold text-slate-900 tracking-tight">
            Bienvenido de nuevo
          </h2>
          <p class="text-slate-500 mt-1 text-sm">
            Ingresa tus credenciales para continuar
          </p>
        </div>

        <form class="space-y-5" @submit.prevent="handleLogin">
          <!-- Email -->
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1.5">
              Correo electrónico
            </label>
            <UInput
              v-model="form.email"
              type="email"
              placeholder="usuario@institución.edu.co"
              size="xl"
              icon="i-lucide-mail"
              autocomplete="email"
              class="w-full"
            />
          </div>

          <!-- Password -->
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1.5">
              Contraseña
            </label>
            <div class="relative">
              <UInput
                v-model="form.password"
                :type="showPassword ? 'text' : 'password'"
                placeholder="••••••••"
                size="xl"
                icon="i-lucide-lock"
                autocomplete="current-password"
                class="w-full"
              />
              <button
                type="button"
                class="absolute right-3 top-1/2 -translate-y-1/2 focus:outline-none cursor-pointer"
                @click="showPassword = !showPassword"
              >
                <UIcon
                  :name="showPassword ? 'i-lucide-eye-off' : 'i-lucide-eye'"
                  class="size-5 text-slate-400 hover:text-slate-600 transition-colors"
                />
              </button>
            </div>
          </div>

          <!-- Remember + Forgot -->
          <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 cursor-pointer">
              <UCheckbox v-model="rememberMe" />
              <span class="text-sm text-slate-600">Recordarme</span>
            </label>
            <NuxtLink
              to="/forgot-password"
              class="text-sm text-blue-600 hover:text-blue-700 font-medium transition-colors"
            >
              ¿Olvidó su contraseña?
            </NuxtLink>
          </div>

          <!-- Error -->
          <UAlert
            v-if="errorMessage"
            color="error"
            variant="subtle"
            icon="i-lucide-alert-circle"
          >
            <template #title>
              {{ errorMessage }}
            </template>
          </UAlert>

          <!-- Submit -->
          <UButton
            type="submit"
            size="xl"
            block
            :loading="loading"
            :disabled="!form.email || !form.password"
            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold cursor-pointer"
          >
            <template #leading>
              <UIcon
                v-if="!loading"
                name="i-lucide-log-in"
                class="size-5"
              />
            </template>
            {{ loading ? 'Ingresando...' : 'Ingresar' }}
          </UButton>
        </form>

        <!-- Mobile footer -->
        <p class="lg:hidden mt-10 text-center text-xs text-slate-400">
          &copy; {{ new Date().getFullYear() }} Aula360
        </p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({
  layout: false
})

const auth = useAuthStore()
const router = useRouter()

const features = [
  { icon: 'i-lucide-book-open', text: 'Registro de notas y boletines automáticos' },
  { icon: 'i-lucide-users', text: 'Gestión de estudiantes, docentes y acudientes' },
  { icon: 'i-lucide-calendar-check', text: 'Control de asistencia en tiempo real' },
  { icon: 'i-lucide-sparkles', text: 'Análisis con Inteligencia Artificial' }
]

const form = reactive({
  email: '',
  password: ''
})

const loading = ref(false)
const showPassword = ref(false)
const rememberMe = ref(false)
const errorMessage = ref('')

const getRedirectPath = () => {
  if (auth.isGuardian) return '/guardian'
  if (auth.isStudent) return '/student'
  return '/dashboard'
}

onMounted(() => {
  auth.initFromStorage()
  if (auth.isAuthenticated) {
    router.push(getRedirectPath())
  }
})

async function handleLogin() {
  errorMessage.value = ''
  loading.value = true

  try {
    const result = await auth.login(form.email, form.password)

    if (result.success) {
      router.push(getRedirectPath())
    } else {
      errorMessage.value = result.message || 'Credenciales inválidas'
    }
  } catch {
    errorMessage.value = 'Error de conexión. Por favor intente de nuevo.'
  } finally {
    loading.value = false
  }
}
</script>
