import type {
  Institution,
  AcademicYear,
  Period,
  Grade,
  Group,
  Area,
  Subject,
  Student,
  Teacher,
  TeacherAssignment,
  Guardian,
  PaginatedResponse
} from '~/types/school'

export interface PaginationParams {
  page?: number
  per_page?: number
  search?: string
}

export const useAcademic = () => {
  const api = useApi()

  // Institution
  const getInstitution = () => api.get<Institution>('/institution')
  const updateInstitution = (data: Partial<Institution>) => api.put<Institution>('/institution', data)

  // Academic Years
  const getAcademicYears = (params?: PaginationParams) => {
    const query = new URLSearchParams()
    if (params?.page) query.set('page', String(params.page))
    if (params?.per_page) query.set('per_page', String(params.per_page))
    if (params?.search) query.set('search', params.search)
    const queryStr = query.toString() ? `?${query.toString()}` : ''
    return api.get<PaginatedResponse<AcademicYear>>(`/academic-years${queryStr}`)
  }
  const getAcademicYear = (id: number) => api.get<AcademicYear>(`/academic-years/${id}`)
  const createAcademicYear = (data: Partial<AcademicYear>) => api.post<AcademicYear>('/academic-years', data)
  const updateAcademicYear = (id: number, data: Partial<AcademicYear>) => api.put<AcademicYear>(`/academic-years/${id}`, data)
  const deleteAcademicYear = (id: number) => api.delete(`/academic-years/${id}`)
  const activateAcademicYear = (id: number) => api.post<AcademicYear>(`/academic-years/${id}/activate`, {})

  // Periods
  const getPeriods = (params?: PaginationParams & { academic_year_id?: number }) => {
    const query = new URLSearchParams()
    if (params?.page) query.set('page', String(params.page))
    if (params?.per_page) query.set('per_page', String(params.per_page))
    if (params?.search) query.set('search', params.search)
    if (params?.academic_year_id) query.set('academic_year_id', String(params.academic_year_id))
    const queryStr = query.toString() ? `?${query.toString()}` : ''
    return api.get<PaginatedResponse<Period>>(`/periods${queryStr}`)
  }
  const createPeriod = (data: Partial<Period> & { academic_year_id: number }) =>
    api.post<Period>(`/academic-years/${data.academic_year_id}/periods`, data)
  const updatePeriod = (id: number, data: Partial<Period>) => api.put<Period>(`/periods/${id}`, data)
  const deletePeriod = (id: number) => api.delete(`/periods/${id}`)
  const closePeriod = (id: number) => api.post<Period>(`/periods/${id}/close`, {})
  const openPeriod = (id: number) => api.post<Period>(`/periods/${id}/open`, {})

  // Grades (School levels)
  const getGrades = (params?: PaginationParams & { level?: string }) => {
    const query = new URLSearchParams()
    if (params?.page) query.set('page', String(params.page))
    if (params?.per_page) query.set('per_page', String(params.per_page))
    if (params?.search) query.set('search', params.search)
    if (params?.level) query.set('level', params.level)
    const queryStr = query.toString() ? `?${query.toString()}` : ''
    return api.get<PaginatedResponse<Grade>>(`/grades${queryStr}`)
  }
  const createGrade = (data: Partial<Grade>) => api.post<Grade>('/grades', data)
  const updateGrade = (id: number, data: Partial<Grade>) => api.put<Grade>(`/grades/${id}`, data)
  const deleteGrade = (id: number) => api.delete(`/grades/${id}`)

  // Groups
  const getGroups = (params?: PaginationParams & { grade_id?: number, academic_year_id?: number }) => {
    const query = new URLSearchParams()
    if (params?.page) query.set('page', String(params.page))
    if (params?.per_page) query.set('per_page', String(params.per_page))
    if (params?.search) query.set('search', params.search)
    if (params?.grade_id) query.set('grade_id', String(params.grade_id))
    if (params?.academic_year_id) query.set('academic_year_id', String(params.academic_year_id))
    const queryStr = query.toString() ? `?${query.toString()}` : ''
    return api.get<PaginatedResponse<Group>>(`/groups${queryStr}`)
  }
  const getGroup = (id: number) => api.get<Group>(`/groups/${id}`)
  const createGroup = (data: Partial<Group>) => api.post<Group>('/groups', data)
  const updateGroup = (id: number, data: Partial<Group>) => api.put<Group>(`/groups/${id}`, data)
  const deleteGroup = (id: number) => api.delete(`/groups/${id}`)
  const getGroupStudents = (id: number) => api.get<Student[]>(`/groups/${id}/students`)
  const assignDirector = (groupId: number, directorId: number) =>
    api.post<Group>(`/groups/${groupId}/assign-director`, { director_id: directorId })

  // Areas
  const getAreas = () => api.get<Area[]>('/areas')
  const createArea = (data: Partial<Area>) => api.post<Area>('/areas', data)
  const updateArea = (id: number, data: Partial<Area>) => api.put<Area>(`/areas/${id}`, data)
  const deleteArea = (id: number) => api.delete(`/areas/${id}`)

  // Subjects
  const getSubjects = (params?: PaginationParams & { area_id?: number, grade_id?: number, group_id?: number }) => {
    const query = new URLSearchParams()
    if (params?.page) query.set('page', String(params.page))
    if (params?.per_page) query.set('per_page', String(params.per_page))
    if (params?.search) query.set('search', params.search)
    if (params?.area_id) query.set('area_id', String(params.area_id))
    if (params?.grade_id) query.set('grade_id', String(params.grade_id))
    if (params?.group_id) query.set('group_id', String(params.group_id))
    const queryStr = query.toString() ? `?${query.toString()}` : ''
    return api.get<PaginatedResponse<Subject>>(`/subjects${queryStr}`)
  }
  const createSubject = (data: Partial<Subject>) => api.post<Subject>('/subjects', data)
  const updateSubject = (id: number, data: Partial<Subject>) => api.put<Subject>(`/subjects/${id}`, data)
  const deleteSubject = (id: number) => api.delete(`/subjects/${id}`)

  // Students
  const getStudents = (params?: PaginationParams & { group_id?: number, status?: string }) => {
    const query = new URLSearchParams()
    if (params?.page) query.set('page', String(params.page))
    if (params?.per_page) query.set('per_page', String(params.per_page))
    if (params?.search) query.set('search', params.search)
    if (params?.group_id) query.set('group_id', String(params.group_id))
    if (params?.status) query.set('status', params.status)
    const queryStr = query.toString() ? `?${query.toString()}` : ''
    return api.get<PaginatedResponse<Student>>(`/students${queryStr}`)
  }
  const getStudent = (id: number) => api.get<Student>(`/students/${id}`)
  const createStudent = (data: any) => api.post<Student>('/students', data)
  const updateStudent = (id: number, data: any) => api.put<Student>(`/students/${id}`, data)
  const deleteStudent = (id: number) => api.delete(`/students/${id}`)
  const getStudentGrades = (id: number, periodId?: number) => {
    const params = periodId ? `?period_id=${periodId}` : ''
    return api.get(`/students/${id}/grades${params}`)
  }
  const getStudentAttendance = (id: number, periodId?: number) => {
    const params = periodId ? `?period_id=${periodId}` : ''
    return api.get(`/students/${id}/attendance${params}`)
  }
  const assignGuardian = (studentId: number, guardianId: number, isPrimary = false) =>
    api.post(`/students/${studentId}/assign-guardian`, { guardian_id: guardianId, is_primary: isPrimary })

  const importStudentsFromCsv = async (
    groupId: number,
    file: File
  ): Promise<{ message: string, count: number, errors: string[] }> => {
    const config = useRuntimeConfig()
    const auth = useAuthStore()

    const formData = new FormData()
    formData.append('group_id', String(groupId))
    formData.append('file', file)

    const response = await fetch(`${config.public.apiUrl}/students/import`, {
      method: 'POST',
      headers: {
        Accept: 'application/json',
        Authorization: `Bearer ${auth.token}`
      },
      body: formData
    })

    if (!response.ok) {
      const error = await response.json()
      throw new Error(error.message || 'Error al importar estudiantes')
    }

    return response.json()
  }

  // Teachers
  const getTeachers = (params?: PaginationParams) => {
    const query = new URLSearchParams()
    if (params?.page) query.set('page', String(params.page))
    if (params?.per_page) query.set('per_page', String(params.per_page))
    if (params?.search) query.set('search', params.search)
    const queryStr = query.toString() ? `?${query.toString()}` : ''
    return api.get<PaginatedResponse<Teacher>>(`/teachers${queryStr}`)
  }
  const getTeacher = (id: number) => api.get<Teacher>(`/teachers/${id}`)
  const createTeacher = (data: any) => api.post<Teacher>('/teachers', data)
  const updateTeacher = (id: number, data: any) => api.put<Teacher>(`/teachers/${id}`, data)
  const deleteTeacher = (id: number) => api.delete(`/teachers/${id}`)
  const getTeacherAssignments = (id: number) => api.get<TeacherAssignment[]>(`/teachers/${id}/assignments`)
  const assignTeacher = (teacherId: number, data: { subject_id: number, group_id: number, academic_year_id: number }) =>
    api.post(`/teachers/${teacherId}/assign`, data)
  const unassignTeacher = (teacherId: number, assignmentId: number) =>
    api.delete(`/teachers/${teacherId}/unassign/${assignmentId}`)

  const importTeachersFromCsv = async (
    file: File
  ): Promise<{ message: string, count: number, errors: string[] }> => {
    const config = useRuntimeConfig()
    const auth = useAuthStore()

    const formData = new FormData()
    formData.append('file', file)

    const response = await fetch(`${config.public.apiUrl}/teachers/import`, {
      method: 'POST',
      headers: {
        Accept: 'application/json',
        Authorization: `Bearer ${auth.token}`
      },
      body: formData
    })

    if (!response.ok) {
      const error = await response.json()
      throw new Error(error.message || 'Error al importar docentes')
    }

    return response.json()
  }

  // Guardians
  const getGuardians = () => api.get<Guardian[]>('/guardians')
  const getGuardian = (id: number) => api.get<Guardian>(`/guardians/${id}`)
  const createGuardian = (data: any) => api.post<Guardian>('/guardians', data)
  const updateGuardian = (id: number, data: any) => api.put<Guardian>(`/guardians/${id}`, data)
  const deleteGuardian = (id: number) => api.delete(`/guardians/${id}`)

  return {
    // Institution
    getInstitution,
    updateInstitution,
    // Academic Years
    getAcademicYears,
    getAcademicYear,
    createAcademicYear,
    updateAcademicYear,
    deleteAcademicYear,
    activateAcademicYear,
    // Periods
    getPeriods,
    createPeriod,
    updatePeriod,
    deletePeriod,
    closePeriod,
    openPeriod,
    // Grades
    getGrades,
    createGrade,
    updateGrade,
    deleteGrade,
    // Groups
    getGroups,
    getGroup,
    createGroup,
    updateGroup,
    deleteGroup,
    getGroupStudents,
    assignDirector,
    // Areas
    getAreas,
    createArea,
    updateArea,
    deleteArea,
    // Subjects
    getSubjects,
    createSubject,
    updateSubject,
    deleteSubject,
    // Students
    getStudents,
    getStudent,
    createStudent,
    updateStudent,
    deleteStudent,
    getStudentGrades,
    getStudentAttendance,
    assignGuardian,
    importStudentsFromCsv,
    // Teachers
    getTeachers,
    getTeacher,
    createTeacher,
    updateTeacher,
    deleteTeacher,
    getTeacherAssignments,
    assignTeacher,
    unassignTeacher,
    importTeachersFromCsv,
    // Guardians
    getGuardians,
    getGuardian,
    createGuardian,
    updateGuardian,
    deleteGuardian
  }
}
