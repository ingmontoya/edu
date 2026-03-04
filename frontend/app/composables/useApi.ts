import type { UseFetchOptions } from 'nuxt/app'
import type { FetchResponse } from 'ofetch'

export const useApi = () => {
  const config = useRuntimeConfig()
  const apiUrl = config.public.apiUrl

  const request = async <T>(endpoint: string, options: RequestInit = {}): Promise<T> => {
    const auth = useAuthStore()

    const response = await $fetch<T>(`${apiUrl}${endpoint}`, {
      credentials: 'omit',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        ...(auth.token && { Authorization: `Bearer ${auth.token}` })
      },
      ...options,
      onResponseError({ response }: { response: FetchResponse<unknown> }) {
        if (response.status === 401) {
          auth.logout()
          navigateTo('/login')
        }
      }
    } as any)
    return response
  }

  const health = () => request<{ status: string, timestamp: string }>('/health')

  const buildUrl = (url: string, params?: Record<string, any>): string => {
    if (!params) return url
    const query = new URLSearchParams()
    for (const [key, value] of Object.entries(params)) {
      if (value !== undefined && value !== null) {
        query.set(key, String(value))
      }
    }
    const queryStr = query.toString()
    return queryStr ? `${url}?${queryStr}` : url
  }

  return {
    request,
    health,
    get: <T>(url: string, options?: { params?: Record<string, any> }) =>
      request<T>(buildUrl(url, options?.params), { method: 'GET' }),
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
    onResponseError({ response }: { response: FetchResponse<unknown> }) {
      if (response.status === 401) {
        auth.logout()
        navigateTo('/login')
      }
    },
    ...options
  } as any)
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
