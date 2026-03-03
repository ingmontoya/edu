# Aula360 - Sistema de Gestión Académica

## Descripción General

**Aula360** es una plataforma integral de gestión académica diseñada para instituciones educativas colombianas. Permite administrar estudiantes, docentes, calificaciones, asistencia, boletines y el Sistema Institucional de Evaluación de Estudiantes (SIEE).

---

## Stack Tecnológico

| Componente | Tecnología |
|------------|------------|
| **Backend** | Laravel 12.0 (PHP 8.2+) |
| **Frontend** | Nuxt 3 + Vue 3 + TypeScript |
| **Base de Datos** | SQLite (desarrollo) / MySQL / PostgreSQL |
| **Autenticación** | Laravel Sanctum (API tokens) |
| **Reportes PDF** | DOMPDF |
| **UI** | Nuxt UI + Tailwind CSS |

---

## Arquitectura

```
edu/
├── app/                    # Backend Laravel
│   ├── Http/Controllers/   # Controladores API
│   ├── Models/             # Modelos Eloquent
│   ├── Services/           # Servicios de negocio
│   └── Enums/              # Enumeraciones
├── frontend/               # Frontend Nuxt
│   └── app/
│       ├── pages/          # Páginas/Rutas
│       ├── components/     # Componentes Vue
│       ├── composables/    # Composables
│       ├── stores/         # Stores Pinia
│       └── types/          # Tipos TypeScript
├── database/
│   ├── migrations/         # Migraciones
│   └── seeders/            # Seeders
└── routes/
    └── api.php             # Rutas API
```

---

## Roles de Usuario

| Rol | Descripción | Permisos |
|-----|-------------|----------|
| **admin** | Administrador | Acceso total, gestión de institución |
| **coordinator** | Coordinador Académico | Gestión académica completa |
| **teacher** | Docente | Calificaciones, asistencia, logros de sus grupos |
| **guardian** | Acudiente | Portal limitado: ver hijos, notas, boletines |

---

## Módulos del Sistema

### 1. Autenticación

**Funcionalidades:**
- Inicio de sesión con email/contraseña
- Cierre de sesión seguro
- Actualización de perfil
- Cambio de contraseña

**Endpoints:**
| Método | Ruta | Descripción |
|--------|------|-------------|
| POST | `/api/auth/login` | Iniciar sesión |
| POST | `/api/auth/logout` | Cerrar sesión |
| GET | `/api/auth/me` | Obtener usuario actual |
| PUT | `/api/auth/profile` | Actualizar perfil |
| PUT | `/api/auth/password` | Cambiar contraseña |

---

### 2. Gestión Académica

#### 2.1 Años Académicos

Administración del calendario escolar anual.

**Funcionalidades:**
- Crear/editar años académicos
- Activar año académico vigente
- Definir fechas de inicio y fin

**Endpoints:**
| Método | Ruta | Descripción |
|--------|------|-------------|
| GET | `/api/academic-years` | Listar años |
| POST | `/api/academic-years` | Crear año |
| PUT | `/api/academic-years/{id}` | Actualizar año |
| POST | `/api/academic-years/{id}/activate` | Activar año |

#### 2.2 Períodos

División del año en períodos/trimestres con peso porcentual.

**Funcionalidades:**
- Crear períodos con pesos (ej: 25% cada uno)
- Cerrar períodos (impide modificaciones)
- Reabrir períodos

**Endpoints:**
| Método | Ruta | Descripción |
|--------|------|-------------|
| GET | `/api/periods` | Listar períodos |
| POST | `/api/periods` | Crear período |
| POST | `/api/periods/{id}/close` | Cerrar período |
| POST | `/api/periods/{id}/open` | Reabrir período |

#### 2.3 Grados

Niveles educativos (1°, 2°, 3°, etc.)

**Niveles:**
- Preescolar
- Primaria
- Secundaria
- Media

