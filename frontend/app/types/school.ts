/**
 * Aula360 — Shared TypeScript types
 *
 * Domain types are based on the OpenAPI specs in /docs/specs/.
 * Generated raw types (path/operation/component schemas) live in ./generated/{module}.d.ts
 * — they are auto-generated and must NOT be edited manually.
 *
 * This file re-exports simple enum types from generated files and defines
 * usage-friendly interfaces with proper required/optional fields.
 *
 * Regenerate all generated types:
 *   pnpm exec openapi-typescript ../docs/specs/{module}.yaml -o app/types/generated/{module}.d.ts
 */

// ─── Enum / union types sourced from generated files ─────────────────────────
import type { components as EnrollmentsGen } from './generated/enrollments'
import type { components as ReportsGen } from './generated/reports'
import type { components as AchievementsGen } from './generated/achievements'

export type EnrollmentStatus = EnrollmentsGen['schemas']['EnrollmentStatus']
export type RiskLevel = ReportsGen['schemas']['RiskLevel']
export type AchievementStatus = AchievementsGen['schemas']['AchievementStatus']

// ─── Auth ────────────────────────────────────────────────────────────────────

export interface LoginRequest {
  email: string
  password: string
}

export interface ResetPasswordRequest {
  token: string
  email: string
  password: string
  password_confirmation: string
}

export interface UpdatePasswordRequest {
  current_password: string
  password: string
  password_confirmation: string
}

// ─── User ─────────────────────────────────────────────────────────────────────

export interface User {
  id: number
  name: string
  email: string
  role: 'admin' | 'coordinator' | 'teacher' | 'guardian' | 'student'
  institution_id?: number | null
  document_type?: string
  document_number?: string
  phone?: string
  address?: string
  birth_date?: string
  photo?: string
  is_active?: boolean
}

// ─── Institution ──────────────────────────────────────────────────────────────

export type EducationLevel = 'k12' | 'higher'

export interface AiQuota {
  used: number
  limit: number
  remaining: number
  resets_at: string | null
}

export interface GradingScale {
  superior: { min: number; max: number }
  high: { min: number; max: number }
  basic: { min: number; max: number }
  low: { min: number; max: number }
}

export interface Institution {
  id: number
  name: string
  nit?: string
  dane_code?: string
  logo?: string | null
  address?: string
  phone?: string
  email?: string
  city?: string
  department?: string
  rector_name?: string
  education_level?: EducationLevel
  grading_scale?: GradingScale
  ai_monthly_quota?: number
  ai_usage_count?: number
  ai_quota?: AiQuota
}

// ─── Academic Years ────────────────────────────────────────────────────────────

export interface AcademicYear {
  id: number
  institution_id: number
  name: string
  year?: number
  start_date: string
  end_date: string
  is_active: boolean
  periods?: Period[]
}

// ─── Periods ──────────────────────────────────────────────────────────────────

export interface Period {
  id: number
  academic_year_id: number
  name: string
  number: number
  weight: number
  start_date: string
  end_date: string
  status?: 'open' | 'closed'
  is_active: boolean
  is_closed: boolean
  academic_year?: AcademicYear
}

// ─── Grades (grados escolares) ─────────────────────────────────────────────────

export interface Grade {
  id: number
  institution_id: number
  name: string
  short_name: string
  level: 'preescolar' | 'primaria' | 'secundaria' | 'media'
  order: number
  education_level?: EducationLevel
  subjects?: Subject[]
  groups?: Group[]
  groups_count?: number
}

// ─── Groups ───────────────────────────────────────────────────────────────────

export interface Group {
  id: number
  grade_id: number
  academic_year_id: number
  director_id?: number | null
  name: string
  capacity?: number | null
  full_name?: string
  grade?: Grade
  academic_year?: AcademicYear
  director?: User  // director_id references users.id
  students?: Student[]
}

// ─── Areas ────────────────────────────────────────────────────────────────────

export interface Area {
  id: number
  institution_id: number
  name: string
  description?: string | null
  order?: number
  subjects?: Subject[]
}

// ─── Subjects ─────────────────────────────────────────────────────────────────

export interface Subject {
  id: number
  area_id: number
  grade_id: number
  name: string
  weekly_hours?: number
  intensity_hours?: number
  credits?: number | null
  order: number
  area?: Area
  grade?: Grade
}

