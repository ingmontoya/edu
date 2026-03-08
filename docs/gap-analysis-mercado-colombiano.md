# Gap Analysis: Mercado Colombiano de Software Educativo

**Plataforma:** Aula360
**Fecha:** Marzo 2026 (actualizado post-sprint IES)
**Propósito:** Identificar brechas entre la oferta actual del mercado y los requisitos del sector educativo colombiano

---

## 1. Panorama Competitivo

### Competidores Principales en Colombia

| Plataforma | Segmento | Fortalezas | Debilidades |
|-----------|---------|-----------|-------------|
| **Caseware / SGA SINU** | IES (30+ universidades, 300k+ estudiantes) | 24 años experiencia, ISO 27001, ERP especializado IES, nómina docente, integración Oracle | Solo IES, sin colegios K-12, precio enterprise, no SaaS, interfaz legacy |
| **Escuelafácil** | K-12 | Presencia en LATAM, funcionalidad amplia | Interfaz antigua, alto costo |
| **Sigeweb** | K-12 | Integración SIMAT nativa | Solo para colegios grandes, difícil de usar |
| **Master2000** | K-12 | Muy completo, notas, asistencia | Costoso, instalación local, no SaaS |
| **Axioma Escolar** | K-12 | Adaptado a MEN, Decreto 1290 | Costoso, soporte lento |
| **iColaborar** | K-12 | Comunicación familia-escuela | No gestión académica completa |
| **Sapiens** | K-12 + IES | ERP educativo completo | Precio enterprise, curva de aprendizaje alta |

### Posicionamiento de Aula360

Aula360 apunta a **dos segmentos complementarios**:

**Segmento A — K-12:** Colegios medianos privados y concesionados (200–1500 estudiantes)
- Cumplimiento normativo colombiano (MEN, SIMAT, Decreto 1290, Ley 1620)
- Precio accesible (SaaS mensual, no licencia perpetua)
- Implementación rápida (< 1 semana)
- Interfaz moderna y usable por docentes no técnicos

**Segmento B — IES:** Instituciones de Educación Superior pequeñas y medianas (tecnológicas, técnicas, universidades regionales)
- Alternativa moderna y asequible a Caseware/Sapiens (precio enterprise)
- PAPA ponderado por créditos, matrículas por semestre, kardex académico
- Portal propio del estudiante con historial y promedio acumulado
- Mismo stack SaaS — sin instalación, sin servidores propios

---

## 2. Tabla de Brechas (Gap Analysis)

### 2.0 Comparativo Directo: Aula360 IES vs Caseware SGA SINU

| Funcionalidad | Aula360 (IES) | Caseware SGA SINU | Notas |
|--------------|:---:|:---:|-------|
| **Gestión académica** | | | |
| Matrículas por semestre | ✅ | ✅ | Aula360: individual + masiva en un request |
| PAPA ponderado por créditos | ✅ | ✅ | `Σ(nota×créditos)/Σ(créditos)` |
| Kardex académico por año | ✅ | ✅ | Con créditos aprobados y PAPA por período |
| Cálculo automático nota final | ✅ | ✅ | Aula360: desde GradeRecords con pesos por corte |
| Portal del estudiante | ✅ | ✅ | Aula360: rol `student` propio, no hack de guardian |
| Homologaciones | ❌ | ✅ | Gap — P2 |
| Horario de clases | ❌ | ✅ | Gap — P1 |
| Oferta académica / syllabus | ❌ | ✅ | Gap — P2 |
| **Finanzas** | | | |
| Cartera estudiantil / pagos | ❌ | ✅ | Out of scope MVP |
| Facturación electrónica | ❌ | ✅ | Out of scope MVP |
| **RRHH / Nómina** | | | |
| Nómina docente (UGPP) | ❌ | ✅ | Out of scope |
| **Tecnología** | | | |
| SaaS / cloud nativo | ✅ | ❌ | Caseware requiere instalación on-premise |
| API REST moderna | ✅ | ❌ | Caseware: integración via Oracle legacy |
| Precio accesible | ✅ | ❌ | Caseware: enterprise sin precio público |
| Implementación < 1 semana | ✅ | ❌ | Caseware: meses de implementación |
| Interfaz moderna | ✅ | ❌ | Caseware: interfaz legacy ERP |
| ISO 27001 | ❌ | ✅ | Gap de certificación — Q3 2026 |
| Soporte dual K-12 + IES | ✅ | ❌ | Aula360: misma plataforma, modo por institución |