**Endpoints:**
| Método | Ruta | Descripción |
|--------|------|-------------|
| GET | `/api/grades` | Listar grados |
| POST | `/api/grades` | Crear grado |
| PUT | `/api/grades/{id}` | Actualizar grado |
| DELETE | `/api/grades/{id}` | Eliminar grado |

#### 2.4 Grupos

Secciones dentro de cada grado (1°A, 1°B, etc.)

**Funcionalidades:**
- Crear grupos con capacidad
- Asignar director de grupo
- Ver estudiantes del grupo

**Endpoints:**
| Método | Ruta | Descripción |
|--------|------|-------------|
| GET | `/api/groups` | Listar grupos |
| POST | `/api/groups` | Crear grupo |
| POST | `/api/groups/{id}/assign-director` | Asignar director |
| GET | `/api/groups/{id}/students` | Estudiantes del grupo |

#### 2.5 Áreas y Asignaturas

Organización curricular por áreas de conocimiento.

**Áreas típicas:**
- Lengua Castellana
- Matemáticas
- Ciencias Naturales
- Ciencias Sociales
- Educación Física
- Artes
- Tecnología

**Endpoints:**
| Método | Ruta | Descripción |
|--------|------|-------------|
| GET | `/api/areas` | Listar áreas |
| POST | `/api/areas` | Crear área |
| GET | `/api/subjects` | Listar asignaturas |
| POST | `/api/subjects` | Crear asignatura |

---

### 3. Gestión de Personas

#### 3.1 Estudiantes

**Estados:**
- `active` - Activo
- `inactive` - Inactivo
- `withdrawn` - Retirado
- `graduated` - Graduado

**Funcionalidades:**
- Registro de estudiantes
- Asignación a grupos
- Seguimiento académico
- Historial de notas y asistencia

**Endpoints:**
| Método | Ruta | Descripción |
|--------|------|-------------|
| GET | `/api/students` | Listar (paginado, filtrable) |
| POST | `/api/students` | Crear estudiante |
| GET | `/api/students/{id}` | Ver detalles |
| PUT | `/api/students/{id}` | Actualizar |
| GET | `/api/students/{id}/grades` | Notas del estudiante |
| GET | `/api/students/{id}/attendance` | Asistencia |
| POST | `/api/students/{id}/assign-guardian` | Asignar acudiente |

#### 3.2 Docentes

**Tipos de contrato:**
- Tiempo completo
- Medio tiempo
- Contratista

**Funcionalidades:**
- Registro de docentes
- Asignación a grupos/asignaturas
- Seguimiento de carga académica

**Endpoints:**
| Método | Ruta | Descripción |
|--------|------|-------------|
| GET | `/api/teachers` | Listar docentes |
| POST | `/api/teachers` | Crear docente |
| GET | `/api/teachers/{id}/assignments` | Ver asignaciones |
| POST | `/api/teachers/{id}/assign` | Asignar a grupo/materia |

#### 3.3 Acudientes

**Funcionalidades:**
- Registro de acudientes
- Vinculación con estudiantes
- Acceso al portal de padres

**Endpoints:**
| Método | Ruta | Descripción |
|--------|------|-------------|
| GET | `/api/guardians` | Listar acudientes |
| POST | `/api/guardians` | Crear acudiente |
| GET | `/api/guardians/{id}/students` | Estudiantes a cargo |

---

### 4. Calificaciones

#### Sistema de Evaluación (Escala Colombiana)

| Rango | Nivel | Color |
|-------|-------|-------|
| 1.0 - 2.9 | Desempeño Bajo | Rojo |
| 3.0 - 3.9 | Desempeño Básico | Amarillo |
| 4.0 - 4.5 | Desempeño Alto | Azul |
| 4.6 - 5.0 | Desempeño Superior | Verde |

**Nota aprobatoria:** 3.0

#### 4.1 Registro de Notas

