<script setup lang="ts">
definePageMeta({
  middleware: 'auth'
})

const { health } = useApi()

// API connection status
const apiStatus = ref<'loading' | 'connected' | 'error'>('loading')
const apiTimestamp = ref<string>('')

onMounted(async () => {
  try {
    const response = await health()
    apiStatus.value = 'connected'
    apiTimestamp.value = response.timestamp
  } catch {
    apiStatus.value = 'error'
  }
})
</script>

<template>
  <UDashboardPanel id="home">
    <template #header>
      <UDashboardNavbar title="Dashboard">
        <template #leading>
          <UDashboardSidebarCollapse />
        </template>
      </UDashboardNavbar>
    </template>

    <template #body>
      <!-- API Status Banner -->
      <UAlert
        v-if="apiStatus === 'connected'"
        color="success"
        icon="i-lucide-check-circle"
        title="API Connected"
        :description="`Backend responding - ${apiTimestamp}`"
        class="mb-4"
      />
      <UAlert
        v-else-if="apiStatus === 'error'"
        color="error"
        icon="i-lucide-alert-circle"
        title="Connection Error"
        description="Could not connect to Laravel backend at localhost:9090"
        class="mb-4"
      />
      <UAlert
        v-else
        color="neutral"
        icon="i-lucide-loader"
        title="Connecting..."
        description="Verifying Laravel API connection"
        class="mb-4"
      />

      <!-- Welcome Card -->
      <UCard>
        <div class="text-center py-12">
          <UIcon name="i-lucide-rocket" class="size-16 text-primary-500 mx-auto mb-4" />
          <h2 class="text-2xl font-bold mb-2">Welcome to App Template</h2>
          <p class="text-gray-500 dark:text-gray-400 max-w-md mx-auto">
            This is a full-stack template with Laravel 12 and Nuxt 4. Start building your application by adding pages, components, and API routes.
          </p>
        </div>
      </UCard>
    </template>
  </UDashboardPanel>
</template>
