# Datos de Demo — Aula360

Este documento describe todo lo que encontrarás en la plataforma después de ejecutar `php artisan db:seed`. Hay dos instituciones completamente independientes cargadas: un colegio K-12 y una institución de educación superior.

---

## Cómo sembrar la base de datos

```bash
php artisan migrate:fresh --seed
```

---

## Institución 1 — Colegio Aula360 Demo (K-12)

**Seeder:** `SchoolSeeder` + `P0DemoSeeder` + `TeacherAssignmentsSeeder` + `DemoUsersSeeder`

Colegio ficticio en Barranquilla que representa una institución colombiana K-12 tradicional con todos los módulos activados.

### Credenciales de acceso

| Rol         | Email                          | Contraseña |
|-------------|--------------------------------|------------|
| Admin       | `admin@example.com`            | `password` |
| Coordinador | `coordinador@example.com`      | `password` |
| Docente     | `teacher@example.com`          | `password` |
| Acudiente   | `guardian@example.com`         | `password` |

### Estructura académica

- **Año activo:** 2026 (12 enero – 27 noviembre)
- **4 períodos** con pesos iguales (25% cada uno)
- **12 grados:** Transición, 1° a 5° (primaria), 6° a 9° (secundaria), 10° y 11° (media)
- **2 grupos por grado** (A y B) — 24 grupos en total, capacidad 35 estudiantes c/u
- **8 áreas** con un total de ~240 materias (una instancia por grado):
  - Matemáticas (Matemáticas, Geometría, Estadística)
  - Humanidades (Lengua Castellana, Inglés, Lectura Crítica)
  - Ciencias Naturales (Biología, Química, Física)
  - Ciencias Sociales (Historia, Geografía, Democracia)
  - Educación Artística (Artes Plásticas, Música)
  - Educación Física
  - Tecnología (Informática, Tecnología)
  - Ética y Valores (Ética, Religión)

### Docentes (15)

| Nombre                    | Especialidad          | Contrato    |
|---------------------------|-----------------------|-------------|
| María García López        | Matemáticas           | Planta       |
| Carlos Rodríguez Pérez    | Ciencias Naturales    | Planta       |
| Ana Martínez Gómez        | Lengua Castellana     | Planta       |
| Luis Hernández Castro     | Ciencias Sociales     | Planta       |
| Patricia López Díaz       | Inglés                | Planta       |
| Jorge Sánchez Ruiz        | Educación Física      | Planta       |
| Carmen Torres Vega        | Artes                 | Medio tiempo |
| Miguel Ramírez Silva      | Tecnología            | Planta       |
| Laura González Mora       | Ética y Religión      | Medio tiempo |
| Pedro Vargas Luna         | Física                | Planta       |
| Sandra Mendoza Rico       | Química               | Planta       |
| Ricardo Flores Pinto      | Historia              | Planta       |
| Elena Ríos Cardona        | Geografía             | Medio tiempo |
| Fernando Castro Mejía     | Música                | Contratista  |
| Mónica Duarte Herrera     | Preescolar            | Planta       |

- María García López tiene asignación fija en Matemáticas de 6° y 7°.
- El resto de asignaciones son aleatorias por grupo/materia.

### Estudiantes

- Entre 15 y 25 estudiantes por grupo (generados aleatoriamente con nombres colombianos).
- Cada estudiante tiene un acudiente vinculado.
- Se generan notas aleatorias para todos los períodos y materias.
- Se generan registros de asistencia para períodos cerrados.

---

## Institución 2 — Colegio Mayor de Cartagena (Educación Superior)

**Seeder:** `ColeMayorSeeder`

Institución real de educación superior en Cartagena, configurada en **modo `higher`** (educación superior). Tiene datos cuidadosamente construidos para cubrir los casos de uso más comunes de matrículas universitarias.

### Credenciales de acceso

| Rol         | Email                              | Contraseña |
|-------------|------------------------------------|------------|
| Admin       | `admin@colmayor.edu.co`            | `password` |
| Coordinador | `coordinador@colmayor.edu.co`      | `password` |
| Docente     | `docente.luis@colmayor.edu.co`     | `password` |
| Docente     | `docente.ana@colmayor.edu.co`      | `password` |
| Docente     | `docente.ricardo@colmayor.edu.co`  | `password` |