**Conclusión:** Aula360 cubre el 70% funcional académico de Caseware con una fracción del costo y en SaaS. El gap principal es finanzas/nómina (out of scope) y certificaciones.

---

### 2.1 Requisitos Normativos

| Requisito | Estado Actual | Prioridad | Notas |
|-----------|--------------|-----------|-------|
| Exportación SIMAT (formato MEN) | ✅ Implementado | — | GET /api/students/simat-export → CSV |
| Código DANE institución completo | ⚠️ Parcial | P1 | Falta validación formato 12 dígitos |
| Decreto 1290 - Escala de valoración | ✅ Implementado | — | Superior/Alto/Básico/Bajo |
| Decreto 1290 - SIEE institucional | ✅ Implementado | — | Logros, indicadores, nivelaciones |
| Convivencia Escolar (Ley 1620/2013) | ✅ Implementado | — | DisciplinaryRecord, tipos 1/2/3, historial |
| Manual de convivencia digital | ❌ Ausente | P1 | — |
| Decreto 366 - Inclusión | ❌ Ausente | P1 | Discapacidades, ajustes |
| Informe ICFES por grado | ❌ Ausente | P2 | — |

### 2.2 Funcionalidades Académicas

| Funcionalidad | Estado | Prioridad | Notas |
|--------------|--------|-----------|-------|
| Boletines PDF | ✅ Implementado | — | |
| Constancia de matrícula PDF | ✅ Implementado | — | CertificatePdfService |
| Constancia de notas PDF | ✅ Implementado | — | CertificatePdfService |
| Paz y salvo académico | ❌ Ausente | P1 | |
| Certificado de graduación | ❌ Ausente | P1 | |
| Planilla de calificaciones imprimible | ✅ Implementado | — | |
| Horarios de clase | ❌ Ausente | P1 | |
| Tareas y evidencias de entrega | ❌ Ausente | **P1** | Ver §P1-E más abajo |
| Seguimiento de proyectos transversales | ❌ Ausente | P2 | |

### 2.3 Comunicación y Portal

| Funcionalidad | Estado | Prioridad | Notas |
|--------------|--------|-----------|-------|
| Portal acudientes | ✅ Implementado | — | Ver notas, asistencia |
| Portal estudiante IES | ✅ Implementado | — | Rol `student`, dashboard + kardex con PAPA |
| Recuperación de contraseña | ✅ Implementado | — | Flujo forgot/reset con email |
| Notificaciones push | ❌ Ausente | P1 | App móvil futura |
| Mensajería interna | ❌ Ausente | P1 | |
| Comunicados a grupos/grados | ✅ Parcial | P1 | Falta targeting por grupo |
| WhatsApp integration | ❌ Ausente | P2 | |

### 2.4 Administración

| Funcionalidad | Estado | Prioridad | Notas |
|--------------|--------|-----------|-------|
| Datos socioeconómicos estudiante | ✅ Implementado | — | Estrato, EPS, municipio (migración SIMAT) |
| Etnia y discapacidad | ✅ Implementado | — | Campos DANE en modelo Student |
| Matrículas IES (individual + masiva) | ✅ Implementado | — | POST /enrollments/bulk, semestre completo |
| Cálculo nota final automático | ✅ Implementado | — | POST /enrollments/calculate-finals |
| PAPA ponderado por créditos | ✅ Implementado | — | Σ(nota×créditos)/Σ(créditos), backend + frontend |
| Multi-sede | ❌ Ausente | P1 | Colegios con varias sedes |
| Calendario académico configurable | ❌ Ausente | P1 | |
| Inventario / biblioteca | ❌ Ausente | P3 | Out of scope MVP |
| Nómina docentes | ❌ Ausente | P3 | Out of scope |

---

## 3. Prioridades P0 — Estado Actual

### P0 K-12 — ✅ Todos completados (Q1 2026)

| Item | Estado | Detalle |
|------|:---:|---------|
| P0-A: Exportación SIMAT + Campos DANE | ✅ | `GET /api/students/simat-export` → CSV MEN; 7 campos nuevos en Student |
| P0-B: Convivencia Escolar (Ley 1620/2013) | ✅ | DisciplinaryRecord CRUD, tipos 1/2/3, historial por estudiante |
| P0-C: Constancias y Certificados PDF | ✅ | CertificatePdfService, matrícula + notas con membrete |
| P0-D: Recuperación de contraseña | ✅ | Flujo forgot/reset con email, páginas frontend |

