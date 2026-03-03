<template>
  <div class="min-h-screen flex">
    <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-700 p-12 flex-col justify-between">
      <div>
        <span class="text-3xl font-bold text-white">Aula<span class="text-primary-300">360</span></span>
      </div>
      <div class="space-y-4">
        <h1 class="text-4xl font-bold text-white leading-tight">
          Recuperar Contraseña
        </h1>
        <p class="text-lg text-primary-100 max-w-md">
          Te enviaremos instrucciones a tu correo para restablecer tu contraseña.
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
            ¿Olvidaste tu contraseña?
          </h2>
          <p class="text-gray-500 dark:text-gray-400 mt-1">
            Ingresa tu correo y te enviaremos instrucciones
          </p>
        </div>

        <div
          v-if="sent"
          class="space-y-4"
        >
          <UAlert
            color="success"
            variant="subtle"
            icon="i-lucide-check-circle"
            title="Correo enviado"
            description="Si el correo existe en nuestro sistema, recibirás instrucciones para restablecer tu contraseña en los próximos minutos."
          />
          <UButton
            block
            variant="outline"
            to="/login"
          >
            Volver al inicio de sesión
          </UButton>
        </div>

        <form
          v-else
          class="space-y-5"
          @submit.prevent="handleSubmit"
        >
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Correo electrónico
            </label>
            <UInput
              v-model="email"
              type="email"
              placeholder="usuario@ejemplo.com"
              size="xl"
              icon="i-lucide-mail"
              autocomplete="email"
              class="w-full"
            />
          </div>

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
            :disabled="!email"
          >
            <template #leading>
              <UIcon
                v-if="!loading"
                name="i-lucide-send"
                class="size-5"
              />
            </template>
            {{ loading ? 'Enviando...' : 'Enviar instrucciones' }}
          </UButton>

          <div class="text-center">
            <NuxtLink
              to="/login"
              class="text-sm text-primary-600 hover:text-primary-700 dark:text-primary-400"
            >
              ← Volver al inicio de sesión
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

const email = ref('')
const loading = ref(false)
const sent = ref(false)
const errorMessage = ref('')

async function handleSubmit() {
  errorMessage.value = ''
  loading.value = true

  try {
    await api.post('/auth/forgot-password', { email: email.value })
    sent.value = true
  } catch {
    // Always show success to avoid email enumeration
    sent.value = true
  } finally {
    loading.value = false
  }
}
</script>
