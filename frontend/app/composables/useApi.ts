import type { UseFetchOptions } from 'nuxt/app'

export const useApi = () => {
  const config = useRuntimeConfig()
  const apiUrl = config.public.apiUrl

  const request = async <T>(endpoint: string, options: RequestInit = {}): Promise<T> => {
    const auth = useAuthStore()

    const response = await $fetch<T>(`${apiUrl}${endpoint}`, {
      credentials: 'omit',
      headers: {
        Accept: 'application/json',
        'Content-Type': 'application/json',
        ...(auth.token && { Authorization: `Bearer ${auth.token}` })
      },
      ...options,
      onResponseError({ response }) {
        if (response.status === 401) {
          auth.logout()
          navigateTo('/login')
        }
      }
    } as any)
    return response
  }

  const health = () => request<{ status: string, timestamp: string }>('/health')

  return {
    request,
    health,
    get: <T>(url: string) => request<T>(url, { method: 'GET' }),
    post: <T>(url: string, body: any) => request<T>(url, { method: 'POST', body: JSON.stringify(body) }),
    put: <T>(url: string, body: any) => request<T>(url, { method: 'PUT', body: JSON.stringify(body) }),
    delete: <T>(url: string) => request<T>(url, { method: 'DELETE' })
  }
}

// Composable con useFetch para SSR
export function useApiGet<T>(url: string, options: UseFetchOptions<T> = {}) {
  const config = useRuntimeConfig()
  const auth = useAuthStore()

  return useFetch<T>(url, {
    baseURL: config.public.apiUrl as string,
    credentials: 'omit',
    headers: {
      Accept: 'application/json',
      ...(auth.token && { Authorization: `Bearer ${auth.token}` })
    },
    onResponseError({ response }) {
      if (response.status === 401) {
        auth.logout()
        navigateTo('/login')
      }
    },
    ...options
  })
}

export function useApiPost<T>(url: string, body: any, options: UseFetchOptions<T> = {}) {
  return useApiGet<T>(url, { method: 'POST', body, ...options })
}

export function useApiPut<T>(url: string, body: any, options: UseFetchOptions<T> = {}) {
  return useApiGet<T>(url, { method: 'PUT', body, ...options })
}

export function useApiDelete<T>(url: string, options: UseFetchOptions<T> = {}) {
  return useApiGet<T>(url, { method: 'DELETE', ...options })
}
