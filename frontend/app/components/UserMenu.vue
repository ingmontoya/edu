<script setup lang="ts">
import type { DropdownMenuItem } from '@nuxt/ui'

defineProps<{
  collapsed?: boolean
}>()

const auth = useAuthStore()
const router = useRouter()
const colorMode = useColorMode()

const items = computed<DropdownMenuItem[][]>(() => ([[{
  type: 'label',
  label: auth.user?.name || 'User',
  avatar: {
    icon: 'i-lucide-user'
  }
}], [{
  label: 'Profile',
  icon: 'i-lucide-user'
}, {
  label: 'Settings',
  icon: 'i-lucide-settings',
  to: '/settings'
}], [{
  label: 'Appearance',
  icon: 'i-lucide-sun-moon',
  children: [{
    label: 'Light',
    icon: 'i-lucide-sun',
    type: 'checkbox',
    checked: colorMode.value === 'light',
    onSelect(e: Event) {
      e.preventDefault()
      colorMode.preference = 'light'
    }
  }, {
    label: 'Dark',
    icon: 'i-lucide-moon',
    type: 'checkbox',
    checked: colorMode.value === 'dark',
    onSelect(e: Event) {
      e.preventDefault()
      colorMode.preference = 'dark'
    }
  }]
}], [{
  label: 'Log out',
  icon: 'i-lucide-log-out',
  onSelect: async () => {
    await auth.logout()
    router.push('/login')
  }
}]]))
</script>

<template>
  <UDropdownMenu
    :items="items"
    :content="{ align: 'center', collisionPadding: 12 }"
    :ui="{ content: collapsed ? 'w-48' : 'w-(--reka-dropdown-menu-trigger-width)' }"
  >
    <UButton
      :label="collapsed ? undefined : (auth.user?.name || 'User')"
      :trailing-icon="collapsed ? undefined : 'i-lucide-chevrons-up-down'"
      color="neutral"
      variant="ghost"
      block
      :square="collapsed"
      class="data-[state=open]:bg-elevated"
      :ui="{
        trailingIcon: 'text-dimmed'
      }"
    >
      <template #leading>
        <UAvatar icon="i-lucide-user" size="2xs" />
      </template>
    </UButton>
  </UDropdownMenu>
</template>