// ─── Teachers ─────────────────────────────────────────────────────────────────

export interface Teacher {
  id: number
  user_id: number
  institution_id: number
  specialization?: string | null
  specialty?: string
  contract_type?: 'full_time' | 'part_time' | 'contractor'
  user?: User
  assignments?: TeacherAssignment[]
}

export interface TeacherAssignment {
  id: number
  teacher_id: number
  subject_id: number
  group_id: number
  academic_year_id: number
  teacher?: Teacher
  subject?: Subject
  group?: Group
  academic_year?: AcademicYear
}

// ─── Students ─────────────────────────────────────────────────────────────────

export interface Student {
  id: number
  user_id: number
  group_id?: number | null
  enrollment_code?: string | null
  enrollment_date: string
  status: 'active' | 'inactive' | 'withdrawn' | 'graduated'
  simat_code?: string | null
  stratum?: number | null
  health_insurer?: string | null
  ethnicity?: string | null
  disability_type?: string | null
  municipality?: string | null
  birth_municipality?: string | null
  user?: User
  group?: Group
  guardians?: Guardian[]
}

// ─── Guardians ────────────────────────────────────────────────────────────────

export interface Guardian {
  id: number
  user_id: number
  relationship?: string | null
  occupation?: string
  is_primary?: boolean
  user?: User
  students?: Student[]
}

// ─── Enrollments (Higher Education) ───────────────────────────────────────────

export interface Enrollment {
  id: number
  institution_id?: number
  student_id: number
  subject_id: number
  academic_year_id: number
  semester_number: number
  status: EnrollmentStatus
  final_grade?: number | null
  student?: Student
  subject?: Subject
  academic_year?: AcademicYear
}

// ─── Grade Records ────────────────────────────────────────────────────────────

export interface GradeRecord {
  id: number
  student_id: number
  subject_id: number
  period_id: number
  teacher_id: number
  grade?: number | null
  observations?: string | null
  recommendations?: string | null
  performance_level?: 'superior' | 'high' | 'basic' | 'low'
  performance_label?: string
  student?: Student
  subject?: Subject
  period?: Period
  teacher?: Teacher
}

export interface GradeRecordInput {
  student_id: number
  grade: number | null
  observations?: string
  recommendations?: string
}

export interface WorksheetGrade {
  subject_id: number
  subject_name: string
  area_name: string
  grade: number | null
  performance: string | null
}

export interface WorksheetStudent {
  student_id: number
  student_name: string
  grades: Record<number, number | null>
  average: number | null
  performance: string | null
  ranking: number | null
}

// ─── Grade Activities ─────────────────────────────────────────────────────────

export type ActivityType = 'quiz' | 'tarea' | 'participacion' | 'sustentacion' | 'examen' | 'proyecto' | 'otro'

export const ActivityTypeLabels: Record<ActivityType, string> = {
  quiz: 'Quiz',
  tarea: 'Tarea',
  participacion: 'Participación',
  sustentacion: 'Sustentación',
  examen: 'Examen',
  proyecto: 'Proyecto',
  otro: 'Otro',
}

export interface GradeActivity {
  id: number
  subject_id: number
  period_id: number
  group_id?: number
  teacher_id?: number
  title?: string
  name?: string
  type: ActivityType
  weight: number
  date?: string | null
  order?: number
}

export interface ActivityScore {
  student_id: number
  student_name: string
  document_number: string
  score: number | null
  observation?: string
}

// ─── Attendance ───────────────────────────────────────────────────────────────

export interface Attendance {
  id: number
  student_id: number
  group_id: number
  period_id: number
  date: string
  status: 'present' | 'absent' | 'late' | 'excused'
  observation?: string | null
  registered_by?: number
  student?: Student
  group?: Group
  period?: Period
}

export interface AttendanceInput {
  student_id: number
  status: 'present' | 'absent' | 'late' | 'excused'
  observation?: string
}

export interface AttendanceSummary {
  total_days: number
  present: number
  absent: number
  late: number
  excused: number
  attendance_percentage?: number
  percentage?: number
}

// ─── Announcements ────────────────────────────────────────────────────────────

export interface Announcement {
  id: number
  institution_id: number
  user_id?: number
  title: string
  content: string
  status?: 'draft' | 'published'
  is_published?: boolean
  target_roles?: string[] | null
  published_at?: string | null
  author?: User
  created_at?: string
}

