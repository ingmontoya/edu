# Gap Analysis: Mercado Colombiano de Software Educativo

**Plataforma:** Aula360
**Fecha:** Marzo 2026
**Propósito:** Identificar brechas entre la oferta actual del mercado y los requisitos del sector educativo colombiano

---

## 1. Panorama Competitivo

### Competidores Principales en Colombia

| Plataforma | Fortalezas | Debilidades |
|-----------|-----------|-------------|
| **Escuelafácil** | Presencia en LATAM, funcionalidad amplia | Interfaz antigua, alto costo |
| **Sigeweb** | Integración SIMAT nativa | Solo para colegios grandes, difícil de usar |
| **Master2000** | Muy completo, notas, asistencia | Costoso, instalación local, no SaaS |
| **Axioma Escolar** | Adaptado a MEN, Decreto 1290 | Costoso, soporte lento |
| **iColaborar** | Comunicación familia-escuela | No gestión académica completa |
| **Sapiens** | ERP educativo completo | Precio enterprise, curva de aprendizaje alta |

### Posicionamiento de Aula360

Aula360 apunta al segmento **colegios medianos privados y concesionados** (200-1500 estudiantes) que necesitan:
- Cumplimiento normativo colombiano (MEN, SIMAT, Decreto 1290)
- Precio accesible (SaaS mensual, no licencia perpetua)
- Implementación rápida (< 1 semana)
- Interfaz moderna y usable por docentes no técnicos

---

## 2. Tabla de Brechas (Gap Analysis)

### 2.1 Requisitos Normativos

| Requisito | Estado Actual | Prioridad | Notas |
|-----------|--------------|-----------|-------|
| Exportación SIMAT (formato MEN) | ❌ Ausente | **P0** | Obligatorio para todos los colegios |
| Código DANE institución completo | ⚠️ Parcial | **P0** | Falta validación formato 12 dígitos |
| Decreto 1290 - Escala de valoración | ✅ Implementado | — | Superior/Alto/Básico/Bajo |
| Decreto 1290 - SIEE institucional | ✅ Implementado | — | Logros, indicadores, nivelaciones |
| Convivencia Escolar (Ley 1620/2013) | ❌ Ausente | **P0** | Comité de convivencia, tipos 1/2/3 |
| Manual de convivencia digital | ❌ Ausente | P1 | — |
| Decreto 366 - Inclusión | ❌ Ausente | P1 | Discapacidades, ajustes |
| Informe ICFES por grado | ❌ Ausente | P2 | — |

### 2.2 Funcionalidades Académicas

| Funcionalidad | Estado | Prioridad | Notas |
|--------------|--------|-----------|-------|
| Boletines PDF | ✅ Implementado | — | |
| Constancia de matrícula PDF | ❌ Ausente | **P0** | Solicitada frecuentemente |
| Constancia de notas PDF | ❌ Ausente | **P0** | Para bancos, becas, traslados |
| Paz y salvo académico | ❌ Ausente | P1 | |
| Certificado de graduación | ❌ Ausente | P1 | |
| Planilla de calificaciones imprimible | ✅ Implementado | — | |
| Horarios de clase | ❌ Ausente | P1 | |
| Seguimiento de proyectos transversales | ❌ Ausente | P2 | |

### 2.3 Comunicación y Portal

| Funcionalidad | Estado | Prioridad | Notas |
|--------------|--------|-----------|-------|
| Portal acudientes | ✅ Implementado | — | Ver notas, asistencia |
| Recuperación de contraseña | ❌ Ausente | **P0** | Bloquea adopción |
| Notificaciones push | ❌ Ausente | P1 | App móvil futura |
| Mensajería interna | ❌ Ausente | P1 | |
| Comunicados a grupos/grados | ✅ Parcial | P1 | Falta targeting por grupo |
| WhatsApp integration | ❌ Ausente | P2 | |

### 2.4 Administración

| Funcionalidad | Estado | Prioridad | Notas |
|--------------|--------|-----------|-------|
| Datos socioeconómicos estudiante | ❌ Ausente | **P0** | Requerido SIMAT (estrato, EPS) |
| Etnia y discapacidad | ❌ Ausente | **P0** | Requerido SIMAT/DANE |
| Multi-sede | ❌ Ausente | P1 | Colegios con varias sedes |
| Calendario académico configurable | ❌ Ausente | P1 | |
| Inventario / biblioteca | ❌ Ausente | P3 | Out of scope MVP |
| Nómina docentes | ❌ Ausente | P3 | Out of scope |

