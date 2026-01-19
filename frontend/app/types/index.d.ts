// API Response types
export interface ApiResponse<T> {
  data: T
  message?: string
}

// Auth types
export interface AuthUser {
  id: number
  name: string
  email: string
  role: string
}

// Add your custom types here...