// ─── Reports ──────────────────────────────────────────────────────────────────

export interface RiskScore {
  student_id: number
  student: {
    id: number
    name: string
    group: string
  }
  score: number
  level: RiskLevel
  signals: {
    failing_subjects: number
    total_subjects: number
    attendance_pct: number
    disciplinary_incidents: number
    pending_remedials: number
    grade_trend: number | null
  }
}

export interface ConsolidationStudent {
  student_id: number
  student_name: string
  grades: Record<number, number | null>
  average: number | null
  performance: string | null
  failing_count: number
  ranking: number | null
}

export interface AiAnalysisRecord {
  id: number
  period: { id: number; name: string }
  risk_level: 'high' | 'medium' | 'low'
  risk_score: number
  narrative: string
  recommendations: Array<{ subject: string; strategy: string; activity: string }> | string
  created_at: string
}

export interface AiAnalysisEvolution {
  score_delta: number
  level_changed: boolean
  previous_level: 'high' | 'medium' | 'low'
  current_level: 'high' | 'medium' | 'low'
}

export interface AiAnalysisHistoryResponse {
  analyses: AiAnalysisRecord[]
  evolution: AiAnalysisEvolution | null
}

// ─── SIEE — Achievements (Logros) ────────────────────────────────────────────

export interface Achievement {
  id: number
  subject_id: number
  period_id: number
  grade_id?: number
  code?: string
  title?: string
  description: string
  type: 'cognitive' | 'procedural' | 'attitudinal'
  order: number
  is_active?: boolean
  subject?: Subject
  period?: Period
  indicators?: AchievementIndicator[]
}

export interface AchievementIndicator {
  id: number
  achievement_id: number
  code?: string
  description: string
  order?: number
}

export interface StudentAchievement {
  id: number
  student_id: number
  achievement_id: number
  status: 'pending' | 'in_progress' | 'achieved' | 'not_achieved'
  observations?: string | null
  evaluated_by?: number
  evaluated_at?: string
  student?: Student
  achievement?: Achievement
  evaluator?: User
}

export const AchievementStatusLabels: Record<string, string> = {
  pending: 'Pendiente',
  in_progress: 'En Progreso',
  achieved: 'Alcanzado',
  not_achieved: 'No Alcanzado',
}

// ─── SIEE — Remedials (Nivelaciones) ─────────────────────────────────────────

export interface RemedialActivity {
  id: number
  subject_id: number
  period_id: number
  teacher_id?: number
  title: string
  description?: string | null
  instructions?: string
  type?: 'recovery' | 'reinforcement' | 'leveling' | string
  assigned_date?: string
  due_date: string
  max_grade?: number
  is_active?: boolean
  subject?: Subject
  period?: Period
  teacher?: Teacher
  student_remedials?: StudentRemedial[]
}

export interface StudentRemedial {
  id: number
  student_id: number
  remedial_activity_id: number
  status?: 'pending' | 'submitted' | 'graded' | 'excused'
  grade: number | null
  observations: string | null
  submission_notes?: string
  teacher_feedback?: string
  submitted_at?: string
  graded_at?: string
  graded_by?: number
  student?: Student
  remedial_activity?: RemedialActivity
}

export const RemedialStatusLabels: Record<string, string> = {
  pending: 'Pendiente',
  submitted: 'Entregado',
  graded: 'Calificado',
  excused: 'Excusado',
}

// ─── Convivencia Escolar (Ley 1620/2013) ──────────────────────────────────────

export type DisciplinaryRecordType = 'type1' | 'type2' | 'type3'
export type DisciplinaryRecordStatus = 'open' | 'in_process' | 'resolved' | 'archived' | 'escalated'
export type DisciplinaryCategory = 'verbal' | 'physical' | 'psychological' | 'cyberbullying' | 'other'

export interface DisciplinaryRecord {
  id: number
  institution_id: number
  student_id: number
  registered_by?: number
  reporter_id?: number
  period_id?: number
  type: DisciplinaryRecordType
  category: DisciplinaryCategory | string
  description: string
  date: string
  location?: string
  witnesses?: string
  action_taken?: string
  status: DisciplinaryRecordStatus
  resolution?: string | null
  resolution_date?: string | null
  resolved_at?: string
  notify_guardian?: boolean
  commitment?: string
  student?: Student
  reporter?: User
  period?: Period
}

