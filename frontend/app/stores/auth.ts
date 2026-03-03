import { defineStore } from 'pinia'

interface User {
  id: number
  name: string
  email: string
  role: string
}

interface AuthState {
  user: User | null
  token: string | null
  isAuthenticated: boolean
}

export const useAuthStore = defineStore('auth', {
  state: (): AuthState => ({
    user: null,
    token: null,
    isAuthenticated: false
  }),

  getters: {
    isAdmin: (state) => state.user?.role === 'admin',
    isCoordinator: (state) => state.user?.role === 'coordinator',
    isTeacher: (state) => state.user?.role === 'teacher',
    isGuardian: (state) => state.user?.role === 'guardian',
    isStaff: (state) => ['admin', 'coordinator', 'teacher'].includes(state.user?.role || '')
  },

  actions: {
    async login(email: string, password: string) {
      const config = useRuntimeConfig()

      try {
        const response = await $fetch<{ data: { user: User, token: string } }>(
          `${config.public.apiUrl}/auth/login`,
          {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json'
            },
            body: { email, password },
            credentials: 'omit'
          }
        )

        this.user = response.data.user
        this.token = response.data.token
        this.isAuthenticated = true

        // Persist to localStorage
        if (import.meta.client) {
          localStorage.setItem('auth_token', response.data.token)
          localStorage.setItem('auth_user', JSON.stringify(response.data.user))
        }

        return { success: true }
      } catch (error: any) {
        return {
          success: false,
          message: error.data?.message || 'Login failed'
        }
      }
    },

    async logout() {
      const config = useRuntimeConfig()

      try {
        if (this.token) {
          await $fetch(`${config.public.apiUrl}/auth/logout`, {
            method: 'POST',
            headers: { Authorization: `Bearer ${this.token}` }
          })
        }
      } catch {
        // Ignore logout errors
      }

      this.user = null
      this.token = null
      this.isAuthenticated = false

      if (import.meta.client) {
        localStorage.removeItem('auth_token')
        localStorage.removeItem('auth_user')
      }
    },

    async fetchUser() {
      if (!this.token) return

      const config = useRuntimeConfig()

      try {
        const response = await $fetch<{ data: User }>(
          `${config.public.apiUrl}/auth/me`,
          {
            headers: { Authorization: `Bearer ${this.token}` }
          }
        )
        this.user = response.data
        this.isAuthenticated = true
      } catch {
        this.logout()
      }
    },

    initFromStorage() {
      if (import.meta.client) {
        const token = localStorage.getItem('auth_token')
        const user = localStorage.getItem('auth_user')

        if (token && user) {
          this.token = token
          this.user = JSON.parse(user)
          this.isAuthenticated = true
        }
      }
    },

    hasRole(role: string): boolean {
      return this.user?.role === role
    }
  }
})
