import type { Schedule, TeacherAssignment } from '~/types/school'

export interface ScheduleInput {
  teacher_assignment_id: number
  day_of_week: number
  start_time: string
  end_time: string
  classroom?: string | null
}

export const useSchedules = () => {
  const api = useApi()

  const getSchedules = (params?: { group_id?: number, teacher_id?: number, academic_year_id?: number }) => {
    const query = new URLSearchParams()
    if (params?.group_id) query.set('group_id', String(params.group_id))
    if (params?.teacher_id) query.set('teacher_id', String(params.teacher_id))
    if (params?.academic_year_id) query.set('academic_year_id', String(params.academic_year_id))
    const queryStr = query.toString() ? `?${query.toString()}` : ''
    return api.get<{ data: Schedule[] }>(`/schedules${queryStr}`)
  }

  const getGroupAssignments = (groupId: number, academicYearId?: number) => {
    const query = new URLSearchParams()
    if (academicYearId) query.set('academic_year_id', String(academicYearId))
    const queryStr = query.toString() ? `?${query.toString()}` : ''
    return api.get<{ data: TeacherAssignment[] }>(`/schedules/group/${groupId}/assignments${queryStr}`)
  }

  const getGroupSchedule = (groupId: number, academicYearId?: number) => {
    const query = new URLSearchParams()
    if (academicYearId) query.set('academic_year_id', String(academicYearId))
    const queryStr = query.toString() ? `?${query.toString()}` : ''
    return api.get<{ data: Schedule[] }>(`/schedules/group/${groupId}${queryStr}`)
  }

  const getTeacherSchedule = (teacherId: number, academicYearId?: number) => {
    const query = new URLSearchParams()
    if (academicYearId) query.set('academic_year_id', String(academicYearId))
    const queryStr = query.toString() ? `?${query.toString()}` : ''
    return api.get<{ data: Schedule[] }>(`/schedules/teacher/${teacherId}${queryStr}`)
  }

  const createSchedule = (data: ScheduleInput) => api.post<Schedule>('/schedules', data)

  const updateSchedule = (id: number, data: Partial<ScheduleInput>) => api.put<Schedule>(`/schedules/${id}`, data)

  const deleteSchedule = (id: number) => api.delete(`/schedules/${id}`)

  return {
    getSchedules,
    getGroupAssignments,
    getGroupSchedule,
    getTeacherSchedule,
    createSchedule,
    updateSchedule,
    deleteSchedule
  }
}