export const DisciplinaryTypeLabels: Record<DisciplinaryRecordType, string> = {
  type1: 'Tipo 1 - Conflicto o desacuerdo',
  type2: 'Tipo 2 - Agresión o acoso escolar',
  type3: 'Tipo 3 - Vulneración de derechos',
}

export const DisciplinaryStatusLabels: Record<DisciplinaryRecordStatus, string> = {
  open: 'Abierto',
  in_process: 'En Proceso',
  resolved: 'Resuelto',
  archived: 'Archivado',
  escalated: 'Escalado',
}

export const DisciplinaryCategoryLabels: Record<DisciplinaryCategory, string> = {
  verbal: 'Verbal',
  physical: 'Físico',
  psychological: 'Psicológico',
  cyberbullying: 'Ciberbullying',
  other: 'Otro',
}

export const DisciplinaryStatusColors: Record<DisciplinaryRecordStatus, string> = {
  open: 'error',
  in_process: 'warning',
  resolved: 'success',
  archived: 'neutral',
  escalated: 'neutral',
}

// ─── Schedules (Horarios de Clase) ────────────────────────────────────────────

export interface Schedule {
  id: number
  day_of_week: number // 1=Mon … 5=Fri
  start_time: string // "08:00"
  end_time: string // "09:00"
  classroom?: string | null
  teacher_assignment_id: number
  assignment?: {
    id: number
    teacher?: Teacher
    subject?: Subject
    group?: Group
  }
}

export const DayLabels: Record<number, string> = {
  1: 'Lunes',
  2: 'Martes',
  3: 'Miércoles',
  4: 'Jueves',
  5: 'Viernes',
}

export const DayColors: Record<number, string> = {
  1: 'blue',
  2: 'green',
  3: 'purple',
  4: 'orange',
  5: 'red',
}

// ─── Tasks (Tareas) ───────────────────────────────────────────────────────────

export interface Task {
  id: number
  institution_id: number
  teacher_id?: number
  group_id: number
  subject_id?: number | null
  title: string
  description?: string | null
  instructions?: string
  attachment_path?: string | null
  attachment_name?: string
  due_date: string
  is_published?: boolean
  teacher?: Teacher
  group?: Group
  subject?: Subject
  student_tasks?: StudentTask[]
  student_tasks_count?: number
  created_at: string
  updated_at: string
}

export interface StudentTask {
  id: number
  task_id: number
  student_id: number
  status?: 'pending' | 'submitted' | 'reviewed'
  submission_path?: string | null
  submission_name?: string
  submitted_at?: string | null
  grade?: number | null
  feedback?: string | null
  reviewed_at?: string | null
  task?: Task
  student?: Student
  created_at?: string
  updated_at?: string
}

// ─── Promotion Record ─────────────────────────────────────────────────────────

export interface PromotionRecord {
  id: number
  student_id: number
  academic_year_id: number
  decision: 'promoted' | 'retained' | 'early_promoted' | 'conditional'
  observations?: string
  failed_subjects?: number[]
  failed_count: number
  final_average?: number
  performance_level?: string
  decided_by?: number
  decided_at?: string
  student?: Student
  academic_year?: AcademicYear
}

// ─── Student Portal (Higher Education) ───────────────────────────────────────

export interface StudentPortalMe {
  student: Student
  active_year?: AcademicYear | null
  current_enrollments_count: number
  current_credits: number
}

export interface KardexYear {
  academic_year: { id: number; name: string }
  enrollments: Enrollment[]
  total_credits: number
  approved_credits: number
  papa: number | null
}

// ─── API Response helpers ──────────────────────────────────────────────────────

export interface ApiResponse<T> {
  data: T
  message?: string
}

export interface PaginatedResponse<T> {
  data: T[]
  meta: {
    current_page: number
    last_page: number
    per_page: number
    total: number
  }
}

// ─── Performance Level ────────────────────────────────────────────────────────

export type PerformanceLevel = 'superior' | 'high' | 'basic' | 'low'

export const PerformanceLevelLabels: Record<PerformanceLevel, string> = {
  superior: 'Desempeño Superior',
  high: 'Desempeño Alto',
  basic: 'Desempeño Básico',
  low: 'Desempeño Bajo',
}

export const PerformanceLevelColors: Record<PerformanceLevel, string> = {
  superior: 'success',
  high: 'info',
  basic: 'warning',
  low: 'error',
}