### P0 IES — ✅ Todos completados (Marzo 2026)

| Item | Estado | Detalle |
|------|:---:|---------|
| P0-IES-1: PAPA ponderado por créditos | ✅ | `Σ(nota×créditos)/Σ(créditos)` en kardex frontend + StudentPortalController |
| P0-IES-2: Cálculo automático nota final | ✅ | `POST /enrollments/calculate-finals` — pesos por corte → final_grade + status |
| P0-IES-3: Portal del estudiante (rol propio) | ✅ | Rol `student`, dashboard + kardex, sidebar propio, redirect en login |
| P0-IES-4: Matrícula masiva por semestre | ✅ | `POST /enrollments/bulk` — semestre completo en un request, skip duplicados |

---

## 4. Prioridades P1 (Diferenciadores Competitivos)

| Item | Descripción | Impacto |
|------|-----------|---------|
| Horarios de clase | Gestión de horarios por grupo/docente | Alto |
| **Tareas con evidencias** | Asignación de tareas a grupos, entrega de archivos por estudiante, vista acudiente | **Alto** |
| Portal móvil PWA | App móvil para acudientes | Alto |
| Multi-sede | Gestión de colegios con varias sedes | Medio |
| Comunicados targetizados | Mensajes a grado/grupo específico | Medio |
| Paz y salvo | Documento oficial fin de año | Medio |
| Inclusión (Dec. 366) | Adaptaciones curriculares por discapacidad | Legal |

### P1-E: Módulo de Tareas con Evidencias de Entrega

**Problema que resuelve:** Los docentes no tienen forma de asignar trabajos al grupo, compartir instrucciones escritas o un PDF de enunciado, ni verificar quién entregó evidencia de cumplimiento. Los acudientes tampoco pueden consultar si su hijo tiene tareas pendientes.

**Actores y alcance:**

| Actor | Funcionalidad |
|-------|--------------|
| **Docente** | Crear tarea (título, instrucciones, fecha límite, PDF opcional, grupo + asignatura opcional) · Ver listado de entregas por estudiante · Marcar entrega como revisada |
| **Acudiente** | Vista de solo lectura de las tareas de su hijo y el estado de entrega (pendiente / entregado / revisado) |
| **Estudiante** | Backend completamente implementado; UI diferida hasta que exista el rol de login de estudiante |

**Regla de asignación:** Al crear una tarea, se auto-asigna a todos los estudiantes activos del grupo seleccionado.

**Modelo de datos propuesto:**

```
tasks
  id, group_id, subject_id (nullable), teacher_id, institution_id
  title, instructions (text), file_path (nullable, PDF)
  due_date, is_active
  timestamps

student_tasks
  id, task_id, student_id
  status: pending | submitted | reviewed
  submission_file_path (nullable)
  submission_notes (text, nullable)
  reviewed_at (nullable), reviewed_by (nullable)
  submitted_at (nullable)
  timestamps
```

**Endpoints backend:**
- `POST /api/tasks` — docente crea tarea, auto-asigna al grupo
- `GET /api/tasks?group_id=X` — lista de tareas del docente
- `GET /api/tasks/{task}/submissions` — docente ve entregas por estudiante
- `PATCH /api/tasks/{task}/submissions/{studentTask}` — docente marca como revisado
- `POST /api/tasks/{task}/submit` — estudiante sube evidencia (backend listo, UI diferida)
- `GET /api/guardian/children/{student}/tasks` — acudiente ve tareas de su hijo

**Frontend implementado (alcance actual):**
- `/dashboard/tasks` — docente: crear y gestionar tareas
- Portal acudiente: sección tareas en `/guardian/child/{id}`

**Frontend diferido:**
- UI de entrega para el estudiante — hasta que exista rol de login de estudiante

---

## 5. Inteligencia Artificial — Inspirado en Panorama Education

### Referente: Panorama Education