**Funcionalidades:**
- Registro individual y masivo
- Observaciones por estudiante
- Cálculo automático de promedios
- Validación de escala

**Endpoints:**
| Método | Ruta | Descripción |
|--------|------|-------------|
| GET | `/api/grade-records` | Obtener notas |
| POST | `/api/grade-records/bulk` | Guardar múltiples notas |
| PUT | `/api/grade-records/{id}` | Actualizar nota |

#### 4.2 Planilla de Notas

**Funcionalidades:**
- Vista consolidada: estudiantes vs asignaturas
- Cálculo de promedios por período
- Ranking automático
- Identificación de estudiantes en riesgo

**Endpoints:**
| Método | Ruta | Descripción |
|--------|------|-------------|
| GET | `/api/grade-records/worksheet` | Planilla completa |

---

### 5. Asistencia

#### Estados de Asistencia

| Estado | Código | Color |
|--------|--------|-------|
| Presente | `present` | Verde |
| Ausente | `absent` | Rojo |
| Tardanza | `late` | Amarillo |
| Excusa | `excused` | Azul |

**Funcionalidades:**
- Registro diario por grupo
- Observaciones por registro
- Cálculo de porcentaje de asistencia
- Reportes consolidados

**Endpoints:**
| Método | Ruta | Descripción |
|--------|------|-------------|
| GET | `/api/attendance` | Obtener asistencia |
| POST | `/api/attendance/bulk` | Registrar múltiples |
| PUT | `/api/attendance/{id}` | Actualizar registro |
| GET | `/api/attendance/daily/{group}` | Asistencia del día |
| GET | `/api/attendance/report` | Reporte consolidado |

---

### 6. Reportes y Boletines

#### 6.1 Boletines PDF

**Funcionalidades:**
- Generación individual por estudiante
- Generación masiva por grupo
- Incluye: notas, observaciones, promedio, ranking

**Endpoints:**
| Método | Ruta | Descripción |
|--------|------|-------------|
| GET | `/api/report-cards/student/{id}/period/{id}` | Datos del boletín |
| GET | `/api/report-cards/student/{id}/period/{id}/pdf` | Descargar PDF |
| GET | `/api/report-cards/group/{id}/period/{id}/pdf` | Boletines en lote |

#### 6.2 Reportes Analíticos

**Tipos de reportes:**
- Consolidado de notas por grupo
- Estudiantes en riesgo académico
- Resumen de asistencia

**Endpoints:**
| Método | Ruta | Descripción |
|--------|------|-------------|
| GET | `/api/reports/consolidation` | Consolidado de grupo |
| GET | `/api/reports/failing-students` | Estudiantes en riesgo |
| GET | `/api/reports/attendance-summary` | Resumen asistencia |

---

### 7. SIEE - Sistema Institucional de Evaluación

#### 7.1 Logros

Evaluación por competencias según el SIEE colombiano.

**Tipos de Logros:**
| Tipo | Descripción |
|------|-------------|
| `cognitive` | Cognitivo (conocimiento) |
| `procedural` | Procedimental (habilidad) |
| `attitudinal` | Actitudinal (comportamiento) |

**Estados de Evaluación:**
| Estado | Descripción |
|--------|-------------|
| `pending` | Pendiente |
| `in_progress` | En progreso |
| `achieved` | Alcanzado |
| `not_achieved` | No alcanzado |

**Funcionalidades:**
- Crear logros por asignatura/período
- Agregar indicadores de logro
- Evaluar estudiantes
- Copiar logros entre períodos
- Importar desde CSV

**Endpoints:**
| Método | Ruta | Descripción |
|--------|------|-------------|
| GET | `/api/achievements` | Listar logros |
| POST | `/api/achievements` | Crear logro |
| POST | `/api/achievements/{id}/indicators` | Agregar indicador |
| POST | `/api/achievements/record` | Evaluar estudiante |
| POST | `/api/achievements/bulk-record` | Evaluar múltiples |
| POST | `/api/achievements/copy` | Copiar de otro período |
| POST | `/api/achievements/import` | Importar CSV |

