<template>
  <div class="min-h-screen flex">
    <!-- Left Panel - Branding -->
    <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-primary-600 via-primary-700 to-primary-800 p-12 flex-col justify-between">
      <div>
        <div class="flex items-center gap-3">
          <div class="h-12 w-12 bg-white/20 backdrop-blur rounded-xl flex items-center justify-center">
            <UIcon name="i-lucide-layout-dashboard" class="size-7 text-white" />
          </div>
          <span class="text-2xl font-bold text-white">App Template</span>
        </div>
      </div>

      <div class="space-y-6">
        <h1 class="text-4xl font-bold text-white leading-tight">
          Welcome to<br />Your Application
        </h1>
        <p class="text-lg text-primary-100 max-w-md">
          A modern full-stack template with Laravel and Nuxt.js ready for your next project.
        </p>
      </div>

      <p class="text-primary-200 text-sm">
        &copy; {{ new Date().getFullYear() }} Your Company
      </p>
    </div>

    <!-- Right Panel - Login Form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-gray-50 dark:bg-gray-900">
      <div class="w-full max-w-md">
        <!-- Mobile Logo -->
        <div class="lg:hidden text-center mb-8">
          <div class="inline-flex items-center gap-3 mb-4">
            <div class="h-12 w-12 bg-primary-600 rounded-xl flex items-center justify-center">
              <UIcon name="i-lucide-layout-dashboard" class="size-7 text-white" />
            </div>
            <span class="text-2xl font-bold text-gray-900 dark:text-white">App Template</span>
          </div>
        </div>

        <div class="mb-8">
          <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Welcome</h2>
          <p class="text-gray-500 dark:text-gray-400 mt-1">Enter your credentials to continue</p>
        </div>

        <form @submit.prevent="handleLogin" class="space-y-5">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Email
            </label>
            <UInput
              v-model="form.email"
              type="email"
              placeholder="user@example.com"
              size="xl"
              icon="i-lucide-mail"
              autocomplete="email"
              class="w-full"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Password
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
                class="absolute right-3 top-1/2 -translate-y-1/2 focus:outline-none"
                @click="showPassword = !showPassword"
              >
                <UIcon
                  :name="showPassword ? 'i-lucide-eye-off' : 'i-lucide-eye'"
                  class="size-5 text-gray-400 hover:text-gray-300 transition-colors"
                />
              </button>
            </div>
          </div>

          <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 cursor-pointer">
              <UCheckbox v-model="rememberMe" />
              <span class="text-sm text-gray-600 dark:text-gray-400">Remember me</span>
            </label>
            <a href="#" class="text-sm text-primary-600 hover:text-primary-700 dark:text-primary-400">
              Forgot password?
            </a>
          </div>

          <UAlert
            v-if="errorMessage"
            color="error"
            variant="subtle"
            icon="i-lucide-alert-circle"
          >
            <template #title>{{ errorMessage }}</template>
          </UAlert>

          <UButton
            type="submit"
            size="xl"
            block
            :loading="loading"
            :disabled="!form.email || !form.password"
          >
            <template #leading>
              <UIcon v-if="!loading" name="i-lucide-log-in" class="size-5" />
            </template>
            {{ loading ? 'Signing in...' : 'Sign In' }}
          </UButton>
        </form>

        <!-- Mobile Footer -->
        <p class="lg:hidden mt-8 text-center text-xs text-gray-400">
          &copy; {{ new Date().getFullYear() }} Your Company
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

const form = reactive({
  email: '',
  password: ''
})

const loading = ref(false)
const showPassword = ref(false)
const rememberMe = ref(false)
const errorMessage = ref('')

onMounted(() => {
  auth.initFromStorage()
  if (auth.isAuthenticated) {
    router.push('/')
  }
})

async function handleLogin() {
  errorMessage.value = ''
  loading.value = true

  try {
    const result = await auth.login(form.email, form.password)

    if (result.success) {
      router.push('/')
    } else {
      errorMessage.value = result.message || 'Invalid credentials'
    }
  } catch {
    errorMessage.value = 'Connection error. Please try again.'
  } finally {
    loading.value = false
  }
}
</script>
