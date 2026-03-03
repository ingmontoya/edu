<script setup lang="ts">
import type { NavigationMenuItem } from '@nuxt/ui'

const auth = useAuthStore()
const open = ref(false)

const mainLinks = computed<NavigationMenuItem[]>(() => {
  const links: NavigationMenuItem[] = []

  // Guardian has different home
  if (auth.isGuardian) {
    links.push({
      label: 'Mis Hijos',
      icon: 'i-lucide-users',
      to: '/guardian',
      onSelect: () => { open.value = false }
    })
    return links
  }

  // Staff home
  links.push({
    label: 'Inicio',
    icon: 'i-lucide-layout-dashboard',
    to: '/dashboard',
    onSelect: () => { open.value = false }
  })

  // Admin & Coordinator links
  if (auth.isAdmin || auth.isCoordinator) {
    links.push({
      label: 'Académico',
      icon: 'i-lucide-graduation-cap',
      type: 'trigger',
      defaultOpen: true,
      children: [{
        label: 'Años Académicos',
        icon: 'i-lucide-chevron-right',
        to: '/academic/years',
        onSelect: () => { open.value = false }
      }, {
        label: 'Grados',
        icon: 'i-lucide-chevron-right',
        to: '/academic/grades',
        onSelect: () => { open.value = false }
      }, {
        label: 'Grupos',
        icon: 'i-lucide-chevron-right',
        to: '/academic/groups',
        onSelect: () => { open.value = false }
      }, {
        label: 'Áreas y Asignaturas',
        icon: 'i-lucide-chevron-right',
        to: '/academic/subjects',
        onSelect: () => { open.value = false }
      }]
    })
  }

  // Staff links (admin, coordinator, teacher)
  if (auth.isStaff) {
    if (auth.isAdmin || auth.isCoordinator) {
      links.push({
        label: 'Estudiantes',
        icon: 'i-lucide-users',
        type: 'trigger',
        children: [{
          label: 'Listado',
          icon: 'i-lucide-chevron-right',
          to: '/students',
          onSelect: () => { open.value = false }
        }, {
          label: 'Exportar SIMAT',
          icon: 'i-lucide-chevron-right',
          to: '/students/simat-export',
          onSelect: () => { open.value = false }
        }]
      })
    } else {
      links.push({
        label: 'Estudiantes',
        icon: 'i-lucide-users',
        to: '/students',
        onSelect: () => { open.value = false }
      })
    }

    if (auth.isAdmin || auth.isCoordinator) {
      links.push({
        label: 'Docentes',
        icon: 'i-lucide-briefcase',
        to: '/teachers',
        onSelect: () => { open.value = false }
      })
    }

    links.push({
      label: 'Notas',
      icon: 'i-lucide-file-text',
      type: 'trigger',
      children: [{
        label: 'Registrar Notas',
        icon: 'i-lucide-chevron-right',
        to: '/grades/record',
        onSelect: () => { open.value = false }
      }, {
        label: 'Planilla',
        icon: 'i-lucide-chevron-right',
        to: '/grades/worksheet',
        onSelect: () => { open.value = false }
      }]
    })

    links.push({
      label: 'Asistencia',
      icon: 'i-lucide-calendar-check',
      to: '/attendance',
      onSelect: () => { open.value = false }
    })

    links.push({
      label: 'Reportes',
      icon: 'i-lucide-bar-chart-3',
      type: 'trigger',
      children: [{
        label: 'Boletines',
        icon: 'i-lucide-chevron-right',
        to: '/reports/cards',
        onSelect: () => { open.value = false }
      }, {
        label: 'Constancias',
        icon: 'i-lucide-chevron-right',
        to: '/reports/certificates',
        onSelect: () => { open.value = false }
      }, {
        label: 'Consolidado',
        icon: 'i-lucide-chevron-right',
        to: '/reports/consolidation',
        onSelect: () => { open.value = false }
      }, {
        label: 'Estudiantes en Riesgo',
        icon: 'i-lucide-chevron-right',
        to: '/reports/failing',
        onSelect: () => { open.value = false }
      }]
    })

    if (auth.isAdmin || auth.isCoordinator) {
      links.push({
        label: 'Convivencia',
        icon: 'i-lucide-shield',
        to: '/convivencia',
        onSelect: () => { open.value = false }
      })
    }

    links.push({
      label: 'SIEE',
      icon: 'i-lucide-target',
      type: 'trigger',
      children: [{
        label: 'Logros',
        icon: 'i-lucide-chevron-right',
        to: '/siee/achievements',
        onSelect: () => { open.value = false }
      }, {
        label: 'Registrar Logros',
        icon: 'i-lucide-chevron-right',
        to: '/siee/achievements/record',
        onSelect: () => { open.value = false }
      }, {
        label: 'Nivelaciones',
        icon: 'i-lucide-chevron-right',
        to: '/siee/remedials',
        onSelect: () => { open.value = false }
      }]
    })
  }

  return links
})

const settingsLinks = computed<NavigationMenuItem[]>(() => {
  const links: NavigationMenuItem[] = []

  if (auth.isAdmin) {
    links.push({
      label: 'Institución',
      icon: 'i-lucide-building-2',
      to: '/institution/settings',
      onSelect: () => { open.value = false }
    })
  }

  links.push({
    label: 'Mi Perfil',
    icon: 'i-lucide-user',
    to: '/settings',
    onSelect: () => { open.value = false }
  })

  return links
})
</script>

<template>
  <UDashboardGroup unit="rem">
    <UDashboardSidebar
      id="default"
      v-model:open="open"
      collapsible
      resizable
      class="bg-elevated/25"
      :ui="{ footer: 'lg:border-t lg:border-default' }"
    >
      <template #header="{ collapsed }">
        <NuxtLink to="/dashboard" class="flex items-center px-2 py-2" :class="{ 'justify-center': collapsed }">
          <span v-if="collapsed" class="text-xl font-bold text-primary">A</span>
          <span v-else class="text-xl font-bold">Aula<span class="text-primary">360</span></span>
        </NuxtLink>
      </template>

      <template #default="{ collapsed }">
        <UDashboardSearchButton :collapsed="collapsed" class="bg-transparent ring-default" />

        <UNavigationMenu
          :collapsed="collapsed"
          :items="mainLinks"
          orientation="vertical"
          tooltip
          popover
        />

        <UNavigationMenu
          :collapsed="collapsed"
          :items="settingsLinks"
          orientation="vertical"
          tooltip
          class="mt-auto"
        />
      </template>

      <template #footer="{ collapsed }">
        <UserMenu :collapsed="collapsed" />
      </template>
    </UDashboardSidebar>

    <slot />

    <NotificationsSlideover />
  </UDashboardGroup>
</template>