### Ficha institucional

| Campo        | Valor                                          |
|--------------|------------------------------------------------|
| Nombre       | Colegio Mayor de Cartagena                     |
| NIT          | 890.480.128-3                                  |
| DANE         | 130010005678                                   |
| Dirección    | Calle del Bouquet # 36-66, Centro Histórico    |
| Rectora      | Dra. Claudia Patricia Berbesi de Salcedo       |
| Modo         | Educación Superior (`education_level=higher`)  |
| Cuota IA     | 100 análisis                                   |

### Año académico y cortes

- **Año:** 2026 (20 enero – 30 noviembre)
- **3 cortes** de evaluación:

| Corte   | Fechas                    | Peso | Estado   |
|---------|---------------------------|------|----------|
| Corte 1 | 20 ene – 28 mar 2026      | 30%  | Cerrado  |
| Corte 2 | 30 mar – 13 jun 2026      | 30%  | Abierto  |
| Corte 3 | 7 jul – 14 nov 2026       | 40%  | Abierto  |

### Programas (Grados)

| Código | Nombre                                    |
|--------|-------------------------------------------|
| ADM    | Administración de Empresas                |
| SIS    | Tecnología en Sistemas de Información     |
| CON    | Contaduría Pública                        |

### Semestres (Grupos)

| Programa | Semestre     |
|----------|--------------|
| ADM      | Semestre I   |
| ADM      | Semestre III |
| SIS      | Semestre I   |
| CON      | Semestre I   |

### Áreas y Materias

| Área                        | Materias                                                            |
|-----------------------------|---------------------------------------------------------------------|
| Ciencias Administrativas    | Introducción a la Administración (3 cr), Fundamentos de Economía (3 cr), Matemáticas Financieras (4 cr), Gestión del Talento Humano (3 cr) |
| Tecnología e Informática    | Programación Básica (4 cr), Redes y Comunicaciones (3 cr)          |
| Ciencias Contables          | Contabilidad General (4 cr), Legislación Comercial (3 cr)          |
| Humanidades y Comunicación  | Comunicación Empresarial (2 cr), Inglés Técnico (2 cr)             |

### Docentes (3)

| Nombre                            | Especialización              | Asignaciones                                              |
|-----------------------------------|------------------------------|-----------------------------------------------------------|
| Prof. Luis Fernando Díaz Herrera  | Administración               | Intro. Administración (ADM Sem I), Mat. Financieras (ADM Sem III) |
| Dra. Ana María Ospina Romero      | Economía y Contabilidad      | Fund. Economía (ADM Sem I), GTH (ADM Sem III), Contabilidad (CON Sem I) |
| Ing. Ricardo Andrés Torres Vega   | Sistemas e Informática       | Programación Básica (SIS Sem I), Redes (SIS Sem I)        |

### Estudiantes (10)

| # | Nombre                      | Código       | Situación académica                                                                      |
|---|-----------------------------|--------------|------------------------------------------------------------------------------------------|
| 1 | Santiago Gómez Ríos         | CM-2026-001  | **ADM Sem I** — matriculado en las 3 materias del semestre (sin notas aún)              |
| 2 | María Fernanda López        | CM-2026-002  | **ADM Sem I** — matriculada en Intro. Adm. y Fund. Economía; **retirada** de Com. Empresarial |
| 3 | Andrés Felipe Martínez      | CM-2026-003  | **ADM Sem I** — matriculado con notas del Corte 1 (4.2 / 3.8 / 4.5)                   |
| 4 | Laura Cristina Peña         | CM-2026-004  | **Semestre I completado** (4.1 / 3.7 / 4.8) + cursando Sem II (Mat. Fin. y GTH)        |
| 5 | Diego Alejandro Torres      | CM-2026-005  | **Reprobó** Fund. Economía (nota final 2.1, Corte 1: 1.8) + re-matriculado en Sem 2    |
| 6 | Valentina Ríos Montoya      | CM-2026-006  | **SIS Sem I** — matriculada en las 3 materias (Prog. Básica, Redes, Inglés Técnico)    |
| 7 | Camilo Herrera Díaz         | CM-2026-007  | **CON Sem I** — matriculado en Contabilidad General y Legislación Comercial             |
| 8 | Isabella Moreno Castro      | CM-2026-008  | **Sin matrículas** — admitida pero no matriculada en ninguna materia                    |
| 9 | Juan David Castro Vargas    | CM-2026-009  | **SIS Sem I** — con notas del Corte 1 (3.5 / 4.0 / 3.9) y registro de asistencia      |
|10 | Sofía Alejandra Vargas      | CM-2026-010  | **ADM Sem III** — matriculada en Mat. Financieras y GTH                                |

