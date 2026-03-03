import type {
  Achievement,
  AchievementIndicator,
  StudentAchievement,
  RemedialActivity,
  StudentRemedial,
  Student
} from '~/types/school'

export const useSiee = () => {
  const api = useApi()

  // ============ Achievements (Logros) ============

  const getAchievements = async (params?: { subject_id?: number; period_id?: number }) => {
    // Guard: both params are required by the API
    if (!params?.subject_id || !params?.period_id) {
      return [] as Achievement[]
    }
    return api.get<Achievement[]>('/achievements', { params })
  }

  const createAchievement = async (data: {
    subject_id: number
    period_id: number
    code?: string
    description: string
    type: 'cognitive' | 'procedural' | 'attitudinal'
    order?: number
    indicators?: { description: string; code?: string }[]
  }) => {
    return api.post<Achievement>('/achievements', data)
  }

  const updateAchievement = async (id: number, data: Partial<Achievement>) => {
    return api.put<Achievement>(`/achievements/${id}`, data)
  }

  const deleteAchievement = async (id: number) => {
    return api.delete(`/achievements/${id}`)
  }

  const addIndicator = async (achievementId: number, data: { description: string; code?: string }) => {
    return api.post<AchievementIndicator>(`/achievements/${achievementId}/indicators`, data)
  }

  const recordStudentAchievement = async (data: {
    student_id: number
    achievement_id: number
    status: 'pending' | 'in_progress' | 'achieved' | 'not_achieved'
    observations?: string
  }) => {
    return api.post<StudentAchievement>('/achievements/record', data)
  }

  const bulkRecordAchievements = async (data: {
    achievement_id: number
    records: { student_id: number; status: string; observations?: string }[]
  }) => {
    return api.post('/achievements/bulk-record', data)
  }

  const getStudentAchievements = async (studentId: number, periodId: number) => {
    return api.get<StudentAchievement[]>(`/students/${studentId}/achievements`, {
      params: { period_id: periodId }
    })
  }

  const copyAchievements = async (data: {
    source_subject_id: number
    source_period_id: number
    target_period_id: number
  }) => {
    return api.post('/achievements/copy', data)
  }

  const importAchievementsFromCsv = async (
    subjectId: number,
    periodId: number,
    file: File
  ): Promise<{ message: string; count: number; errors: string[] }> => {
    const config = useRuntimeConfig()
    const auth = useAuthStore()

    const formData = new FormData()
    formData.append('subject_id', String(subjectId))
    formData.append('period_id', String(periodId))
    formData.append('file', file)

    const response = await fetch(`${config.public.apiUrl}/achievements/import`, {
      method: 'POST',
      headers: {
        Accept: 'application/json',
        Authorization: `Bearer ${auth.token}`
      },
      body: formData
    })

    if (!response.ok) {
      const error = await response.json()
      throw new Error(error.message || 'Error al importar')
    }

    return response.json()
  }

  // ============ Remedial Activities (Nivelaciones) ============

  const getRemedialActivities = async (params?: {
    subject_id?: number
    period_id?: number
    teacher_id?: number
  }) => {
    return api.get<RemedialActivity[]>('/remedials', { params })
  }

  const getRemedialActivity = async (id: number) => {
    return api.get<RemedialActivity>(`/remedials/${id}`)
  }

  const createRemedialActivity = async (data: {
    subject_id: number
    period_id: number
    teacher_id: number
    title: string
    description: string
    instructions?: string
    type: 'recovery' | 'reinforcement' | 'leveling'
    assigned_date: string
    due_date: string
    max_grade?: number
    student_ids?: number[]
  }) => {
    return api.post<RemedialActivity>('/remedials', data)
  }

  const updateRemedialActivity = async (id: number, data: Partial<RemedialActivity>) => {
    return api.put<RemedialActivity>(`/remedials/${id}`, data)
  }

  const deleteRemedialActivity = async (id: number) => {
    return api.delete(`/remedials/${id}`)
  }

  const assignStudentsToRemedial = async (remedialId: number, studentIds: number[]) => {
    return api.post(`/remedials/${remedialId}/assign-students`, { student_ids: studentIds })
  }

  const autoAssignFailingStudents = async (remedialId: number) => {
    return api.post<{ assigned_count: number; total_failing: number }>(
      `/remedials/${remedialId}/auto-assign`
    )
  }

  const gradeStudentRemedial = async (studentRemedialId: number, data: {
    grade: number
    teacher_feedback?: string
    update_grade_record?: boolean
  }) => {
    return api.put<StudentRemedial>(`/student-remedials/${studentRemedialId}/grade`, data)
  }

  const bulkGradeRemedials = async (remedialId: number, data: {
    grades: { student_remedial_id: number; grade: number; teacher_feedback?: string }[]
    update_grade_records?: boolean
  }) => {
    return api.post(`/remedials/${remedialId}/bulk-grade`, data)
  }

  const getStudentsNeedingRemedial = async (subjectId: number, periodId: number) => {
    return api.get<{ students: { student: Student; current_grade: number }[]; count: number }>(
      '/remedials/students-needing',
      { params: { subject_id: subjectId, period_id: periodId } }
    )
  }

  const getStudentRemedials = async (studentId: number, params?: {
    status?: string
    period_id?: number
  }) => {
    return api.get<StudentRemedial[]>(`/students/${studentId}/remedials`, { params })
  }

  // ============ Helpers ============

  const getAchievementTypeLabel = (type: string) => {
    const labels: Record<string, string> = {
      cognitive: 'Cognitivo',
      procedural: 'Procedimental',
      attitudinal: 'Actitudinal'
    }
    return labels[type] || type
  }

  const getAchievementStatusLabel = (status: string) => {
    const labels: Record<string, string> = {
      pending: 'Pendiente',
      in_progress: 'En Progreso',
      achieved: 'Alcanzado',
      not_achieved: 'No Alcanzado'
    }
    return labels[status] || status
  }

  const getAchievementStatusColor = (status: string): 'neutral' | 'warning' | 'success' | 'error' => {
    const colors: Record<string, 'neutral' | 'warning' | 'success' | 'error'> = {
      pending: 'neutral',
      in_progress: 'warning',
      achieved: 'success',
      not_achieved: 'error'
    }
    return colors[status] || 'neutral'
  }

  const getRemedialTypeLabel = (type: string) => {
    const labels: Record<string, string> = {
      recovery: 'Recuperacion',
      reinforcement: 'Refuerzo',
      leveling: 'Nivelacion'
    }
    return labels[type] || type
  }

  const getRemedialStatusLabel = (status: string) => {
    const labels: Record<string, string> = {
      pending: 'Pendiente',
      submitted: 'Entregado',
      graded: 'Calificado',
      excused: 'Excusado'
    }
    return labels[status] || status
  }

  const getRemedialStatusColor = (status: string): 'warning' | 'info' | 'success' | 'neutral' => {
    const colors: Record<string, 'warning' | 'info' | 'success' | 'neutral'> = {
      pending: 'warning',
      submitted: 'info',
      graded: 'success',
      excused: 'neutral'
    }
    return colors[status] || 'neutral'
  }

  const getRemedialTypeColor = (type: string): 'warning' | 'info' | 'success' => {
    const colors: Record<string, 'warning' | 'info' | 'success'> = {
      recovery: 'warning',
      reinforcement: 'info',
      leveling: 'success'
    }
    return colors[type] || 'info'
  }

  // Aliases for consistent naming
  const getRemedials = getRemedialActivities
  const getRemedial = getRemedialActivity
  const createRemedial = createRemedialActivity
  const deleteRemedial = deleteRemedialActivity
  const autoAssignRemedial = async (remedialId: number, groupId: number) => {
    return api.post<{ assigned_count: number }>(`/remedials/${remedialId}/auto-assign`, { group_id: groupId })
  }

  return {
    // Achievements
    getAchievements,
    createAchievement,
    updateAchievement,
    deleteAchievement,
    addIndicator,
    recordStudentAchievement,
    bulkRecordAchievements,
    getStudentAchievements,
    copyAchievements,
    importAchievementsFromCsv,

    // Remedials
    getRemedialActivities,
    getRemedialActivity,
    createRemedialActivity,
    updateRemedialActivity,
    deleteRemedialActivity,
    assignStudentsToRemedial,
    autoAssignFailingStudents,
    gradeStudentRemedial,
    bulkGradeRemedials,
    getStudentsNeedingRemedial,
    getStudentRemedials,

    // Helpers
    getAchievementTypeLabel,
    getAchievementStatusLabel,
    getAchievementStatusColor,
    getRemedialTypeLabel,
    getRemedialStatusLabel,
    getRemedialStatusColor,
    getRemedialTypeColor,

    // Aliases
    getRemedials,
    getRemedial,
    createRemedial,
    deleteRemedial,
    autoAssignRemedial
  }
}
