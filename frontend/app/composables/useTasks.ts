import type { Task, StudentTask } from '~/types/school'

export const useTasks = () => {
  const api = useApi()
  const config = useRuntimeConfig()
  const auth = useAuthStore()

  const getTasks = async (params?: { group_id?: number, subject_id?: number }) => {
    return api.get<Task[]>('/tasks', { params })
  }

  const getTask = async (id: number) => {
    return api.get<Task>(`/tasks/${id}`)
  }

  const createTask = async (
    data: {
      teacher_id: number
      group_id: number
      subject_id?: number
      title: string
      instructions: string
      due_date: string
      is_published?: boolean
    },
    file?: File | null
  ): Promise<Task> => {
    const formData = new FormData()
    formData.append('teacher_id', String(data.teacher_id))
    formData.append('group_id', String(data.group_id))
    formData.append('title', data.title)
    formData.append('instructions', data.instructions)
    formData.append('due_date', data.due_date)
    if (data.subject_id) formData.append('subject_id', String(data.subject_id))
    if (data.is_published !== undefined) formData.append('is_published', data.is_published ? '1' : '0')
    if (file) formData.append('attachment', file)

    const response = await fetch(`${config.public.apiUrl}/tasks`, {
      method: 'POST',
      headers: {
        Accept: 'application/json',
        Authorization: `Bearer ${auth.token}`
      },
      body: formData
    })

    if (!response.ok) {
      const error = await response.json()
      throw new Error(error.message || 'Error al crear la tarea')
    }

    return response.json()
  }

  const updateTask = async (
    id: number,
    data: Partial<{ title: string, instructions: string, due_date: string, subject_id: number, is_published: boolean }>,
    file?: File | null
  ): Promise<Task> => {
    const formData = new FormData()
    if (data.title !== undefined) formData.append('title', data.title)
    if (data.instructions !== undefined) formData.append('instructions', data.instructions)
    if (data.due_date !== undefined) formData.append('due_date', data.due_date)
    if (data.subject_id !== undefined) formData.append('subject_id', String(data.subject_id))
    if (data.is_published !== undefined) formData.append('is_published', data.is_published ? '1' : '0')
    if (file) formData.append('attachment', file)

    const response = await fetch(`${config.public.apiUrl}/tasks/${id}`, {
      method: 'POST',
      headers: {
        Accept: 'application/json',
        Authorization: `Bearer ${auth.token}`
      },
      body: formData
    })

    if (!response.ok) {
      const error = await response.json()
      throw new Error(error.message || 'Error al actualizar la tarea')
    }

    return response.json()
  }

  const deleteTask = async (id: number) => {
    return api.delete(`/tasks/${id}`)
  }

  const downloadAttachment = async (task: Task) => {
    const response = await fetch(`${config.public.apiUrl}/tasks/${task.id}/attachment`, {
      headers: {
        Authorization: `Bearer ${auth.token}`
      }
    })

    if (!response.ok) throw new Error('Error al descargar el archivo')

    const blob = await response.blob()
    const url = URL.createObjectURL(blob)
    const a = document.createElement('a')
    a.href = url
    a.download = task.attachment_name || 'attachment.pdf'
    a.click()
    URL.revokeObjectURL(url)
  }

  const submitTask = async (studentTaskId: number, file: File): Promise<StudentTask> => {
    const formData = new FormData()
    formData.append('file', file)

    const response = await fetch(`${config.public.apiUrl}/student-tasks/${studentTaskId}/submit`, {
      method: 'POST',
      headers: {
        Accept: 'application/json',
        Authorization: `Bearer ${auth.token}`
      },
      body: formData
    })

    if (!response.ok) {
      const error = await response.json()
      throw new Error(error.message || 'Error al entregar la tarea')
    }

    return response.json()
  }

  const downloadSubmission = async (studentTask: StudentTask) => {
    const response = await fetch(`${config.public.apiUrl}/student-tasks/${studentTask.id}/download`, {
      headers: {
        Authorization: `Bearer ${auth.token}`
      }
    })

    if (!response.ok) throw new Error('Error al descargar la entrega')

    const blob = await response.blob()
    const url = URL.createObjectURL(blob)
    const a = document.createElement('a')
    a.href = url
    a.download = studentTask.submission_name || 'submission'
    a.click()
    URL.revokeObjectURL(url)
  }

  const reviewStudentTask = async (id: number) => {
    return api.put<StudentTask>(`/student-tasks/${id}/review`, {})
  }

  const getGuardianTasks = async (studentId?: number) => {
    return api.get<StudentTask[]>('/guardian/tasks', {
      params: studentId ? { student_id: studentId } : undefined
    })
  }

  // Helpers
  const getStatusLabel = (status: string) => {
    const labels: Record<string, string> = {
      pending: 'Pendiente',
      submitted: 'Entregado',
      reviewed: 'Revisado'
    }
    return labels[status] || status
  }

  const getStatusColor = (status: string): 'warning' | 'success' | 'info' | 'neutral' => {
    const colors: Record<string, 'warning' | 'success' | 'info' | 'neutral'> = {
      pending: 'warning',
      submitted: 'info',
      reviewed: 'success'
    }
    return colors[status] || 'neutral'
  }

  const isOverdue = (dueDate: string) => {
    return new Date(dueDate) < new Date()
  }

  return {
    getTasks,
    getTask,
    createTask,
    updateTask,
    deleteTask,
    downloadAttachment,
    submitTask,
    downloadSubmission,
    reviewStudentTask,
    getGuardianTasks,
    getStatusLabel,
    getStatusColor,
    isOverdue
  }
}