[Panorama Education](https://www.panoramaed.com) es la plataforma líder en analítica estudiantil en EE.UU., usada por más de 2,000 distritos escolares. Su propuesta central es convertir los datos académicos y socioemocionales en **señales de alerta temprana** que permiten a docentes y coordinadores actuar antes de que un estudiante repruebe o abandone.

Lo que los hace únicos:
- **Índice de riesgo predictivo** por estudiante (combina notas, asistencia, comportamiento y bienestar)
- **Encuestas SEL** (Socioemotional Learning) integradas con los datos académicos
- **Dashboards por rol** — el rector ve el colegio, el coordinador ve el grado, el docente ve su grupo
- **Alertas automáticas** cuando un estudiante cruza umbrales de riesgo

### Oportunidad para Aula360

Aula360 ya captura todos los datos necesarios para construir esto: notas, asistencia, registros de convivencia, actividades de nivelación pendientes. La oportunidad es **conectar esos puntos con IA** para generar un perfil de riesgo accionable.

### 2.5 Analítica e IA (Gap)

| Funcionalidad | Estado | Prioridad | Inspiración |
|--------------|--------|-----------|-------------|
| Índice de riesgo por estudiante | ❌ Ausente | **P1** | Panorama Education |
| Alertas automáticas al docente | ❌ Ausente | **P1** | Panorama Education |
| Dashboard de bienestar por grupo | ❌ Ausente | **P1** | Panorama Education |
| Tendencias de notas (últimos 3 períodos) | ❌ Ausente | **P1** | Análisis propio |
| Predicción de reprobación de año | ❌ Ausente | P2 | Panorama Education |
| Encuestas de clima escolar | ❌ Ausente | P2 | Panorama Education SEL |
| Recomendaciones automáticas de nivelación | ❌ Ausente | P2 | IA generativa |
| Comparativa entre grupos/grados | ❌ Ausente | P2 | Análisis propio |

### Propuesta Técnica: Índice de Riesgo Estudiantil

Con los datos que ya tiene Aula360, se puede calcular un **Risk Score (0–100)** por estudiante usando una fórmula ponderada:

| Señal | Peso | Fuente |
|-------|------|--------|
| Asignaturas reprobadas en período actual | 35% | `grade_records` |
| Porcentaje de asistencia < 80% | 25% | `attendances` |
| Registros de convivencia tipo 2/3 (últimos 30 días) | 20% | `disciplinary_records` |
| Actividades de nivelación pendientes | 15% | `student_remedials` |
| Tendencia negativa vs período anterior | 5% | `grade_records` histórico |

**Niveles de riesgo:**
- 🟢 **0–30**: Sin riesgo — seguimiento normal
- 🟡 **31–60**: Riesgo moderado — intervención preventiva
- 🔴 **61–100**: Riesgo alto — intervención urgente, notificar acudiente

Este score se puede calcular en el backend como un endpoint `/api/reports/risk-scores?group_id=X&period_id=Y` sin necesidad de ML externo — es una fórmula determinista sobre datos existentes.

### Roadmap IA

**Fase 1 — Sin ML (Q2 2026):** Risk Score determinista con los 5 factores anteriores. Vista en dashboard y en el perfil del estudiante. Alertas cuando el score sube de umbral.

**Fase 2 — ML básico (Q3 2026):** Modelo de regresión logística entrenado con datos históricos del colegio para predecir probabilidad de reprobación de año. Input: scores de los 2 primeros períodos → Output: probabilidad de perder el año.

**Fase 3 — IA generativa (Q4 2026):** Integración con Claude API para generar recomendaciones pedagógicas personalizadas por estudiante basadas en su perfil de riesgo, asignaturas débiles y estilo de aprendizaje reportado por el docente.

---

## 5. Análisis de Diferenciación

### Aula360 vs Competencia

**K-12:**

| Criterio | Aula360 | Sigeweb | Master2000 | Escuelafácil |
|---------|:---:|:---:|:---:|:---:|
| Precio/mes | $$ | $$$$ | $$$ | $$$ |
| Implementación | < 1 semana | 2-4 semanas | 4-8 semanas | 2-3 semanas |
| Interfaz moderna | ✅ | ⚠️ | ❌ | ⚠️ |
| SaaS (cloud) | ✅ | ⚠️ | ❌ | ✅ |
| SIMAT nativo | ✅ | ✅ | ✅ | ⚠️ |
| Convivencia 1620 | ✅ | ✅ | ✅ | ⚠️ |
| Constancias PDF | ✅ | ✅ | ✅ | ⚠️ |
| Recuperación contraseña | ✅ | ✅ | ✅ | ✅ |
| API abierta | ✅ | ❌ | ❌ | ❌ |
| Riesgo estudiantil IA | ⚠️ P1 | ❌ | ❌ | ❌ |
| Tareas + vista acudiente | ⚠️ P1 | ⚠️ | ✅ | ⚠️ |

**IES (Educación Superior):**

| Criterio | Aula360 | Caseware SGA SINU | Sapiens |
|---------|:---:|:---:|:---:|
| Precio | $$ SaaS | $$$$ enterprise | $$$$ enterprise |
| Implementación | < 1 semana | Meses | Meses |
| SaaS / cloud nativo | ✅ | ❌ on-premise | ⚠️ |
| Interfaz moderna | ✅ | ❌ legacy | ⚠️ |
| PAPA ponderado créditos | ✅ | ✅ | ✅ |
| Matrícula masiva por semestre | ✅ | ✅ | ✅ |
| Kardex + nota final automática | ✅ | ✅ | ✅ |
| Portal del estudiante propio | ✅ | ✅ | ✅ |
| Homologaciones | ❌ P2 | ✅ | ✅ |
| Finanzas / cartera | ❌ OOS | ✅ | ✅ |
| Nómina docente (UGPP) | ❌ OOS | ✅ | ✅ |
| API REST moderna | ✅ | ❌ | ⚠️ |
| Soporte dual K-12 + IES | ✅ | ❌ | ❌ |
| ISO 27001 | ❌ P3 | ✅ | ✅ |

### Propuesta de Valor Única

Aula360 es el **único software académico colombiano** que combina:
1. Cumplimiento normativo completo K-12 (SIMAT, 1290, 1620) y IES (PAPA, créditos, semestres)
2. Interfaz moderna (Nuxt 3 + Nuxt UI — no jQuery legacy)
3. Precio SaaS accesible para colegios medianos e IES regionales
4. Implementación en menos de una semana (vs meses para Caseware/Sapiens)
5. API REST abierta para integraciones
6. **Una sola plataforma para K-12 e IES** — el modo cambia por institución
7. **Analítica de riesgo estudiantil al estilo Panorama Education** — único en el mercado colombiano

---

## 6. Roadmap Sugerido

### Q1 2026 (P0 — Go-to-Market Ready) ✅ COMPLETO
- [x] SIEE completo (logros, nivelaciones, promoción)
- [x] Exportación SIMAT + campos DANE (P0-A)
- [x] Convivencia Escolar Ley 1620 (P0-B)
- [x] Constancias y certificados PDF (P0-C)
- [x] Recuperación de contraseña (P0-D)
- [x] Módulo IES: créditos en materias, education_level por institución
- [x] IES: PAPA ponderado por créditos (P0-IES-1)
- [x] IES: Cálculo automático nota final desde GradeRecords (P0-IES-2)
- [x] IES: Portal del estudiante con rol propio (P0-IES-3)
- [x] IES: Matrícula masiva por semestre (P0-IES-4)

### Q2 2026 (P1 — Diferenciación)
- [ ] Horarios de clase
- [ ] **Módulo de Tareas con Evidencias** (P1-E) — docente + acudiente; entrega estudiante diferida
- [ ] Comunicados targetizados por grupo
- [ ] Paz y salvo / Certificado de graduación
- [ ] Inclusión educativa (Dec. 366)
- [ ] **Risk Score determinista por estudiante** (notas + asistencia + convivencia + nivelaciones)
- [ ] **Dashboard de riesgo por grupo/grado** para coordinadores
- [ ] **Alertas automáticas** al docente cuando un estudiante sube de umbral

### Q3 2026 (P2 — Escala e IA)
- [ ] Multi-sede
- [ ] Portal móvil PWA
- [ ] Integración WhatsApp/SMS
- [ ] **Modelo ML de predicción de reprobación de año** (regresión logística sobre histórico)
- [ ] **Encuestas de clima escolar y SEL** integradas con el Risk Score
- [ ] **Comparativa de desempeño entre grupos y grados**

### Q4 2026 (P3 — IA Generativa)
- [ ] **Recomendaciones pedagógicas automáticas** via Claude API basadas en perfil de riesgo
- [ ] **Resumen ejecutivo semanal** generado por IA para rectores
- [ ] **Sugerencias de actividades de nivelación** personalizadas por estudiante

---

*Documento generado: Marzo 2026 — Revisar cada trimestre*