> **Contraseña de todos los estudiantes:** `password`
> **Email:** ver patrón `nombre.apellido@colmayor.edu.co` (ej. `santiago.gomez@colmayor.edu.co`)

#### Detalle de notas y asistencia

**Andrés Felipe Martínez — Corte 1:**
| Materia                        | Nota | Docente |
|--------------------------------|------|---------|
| Introducción a la Administración | 4.2 | Prof. Luis |
| Fundamentos de Economía         | 3.8 | Dra. Ana  |
| Comunicación Empresarial        | 4.5 | Prof. Luis |

**Diego Alejandro Torres — Corte 1 (Fund. Economía):**
| Materia              | Nota | Observación                                   |
|----------------------|------|-----------------------------------------------|
| Fundamentos de Economía | 1.8 | Rendimiento insuficiente. Requiere nivelación. |

→ Nota final registrada: **2.1** (Reprobado). Re-matriculado en Sem 2.

**Laura Cristina Peña — Semestre I completado:**
| Materia                          | Nota final |
|----------------------------------|------------|
| Introducción a la Administración | 4.1        |
| Fundamentos de Economía          | 3.7        |
| Comunicación Empresarial         | 4.8        |

**Juan David Castro Vargas — Corte 1:**
| Materia            | Nota | Docente           |
|--------------------|------|-------------------|
| Programación Básica | 3.5 | Ing. Ricardo      |
| Redes y Comunicaciones | 4.0 | Ing. Ricardo   |
| Inglés Técnico     | 3.9 | Ing. Ricardo      |

**Juan David Castro Vargas — Asistencia (Corte 1, semana del 2–6 mar):**
| Fecha       | Estado   | Observación                    |
|-------------|----------|--------------------------------|
| 2026-03-02  | Presente | —                              |
| 2026-03-03  | Presente | —                              |
| 2026-03-04  | Tarde    | —                              |
| 2026-03-05  | Presente | —                              |
| 2026-03-06  | Ausente  | No se presentó sin justificación |

---

## Resumen de datos por institución

| Concepto           | K-12 (Aula360 Demo) | Superior (Colegio Mayor) |
|--------------------|---------------------|--------------------------|
| Usuarios staff     | 2 (admin + coord)   | 5 (admin + coord + 3 doc)|
| Docentes           | 15                  | 3                        |
| Grados/Programas   | 12                  | 3                        |
| Grupos/Semestres   | 24                  | 4                        |
| Materias           | ~240                | 10                       |
| Estudiantes        | ~400 (aleatorios)   | 10 (nombrados)           |
| Modo educación     | K-12 (defecto)      | `higher`                 |

---

## Casos de prueba cubiertos por el seeder de Colegio Mayor

Los 10 estudiantes fueron diseñados para cubrir los edge cases más importantes:

| Caso                                    | Estudiante        |
|-----------------------------------------|-------------------|
| Matrícula completa activa               | Santiago          |
| Retiro voluntario de una materia        | María Fernanda    |
| Notas cargadas por el docente           | Andrés, Juan David |
| Semestre completado + avanzando         | Laura             |
| Reprobación + re-matrícula              | Diego             |
| Matrícula en programa diferente (SIS)   | Valentina, Juan David |
| Matrícula en programa diferente (CON)   | Camilo            |
| Admitida sin matrículas                 | Isabella          |
| Estudiante avanzado (Sem III)           | Sofía             |
| Asistencia registrada                   | Juan David        |