#### 7.2 Actividades de Nivelación

Recuperación para estudiantes con bajo desempeño.

**Tipos:**
| Tipo | Descripción |
|------|-------------|
| `recovery` | Recuperación |
| `reinforcement` | Refuerzo |
| `leveling` | Nivelación |

**Funcionalidades:**
- Crear actividades por asignatura
- Asignación manual o automática
- Calificación con nota máxima configurable
- Opción de actualizar nota original

**Endpoints:**
| Método | Ruta | Descripción |
|--------|------|-------------|
| GET | `/api/remedials` | Listar actividades |
| POST | `/api/remedials` | Crear actividad |
| POST | `/api/remedials/{id}/assign-students` | Asignar estudiantes |
| POST | `/api/remedials/{id}/auto-assign` | Auto-asignar bajo desempeño |
| POST | `/api/remedials/{id}/bulk-grade` | Calificar múltiples |
| GET | `/api/remedials/students-needing` | Estudiantes que necesitan |

---

### 8. Portal del Acudiente

Acceso restringido para padres/acudientes.

**Funcionalidades:**
- Ver listado de hijos
- Consultar notas por período
- Ver asistencia
- Descargar boletines
- Ver anuncios institucionales

**Endpoints:**
| Método | Ruta | Descripción |
|--------|------|-------------|
| GET | `/api/guardian/students` | Mis hijos |
| GET | `/api/guardian/students/{id}` | Detalles del hijo |
| GET | `/api/guardian/students/{id}/grades` | Notas |
| GET | `/api/guardian/students/{id}/attendance` | Asistencia |
| GET | `/api/guardian/students/{id}/report-card/{period}/pdf` | Boletín PDF |
| GET | `/api/guardian/announcements` | Anuncios |

---

### 9. Configuración Institucional

**Funcionalidades:**
- Datos de la institución (nombre, NIT, DANE)
- Logo institucional
- Información de contacto
- Nombre del rector

**Endpoints:**
| Método | Ruta | Descripción |
|--------|------|-------------|
| GET | `/api/institution` | Datos de institución |
| PUT | `/api/institution` | Actualizar datos |
| POST | `/api/institution/logo` | Cargar logo |

---

## Páginas del Frontend

### Autenticación
| Ruta | Descripción |
|------|-------------|
| `/login` | Inicio de sesión |

### Dashboard
| Ruta | Descripción |
|------|-------------|
| `/dashboard` | Panel principal con estadísticas |

### Gestión Académica
| Ruta | Descripción |
|------|-------------|
| `/academic/years` | Años académicos |
| `/academic/periods` | Períodos |
| `/academic/grades` | Grados |
| `/academic/groups` | Grupos |
| `/academic/subjects` | Asignaturas |

### Estudiantes
| Ruta | Descripción |
|------|-------------|
| `/students` | Listado de estudiantes |
| `/students/new` | Nuevo estudiante |
| `/students/[id]` | Detalles del estudiante |
| `/students/[id]/edit` | Editar estudiante |

### Docentes
| Ruta | Descripción |
|------|-------------|
| `/teachers` | Listado de docentes |

### Calificaciones
| Ruta | Descripción |
|------|-------------|
| `/grades/record` | Registrar notas |
| `/grades/worksheet` | Planilla de notas |

### Asistencia
| Ruta | Descripción |
|------|-------------|
| `/attendance` | Registro de asistencia |

### Reportes
| Ruta | Descripción |
|------|-------------|
| `/reports/cards` | Boletines |
| `/reports/consolidation` | Consolidado |
| `/reports/failing` | Estudiantes en riesgo |