---

## 3. Prioridades P0 (Must-Have para Go-to-Market)

Estos items bloquean la adopción real o son obligatorios por ley:

### P0-A: Exportación SIMAT + Campos DANE
- **Por qué P0:** Todos los colegios colombianos deben reportar a SIMAT. Sin esto, el software no puede reemplazar al actual.
- **Alcance:** Campos adicionales en estudiante (estrato, EPS, etnia, discapacidad, municipio) + endpoint de exportación CSV en formato SIMAT
- **Esfuerzo estimado:** 2-3 días

### P0-B: Módulo de Convivencia Escolar (Ley 1620/2013)
- **Por qué P0:** Obligatorio por ley. Los colegios tienen comité de convivencia y deben registrar todos los casos.
- **Alcance:** Registro de situaciones tipo 1/2/3, seguimiento de casos, historial por estudiante
- **Esfuerzo estimado:** 3-4 días

### P0-C: Constancias y Certificados PDF
- **Por qué P0:** Solicitud #1 de coordinadores. Se emiten decenas por semana (bancos, traslados, becas).
- **Alcance:** Constancia de matrícula + Constancia de notas con membrete oficial
- **Esfuerzo estimado:** 1-2 días

### P0-D: Recuperación de Contraseña
- **Por qué P0:** Sin esto, cualquier usuario que olvide su contraseña necesita llamar al admin. Bloquea adopción masiva.
- **Alcance:** Flujo completo forgot/reset password con email
- **Esfuerzo estimado:** 1 día

---

## 4. Prioridades P1 (Diferenciadores Competitivos)

| Item | Descripción | Impacto |
|------|-----------|---------|
| Horarios de clase | Gestión de horarios por grupo/docente | Alto |
| Portal móvil PWA | App móvil para acudientes | Alto |
| Multi-sede | Gestión de colegios con varias sedes | Medio |
| Comunicados targetizados | Mensajes a grado/grupo específico | Medio |
| Paz y salvo | Documento oficial fin de año | Medio |
| Inclusión (Dec. 366) | Adaptaciones curriculares por discapacidad | Legal |

---

## 5. Análisis de Diferenciación

### Aula360 vs Competencia

| Criterio | Aula360 | Sigeweb | Master2000 | Escuelafácil |
|---------|---------|---------|-----------|-------------|
| Precio/mes | $$ | $$$$ | $$$ | $$$ |
| Implementación | < 1 semana | 2-4 semanas | 4-8 semanas | 2-3 semanas |
| Interfaz moderna | ✅ | ⚠️ | ❌ | ⚠️ |
| SaaS (cloud) | ✅ | ⚠️ | ❌ | ✅ |
| SIMAT nativo | ⚠️ P0 | ✅ | ✅ | ⚠️ |
| Convivencia 1620 | ⚠️ P0 | ✅ | ✅ | ⚠️ |
| Soporte colombiano | ✅ | ✅ | ✅ | ⚠️ |
| API abierta | ✅ | ❌ | ❌ | ❌ |

### Propuesta de Valor Única

Aula360 es el **único software académico colombiano** que combina:
1. Cumplimiento normativo completo (SIMAT, 1290, 1620)
2. Interfaz moderna (Nuxt 3 + Nuxt UI — no jQuery legacy)
3. Precio SaaS accesible para colegios medianos
4. Implementación en menos de una semana
5. API REST abierta para integraciones

---

## 6. Roadmap Sugerido

### Q1 2026 (P0 — Go-to-Market Ready)
- [x] SIEE completo (logros, nivelaciones, promoción)
- [ ] Exportación SIMAT + campos DANE (P0-A)
- [ ] Convivencia Escolar Ley 1620 (P0-B)
- [ ] Constancias y certificados PDF (P0-C)
- [ ] Recuperación de contraseña (P0-D)

### Q2 2026 (P1 — Diferenciación)
- [ ] Horarios de clase
- [ ] Comunicados targetizados por grupo
- [ ] Paz y salvo / Certificado de graduación
- [ ] Inclusión educativa (Dec. 366)

### Q3 2026 (P2 — Escala)
- [ ] Multi-sede
- [ ] Portal móvil PWA
- [ ] Integración WhatsApp/SMS
- [ ] Dashboard analytics avanzado

---

*Documento generado: Marzo 2026 — Revisar cada trimestre*
