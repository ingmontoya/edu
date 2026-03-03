<template>
  <div class="min-h-screen flex">
    <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-700 p-12 flex-col justify-between">
      <div>
        <span class="text-3xl font-bold text-white">Aula<span class="text-primary-300">360</span></span>
      </div>
      <div class="space-y-4">
        <h1 class="text-4xl font-bold text-white leading-tight">
          Nueva Contraseña
        </h1>
        <p class="text-lg text-primary-100 max-w-md">
          Crea una contraseña segura para tu cuenta.
        </p>
      </div>
      <p class="text-primary-200 text-sm">
        &copy; {{ new Date().getFullYear() }} Aula360
      </p>
    </div>

    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-gray-50 dark:bg-gray-900">
      <div class="w-full max-w-md">
        <div class="lg:hidden text-center mb-8">
          <span class="text-2xl font-bold">Aula<span class="text-primary-600 dark:text-primary-400">360</span></span>
        </div>

        <div class="mb-8">
          <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
            Restablecer contraseña
          </h2>
          <p class="text-gray-500 dark:text-gray-400 mt-1">
            Ingresa tu nueva contraseña
          </p>
        </div>

        <div
          v-if="!token || !email"
          class="space-y-4"
        >
          <UAlert
            color="error"
            variant="subtle"
            icon="i-lucide-alert-circle"
            title="Enlace inválido"
            description="El enlace para restablecer contraseña es inválido o ha expirado."
          />
          <UButton
            block
            variant="outline"
            to="/forgot-password"
          >
            Solicitar nuevo enlace
          </UButton>
        </div>

        <div
          v-else-if="success"
          class="space-y-4"
        >
          <UAlert
            color="success"
            variant="subtle"
            icon="i-lucide-check-circle"
            title="Contraseña actualizada"
            description="Tu contraseña ha sido restablecida exitosamente."
          />
          <UButton
            block
            to="/login"
          >
            Iniciar sesión
          </UButton>
        </div>

        <form
          v-else
          class="space-y-5"
          @submit.prevent="handleSubmit"
        >
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Nueva contraseña
            </label>
            <UInput
              v-model="form.password"
              :type="showPassword ? 'text' : 'password'"
              placeholder="Mínimo 8 caracteres"
              size="xl"
              icon="i-lucide-lock"
              class="w-full"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Confirmar contraseña
            </label>
            <UInput
              v-model="form.password_confirmation"
              :type="showPassword ? 'text' : 'password'"
              placeholder="Repite la contraseña"
              size="xl"
              icon="i-lucide-lock"
              class="w-full"
            />
          </div>

          <label class="flex items-center gap-2 cursor-pointer">
            <UCheckbox v-model="showPassword" />
            <span class="text-sm text-gray-600 dark:text-gray-400">Mostrar contraseñas</span>
          </label>

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

          <UButton
            type="submit"
            size="xl"
            block
            :loading="loading"
            :disabled="!form.password || !form.password_confirmation"
          >
            <template #leading>
              <UIcon
                v-if="!loading"
                name="i-lucide-check"
                class="size-5"
              />
            </template>
            {{ loading ? 'Guardando...' : 'Cambiar contraseña' }}
          </UButton>

          <div class="text-center">
            <NuxtLink
              to="/login"
              class="text-sm text-primary-600 hover:text-primary-700 dark:text-primary-400"
            >
              Volver al inicio de sesión
            </NuxtLink>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ layout: false })

const api = useApi()
const route = useRoute()
const router = useRouter()

const token = computed(() => route.query.token as string | undefined)
const email = computed(() => route.query.email as string | undefined)

const form = reactive({
  password: '',
  password_confirmation: ''
})

const loading = ref(false)
const success = ref(false)
const showPassword = ref(false)
const errorMessage = ref('')

async function handleSubmit() {
  errorMessage.value = ''

  if (form.password !== form.password_confirmation) {
    errorMessage.value = 'Las contraseñas no coinciden'
    return
  }

  if (form.password.length < 8) {
    errorMessage.value = 'La contraseña debe tener al menos 8 caracteres'
    return
  }

  loading.value = true

  try {
    await api.post('/auth/reset-password', {
      token: token.value,
      email: email.value,
      password: form.password,
      password_confirmation: form.password_confirmation
    })
    success.value = true
    setTimeout(() => router.push('/login'), 3000)
  } catch (err: unknown) {
    const e = err as { data?: { message?: string } }
    errorMessage.value = e?.data?.message || 'El enlace es inválido o ha expirado. Solicita uno nuevo.'
  } finally {
    loading.value = false
  }
}
</script>