### SIEE
| Ruta | Descripción |
|------|-------------|
| `/siee/achievements` | Gestión de logros |
| `/siee/achievements/record` | Evaluar logros |
| `/siee/remedials` | Actividades de nivelación |
| `/siee/remedials/new` | Nueva actividad |
| `/siee/remedials/[id]/grade` | Calificar actividad |

### Portal Acudiente
| Ruta | Descripción |
|------|-------------|
| `/guardian` | Mis hijos |
| `/guardian/student/[id]` | Detalles del hijo |

### Configuración
| Ruta | Descripción |
|------|-------------|
| `/settings` | Perfil |
| `/settings/security` | Seguridad |
| `/institution/settings` | Institución (admin) |

---

## Modelo de Datos

### Diagrama Entidad-Relación

```
Institution (1) ──────────< User (N)
     │                        │
     │                        ├── Teacher (1:1)
     │                        ├── Student (1:1)
     │                        └── Guardian (1:1)
     │
     ├──< AcademicYear ──< Period
     │
     ├──< Grade ──< Group ──< Student
     │      │
     │      └──< Subject ──< GradeRecord
     │             │
     │             ├──< Achievement ──< AchievementIndicator
     │             │         │
     │             │         └──< StudentAchievement
     │             │
     │             └──< RemedialActivity ──< StudentRemedial
     │
     └──< Area ──< Subject

Student (N) ────────< (M) Guardian (tabla: student_guardian)

Attendance: Student + Group + Period + Date
GradeRecord: Student + Subject + Period + Teacher
```

### Tablas Principales

| Tabla | Descripción |
|-------|-------------|
| `users` | Usuarios del sistema |
| `institutions` | Instituciones educativas |
| `academic_years` | Años académicos |
| `periods` | Períodos/trimestres |
| `grades` | Grados escolares |
| `groups` | Grupos/secciones |
| `areas` | Áreas de conocimiento |
| `subjects` | Asignaturas |
| `students` | Estudiantes |
| `teachers` | Docentes |
| `guardians` | Acudientes |
| `teacher_assignments` | Asignaciones docente-grupo-materia |
| `grade_records` | Calificaciones |
| `attendances` | Registros de asistencia |
| `achievements` | Logros SIEE |
| `achievement_indicators` | Indicadores de logro |
| `student_achievements` | Evaluación de logros |
| `remedial_activities` | Actividades de nivelación |
| `student_remedials` | Asignación de remediaciones |
| `announcements` | Anuncios institucionales |

---

## Características Especiales

### Multi-Tenancy
- Soporte para múltiples instituciones
- Filtrado automático de datos por institución
- Aislamiento completo de datos

### Sistema de Calificaciones Ponderado
- Cada período tiene un peso porcentual
- Cálculo automático de promedio final
- Ranking de estudiantes

### Generación de PDFs
- Boletines individuales y masivos
- Diseño institucional personalizable
- Descarga automática

### Portal de Padres
- Acceso seguro y limitado
- Consulta de información académica
- Descarga de boletines

---

## Instalación

### Requisitos
- PHP 8.2+
- Composer
- Node.js 18+
- SQLite / MySQL / PostgreSQL

### Backend
```bash
# Instalar dependencias
composer install

# Configurar entorno
cp .env.example .env
php artisan key:generate

# Migrar base de datos
php artisan migrate

# Poblar datos de prueba
php artisan db:seed

# Iniciar servidor
php artisan serve
```

### Frontend
```bash
cd frontend

# Instalar dependencias
npm install

# Iniciar desarrollo
npm run dev
```

---

## Credenciales de Prueba

| Rol | Email | Contraseña |
|-----|-------|------------|
| Admin | admin@aula360.com | password |
| Coordinador | coordinator@aula360.com | password |
| Docente | teacher@aula360.com | password |
| Acudiente | guardian@aula360.com | password |

---

## Licencia

Este proyecto es software propietario. Todos los derechos reservados.

---

## Soporte

Para soporte técnico o consultas, contactar al equipo de desarrollo.
