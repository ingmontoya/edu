# Estrategia de Precios: Aula360

**Fecha:** Marzo 2026
**Autor:** Analisis de mercado — Senior Business Analyst
**TRM Referencia:** $3.800 COP/USD

---

## 1. Benchmarks del Mercado

### 1.1 Competidores con Precios Verificados

#### Quid (Colombia — competidor directo mas cercano)

Unico competidor colombiano con precios publicos. Modelo desagregado por usuario:

| Componente | Precio/mes COP |
|---|---|
| Estudiante | $490 |
| Docente | $4.900 |
| Modulo IA asistencia | $9.950/docente |
| Soporte WhatsApp | $90.000 |
| Soporte telefono prioritario | $290.000 |
| Almacenamiento 10 GB | $19.900 |
| Almacenamiento 100 GB | $49.900 |

**Costo real para un colegio de 200 estudiantes + 20 docentes (base, sin IA):**
- Estudiantes: 200 x $490 = $98.000/mes
- Docentes: 20 x $4.900 = $98.000/mes
- Total mensual: **$196.000 COP ($52 USD)**
- Total anual (10 meses): **$1.960.000 COP ($516 USD)**

**Critica:** El modelo por-docente encarece rapidamente. Un colegio de 500 estudiantes
con 40 docentes paga $440.000/mes en base antes de IA, soporte o almacenamiento.
La atomizacion genera desconfianza y dificulta la presupuestacion del colegio.

---

#### iGradePlus (USA — referencia internacional accesible)

Modelo por estudiante/ano, precios publicos verificados:

| Estudiantes | Costo Anual USD | USD/est/ano | USD/est/mes | COP/ano (TRM 3.800) |
|---|---|---|---|---|
| 100 | $1.062 | $10.62 | $0.89 | $4.035.600 |
| 200 | $1.688 | $8.44 | $0.70 | $6.414.400 |
| 300 | $2.312 | $7.71 | $0.64 | $8.785.600 |
| 500 | $3.562 | $7.12 | $0.59 | $13.535.600 |
| 750 | $4.969 | $6.63 | $0.55 | $18.882.200 |
| 1.000 | $6.375 | $6.38 | $0.53 | $24.225.000 |

**Limitacion:** iGradePlus es completamente generico. No tiene SIMAT, Decreto 1290, SIEE,
Convivencia Escolar Ley 1620 ni soporte en espanol. Comparacion valida como piso de precio
para el mercado anglosajson; Aula360 debe ser mas barato dado el menor poder adquisitivo
colombiano pero con mas valor normativo local.

---

#### Classter (Europa/Global — referencia enterprise)

Modelo modular verificado (caso real: 1.000 estudiantes):

| Modulo | EUR/est/ano |
|---|---|
| Core | 8.50 |
| Academics & LMS | 6.50 |
| Add-ons (surveys, etc.) | 6.50 |

**Para 1.000 estudiantes con Core + Academics:** EUR 15.000/ano = ~$16.650 USD
**Ajuste regional Latam (-50%):** ~$8.325 USD/ano = ~$31.635.000 COP

Classter esta fuera del rango de presupuesto de colegios colombianos medianos sin ajuste regional.

---

#### Panorama Education (USA — referencia para el componente IA)

Precio por distrito (varios miles de estudiantes): $18.000-$26.000 USD/ano.
Para un distrito de 5.000 estudiantes: $3.60-$5.20 USD/estudiante/ano.

El diferencial de precio que paga el mercado por analitica estudiantil accionable
respecto a un SIS basico es de 3-5x. Este es el precedente para justificar el plan
Avanzado de Aula360 que incluye IA.

---

### 1.2 Plataformas sin Precios Publicos (Modelo Consultivo)

| Plataforma | Origen | Estimado de Mercado | Limitacion |
|---|---|---|---|
| Colegium | Chile/Colombia | $800.000-$3.500.000 COP/mes | No SaaS puro, implementacion larga |
| Sigeweb | Colombia | $1.500.000-$5.000.000 COP/mes | Solo colegios grandes |
| Master2000 | Colombia | $2.000.000-$6.000.000 COP/mes | Instalacion local, no cloud |
| Educamos | Espana | $1.200.000-$4.000.000 COP/mes | Orientado a mercado espanol |
| eduColombia | Colombia | Sin informacion publica | Interfaz antigua |

Estimados basados en testimonios de usuarios en foros educativos colombianos y el
posicionamiento de mercado de cada plataforma. No son precios oficiales verificados.

---

## 2. Contexto del Mercado Colombiano

### Datos Estructurales del Sector

| Indicador | Valor | Fuente |
|---|---|---|
| Total colegios en Colombia | 53.148 | MEN 2023 |
| Colegios privados | +9.000 | Estimado MEN |
| Estudiantes sector privado 2024 | 1.355.946 | MEN/DANE |
| Promedio estudiantes/institucion privada | ~150 | Calculo propio |
| Colegios Bogota con menos de 50 estudiantes | 243 | Datos Infobae 2025 |
| Ratio docente/estudiante privado | 1:16 | La Republica |
| Mensualidad promedio colegio privado 2025 | $1.090.000-$5.100.000 COP | MEN/Infobae |

### Capacidad de Pago

Un colegio privado de 300 estudiantes con mensualidad promedio de $1.500.000 COP genera:
- Ingresos anuales: ~$4.500.000.000 COP ($1.18M USD)
- Presupuesto razonable para software (0.5-1.0% de ingresos): $22.500.000-$45.000.000 COP/ano
- Presupuesto razonable mensual: $1.875.000-$3.750.000 COP/mes

Un colegio de 200 estudiantes con mensualidad promedio de $1.200.000 COP genera:
- Ingresos anuales: ~$2.400.000.000 COP
- Presupuesto razonable para software (0.3-0.5%): $7.200.000-$12.000.000 COP/ano

### Segmentacion del Mercado Objetivo

| Segmento | Tamano | Caracteristicas | Plan Aula360 |
|---|---|---|---|
| Microcolegios | <100 est | Municipios pequenos, presupuesto minimo | Basico (precio reducido) |
| Colegios pequenos | 100-200 est | Privados locales, directora-propietaria | Basico |
| Colegios medianos | 200-600 est | Privados urbanos con coord. academica | Estandar |
| Colegios mediano-grandes | 600-1.500 est | Privados o concesionados, varios coordinadores | Avanzado |
| Grupos educativos | 1.500+ est o multi-sede | Grupos empresariales, redes educativas | Enterprise |

---

## 3. Costo Marginal de IA para Aula360

### Claude Haiku 4.5 (modelo recomendado para analisis masivo)

| Componente | Precio |
|---|---|
| Input tokens | $0.001 USD por 1.000 tokens |
| Output tokens | $0.005 USD por 1.000 tokens |
| Descuento Batch API | -50% para procesamiento diferido |

### Costo por Operacion de IA

**Analisis de riesgo individual por estudiante (1x/mes):**
- Input: ~500 tokens (notas, asistencia, convivencia, tendencias)
- Output: ~800 tokens (score, resumen, recomendaciones)
- Costo: $0.0005 + $0.0040 = **$0.0045 USD = $17 COP**

**Resumen ejecutivo semanal para rector:**
- Input: ~3.000 tokens (datos agregados del colegio)
- Output: ~1.500 tokens (resumen narrativo ejecutivo)
- Costo: $0.003 + $0.0075 = **$0.0105 USD/semana = $40 COP**

**Costo IA mensual para un colegio de 500 estudiantes (uso intensivo):**

| Operacion | Frecuencia | Costo USD |
|---|---|---|
| Analisis riesgo (todos los estudiantes) | 1x/mes | $2.25 |
| Resumenes semanales | 4x/mes | $0.04 |
| Reanalisis solicitados por docentes | 100x/mes | $0.45 |
| **Total mensual** | | **$2.74 USD (~$10.400 COP)** |

**Conclusion:** El costo marginal de IA es irrelevante respecto al precio del plan. La IA
debe tratarse como diferenciador estrategico de alto valor percibido, no como costo a trasladar.
El margen bruto en el componente IA es superior al 95%.

### Costos de Infraestructura Estimados (Plan Avanzado, 1.500 est)

| Item | Costo Mensual Estimado |
|---|---|
| Servidor/cloud (Laravel + Nuxt) | $50-80 USD |
| Base de datos PostgreSQL | $20-40 USD |
| Claude API (IA) | $8-15 USD |
| Almacenamiento S3 (PDFs, archivos) | $10-20 USD |
| Email transaccional | $5-10 USD |
| **Total infra por colegio** | **$93-165 USD ($353.000-$627.000 COP)** |

Margen bruto estimado Plan Avanzado ($392 USD/mes): 58-76% antes de personal.

---

## 4. Planes de Precios Aula360

### Principios de Diseno

1. **Precio institucional (por rango de capacidad), no por estudiante individual.**
   Elimina la friccion psicologica de "cada nuevo alumno me cuesta mas" y facilita la presupuestacion anual del colegio.

2. **IA incluida en Plan Avanzado, no como addon.**
   Los addons fragmentan y crean confusion (error de Quid). El diferenciador es la plataforma completa.

3. **Precio en COP como referencia principal, USD como secundario.**
   El coordinador administrativo piensa en pesos. El inversor piensa en dolares. Se publican ambos.

4. **Descuento anual del 17% (equivalente a 2 meses gratis).**
   Estandar del mercado colombiano. Mejora ARR y reduce churn mensual.

5. **Onboarding incluido en todos los planes.**
   Los competidores cobran $990.000-$1.990.000 COP por capacitacion. Incluirlo reduce la barrera de entrada y acelera la adopcion.

---

### Plan Basico — "Starter"

**Segmento:** Colegios pequenos, inicio digital, municipios intermedios

| Parametro | Valor |
|---|---|
| **Precio mensual** | **$490.000 COP / $129 USD** |
| **Precio anual** | **$4.900.000 COP / $1.289 USD** (10 meses, ahorro $980.000) |
| **Capacidad maxima** | **200 estudiantes** |
| **Docentes y administrativos** | Ilimitados |

**Modulos incluidos:**
- Gestion de estudiantes (matricula, datos SIMAT/DANE completos)
- Registro y reporte de notas (Decreto 1290)
- Boletines de calificaciones en PDF
- Asistencia diaria por grupo
- SIEE (logros, indicadores, nivelaciones/remediales)
- Convivencia escolar (Ley 1620/2013 — tipos 1/2/3)
- Constancias y certificados PDF (matricula, notas, paz y salvo)
- Portal de acudientes (notas, asistencia, convivencia en tiempo real)
- Recuperacion de contrasena
- Onboarding: capacitacion virtual de 2 horas incluida
- Soporte por email (respuesta en 48 horas habiles)

**No incluye:**
- Modulo de tareas con evidencias de entrega
- Horarios de clase
- Analisis de riesgo estudiantil (IA)
- Resumenes ejecutivos por IA
- Soporte WhatsApp/telefono

**Justificacion del precio:**
- Quid cobra ~$196.000/mes por 200 estudiantes + 20 docentes sin IA ni SIEE. Aula360
  en $490.000 incluye normativa colombiana completa, SIEE, convivencia y constancias con
  onboarding incluido. La diferencia de precio refleja el stack normativo superior.
- iGradePlus para 200 estudiantes: $6.414.400 COP/ano. Aula360: $4.900.000 COP/ano (24% mas barato con normativa local).
- Representa el 0.20-0.35% de ingresos anuales de un colegio tipico de 200 estudiantes.

---

### Plan Estandar — "Profesional"

**Segmento:** Colegios medianos privados urbanos, con coordinacion academica activa

| Parametro | Valor |
|---|---|
| **Precio mensual** | **$890.000 COP / $234 USD** |
| **Precio anual** | **$8.900.000 COP / $2.342 USD** (10 meses, ahorro $1.780.000) |
| **Capacidad maxima** | **600 estudiantes** |
| **Docentes y administrativos** | Ilimitados |

**Todo lo del Plan Basico, mas:**
- Modulo de tareas con evidencias de entrega (docente crea, acudiente consulta)
- Comunicados targetizados por grupo o grado especifico
- Horarios de clase por grupo y docente
- Paz y salvo academico / Certificado de graduacion
- Dashboard de tendencias de asistencia por grupo (ultimas 4 semanas)
- Exportacion de reportes a Excel/CSV (notas, asistencia, convivencia)
- API REST documentada para integraciones con sistemas propios del colegio
- Soporte por WhatsApp (respuesta en 24 horas habiles)
- Onboarding: capacitacion virtual de 3 horas + 1 sesion practica en semana 2

**No incluye:**
- Analisis de riesgo estudiantil con IA
- Resumenes ejecutivos por IA
- Alertas automaticas de riesgo
- Soporte telefonico prioritario
- Multi-sede

**Justificacion del precio:**
- El salto de $490.000 a $890.000 (+82%) se justifica por: tareas, horarios, comunicados
  targetizados, exportacion, API y soporte WhatsApp. Para un colegio con 600 estudiantes
  el costo por estudiante al ano es $14.833 COP (~$3.90 USD) — inferior a iGradePlus sin IA.
- El soporte WhatsApp incluido vale $90.000/mes segun el modelo de Quid. Se absorbe en el precio.
- iGradePlus para 500 estudiantes: $13.535.600 COP/ano. Aula360 Estandar para 600: $8.900.000 COP (34% mas barato con 20% mas de capacidad y normativa colombiana).

---

### Plan Avanzado — "Institucional"

**Segmento:** Colegios mediano-grandes, liderazgo academico moderno, que buscan diferenciacion con IA

| Parametro | Valor |
|---|---|
| **Precio mensual** | **$1.490.000 COP / $392 USD** |
| **Precio anual** | **$14.900.000 COP / $3.921 USD** (10 meses, ahorro $2.980.000) |
| **Capacidad maxima** | **1.500 estudiantes** |
| **Docentes y administrativos** | Ilimitados |
| **Sedes** | Hasta 3 sedes |

**Todo lo del Plan Estandar, mas:**

**IA y Analitica (diferenciador unico en el mercado colombiano):**
- Risk Score 0-100 por estudiante (calculado mensualmente sobre notas, asistencia, convivencia y nivelaciones)
- Alertas automaticas al coordinador y docente cuando un estudiante supera umbral de riesgo
- Dashboard de bienestar academico por grupo y grado (para coordinadores y rectores)
- Resumenes ejecutivos semanales generados por IA (para rector — via Claude AI)
- Analisis de tendencias de notas ultimos 3 periodos con narrativa automatica
- Prediccion de estudiantes en riesgo de reprobar el ano (modelo determinista Q2, ML en Q3)

**Operativo:**
- Multi-sede (hasta 3 sedes bajo una institucion)
- Inclusion educativa — adaptaciones curriculares (Decreto 366)
- Soporte telefonico prioritario (respuesta en 4 horas en dias habiles)
- Gerente de cuenta asignado (revision trimestral de uso y resultados)
- Onboarding extendido: capacitacion virtual + media jornada presencial + acompanamiento 30 dias

**Justificacion del precio:**
- El salto de $890.000 a $1.490.000 (+67%) se justifica enteramente por el componente IA.
  Panorama Education cobra $18.000-$26.000 USD/ano por analitica estudiantil (mercado USA).
  Ninguna plataforma colombiana ofrece esto. Aula360 lo hace por $1.490.000 COP/mes.
- Para 1.500 estudiantes: $9.933 COP/estudiante/ano = $2.61 USD. Extraordinariamente
  competitivo vs cualquier referencia internacional.
- Costo marginal de IA para Aula360 en 1.500 estudiantes: ~$10 USD/mes.
  Precio del plan: $392 USD/mes. Margen bruto en componente IA: >97%.
- ROI para el colegio: retener 5 estudiantes adicionales gracias a intervencion temprana
  equivale a $60.000.000 COP/ano en pensiones. El plan cuesta $14.900.000/ano. ROI de 4x.

---

### Plan Enterprise

**Segmento:** Grupos educativos, redes de colegios, instituciones de 1.500+ estudiantes

| Parametro | Valor |
|---|---|
| **Precio base mensual** | **Desde $2.900.000 COP / $763 USD** |
| **Precio anual** | **Cotizacion personalizada** |
| **Capacidad** | Ilimitada |
| **Sedes** | Ilimitadas |

**Todo lo del Plan Avanzado, mas:**
- SLA garantizado (99.5% uptime con compensacion contractual)
- Migracion de datos asistida desde el sistema actual
- Integraciones custom (ERP contable, sistemas de pago, LDAP/SSO)
- Capacitacion presencial a todo el equipo docente
- Soporte dedicado con canal directo al equipo tecnico
- White-label opcional (logo y dominio propios)
- Dashboard consolidado multi-colegio para grupos educativos
- Reportes regulatorios consolidados (multiples colegios en un solo SIMAT)

---

## 5. Tabla Resumen de Precios

| Plan | Max. Estudiantes | Mensual COP | Mensual USD | Anual COP | COP/est/ano | USD/est/ano |
|---|---|---|---|---|---|---|
| Basico | 200 | $490.000 | $129 | $4.900.000 | $24.500 | $6.45 |
| Estandar | 600 | $890.000 | $234 | $8.900.000 | $14.833 | $3.90 |
| Avanzado | 1.500 | $1.490.000 | $392 | $14.900.000 | $9.933 | $2.61 |
| Enterprise | 1.500+ | desde $2.900.000 | desde $763 | cotizar | — | — |

*TRM referencia: $3.800 COP/USD*

---

## 6. Comparativa Competitiva

| Plataforma | 200 est/ano (COP) | 600 est/ano (COP) | 1.000 est/ano (COP) | Normativa CO | IA |
|---|---|---|---|---|---|
| **Aula360 Basico** | **$4.900.000** | — | — | Completa | No |
| **Aula360 Estandar** | — | **$8.900.000** | — | Completa | No |
| **Aula360 Avanzado** | — | — | **$14.900.000** | Completa | Si (incluida) |
| Quid (base, sin extras) | ~$2.350.000 | ~$5.880.000 | ~$9.800.000 | Parcial | Addon $$ |
| iGradePlus | ~$6.414.000 | ~$13.536.000 | ~$24.225.000 | Ninguna | No |
| Classter (est. Latam) | ~$6.000.000 | ~$14.000.000 | ~$22.000.000 | Ninguna | Parcial |
| Colegium | Cotizacion | Cotizacion | Cotizacion | Parcial | No |

**Ventaja de Aula360 vs iGradePlus:** Menor precio, normativa colombiana completa, IA incluida en plan Avanzado.
**Ventaja de Aula360 vs Quid:** Mayor valor por peso (modulos incluidos vs atomizados), modelo de precio predecible.
**Ventaja de Aula360 vs Colegium/Master2000:** Precio transparente, implementacion rapida, IA.

---

## 7. Estrategia de Lanzamiento

### 7.1 Precios de Lanzamiento (Primeros 12 Meses)

Descuento del 30% a los primeros 20 colegios con contrato anual:

| Plan | Precio Normal/mes | Precio Lanzamiento/mes | Ahorro Anual |
|---|---|---|---|
| Basico | $490.000 | $343.000 | $1.470.000 |
| Estandar | $890.000 | $623.000 | $2.670.000 |
| Avanzado | $1.490.000 | $1.043.000 | $4.470.000 |

Objetivo: generar 20 casos de exito en 6 meses para validar el producto y construir
referencias comerciales antes del lanzamiento oficial.

### 7.2 Psicologia de Precios

- $490.000 (no $500.000): debajo del umbral del medio millon
- $890.000 (no $900.000): debajo del millon
- $1.490.000 (no $1.500.000): debajo del millon y medio
- Mensaje clave Plan Avanzado: "menos de $5.000 COP por estudiante por ano"
- Mensaje clave Plan Basico: "menos del costo de un libro de texto por estudiante al ano"

### 7.3 Discurso de Venta para el Componente IA (Plan Avanzado)

**No hablar de:**
- "Claude AI", "tokens", "API", "LLM"
- "Inteligencia artificial generativa"
- Costos tecnicos de la IA

**Hablar de:**
- "Risk Score — sepa que estudiantes van a reprobar antes de que lo hagan"
- "Alertas tempranas — el sistema le avisa cuando un nino necesita atencion"
- "Resumen ejecutivo automatico — el rector llega los lunes ya informado"
- "Intervencion preventiva — no espere el boletin para actuar"

### 7.4 Funnel de Escalacion Natural

```
Colegio pequeno (100-200 est)
        |
   Plan Basico
        | (6-12 meses de uso, ve el valor)
        v
   Colegio crece a 250+ est, necesita tareas y API
        |
   Upgrade a Plan Estandar
        | (ve el dashboard de asistencia, quiere mas analisis)
        v
   Quiere saber quienes van a reprobar, el coordinador pide IA
        |
   Upgrade a Plan Avanzado
```

Cada tier debe generar deseo por el siguiente. Las tareas en Estandar y la IA en Avanzado
son los "ganchos" de escalacion.

---

## 8. ROI para el Cliente

### Plan Basico

Un coordinador que emite 30 constancias PDF por semana:
- Tiempo ahorrado: 5 min x 30 = 2.5 horas/semana = 130 horas/ano
- Costo hora coordinador: ~$50.000 COP
- **Ahorro anual: $6.500.000 COP**
- **Costo plan: $4.900.000 COP**
- **ROI: 133% — el plan se paga solo con el tiempo ahorrado en constancias**

### Plan Avanzado (con IA)

Un colegio que retiene 5 estudiantes adicionales gracias a intervencion temprana:
- Ingresos recuperados: 5 estudiantes x $1.200.000/mes x 10 meses = $60.000.000 COP/ano
- Costo del plan: $14.900.000 COP/ano
- **ROI: 4x en el primer ano con solo 5 retenciones adicionales**

Un coordinador academico que ahorra 1 hora diaria de reportes manuales:
- Tiempo ahorrado: 240 horas/ano
- Valor del tiempo (coordinador): ~$60.000 COP/hora
- Ahorro: $14.400.000 COP/ano
- **ROI solo en tiempo del coordinador: casi 1x el costo del plan**

---

## 9. Proyeccion de Ingresos

### Escenario Conservador (Ano 1 — post periodo de lanzamiento)

| Plan | Colegios | ARR/colegio | ARR Total COP | ARR Total USD |
|---|---|---|---|---|
| Basico | 15 | $4.900.000 | $73.500.000 | $19.342 |
| Estandar | 8 | $8.900.000 | $71.200.000 | $18.737 |
| Avanzado | 3 | $14.900.000 | $44.700.000 | $11.763 |
| **Total** | **26** | | **$189.400.000** | **$49.842** |

### Escenario Moderado (Ano 2)

| Plan | Colegios | ARR/colegio | ARR Total COP | ARR Total USD |
|---|---|---|---|---|
| Basico | 40 | $4.900.000 | $196.000.000 | $51.579 |
| Estandar | 25 | $8.900.000 | $222.500.000 | $58.553 |
| Avanzado | 15 | $14.900.000 | $223.500.000 | $58.816 |
| Enterprise | 3 | $34.800.000 | $104.400.000 | $27.474 |
| **Total** | **83** | | **$746.400.000** | **$196.421** |

### Mercado Total Direccionable (TAM)

- Colegios privados objetivo (200-1.500 estudiantes): ~3.500-4.000 instituciones
- Precio promedio ponderado: ~$10.000.000 COP/ano
- **TAM Colombia: ~$35.000.000.000-$40.000.000.000 COP/ano (~$9.2-10.5M USD/ano)**
- SAM (colegios urbanos, privados, con acceso digital): ~1.500 instituciones
- **SAM: ~$15.000.000.000 COP/ano (~$3.9M USD/ano)**

---

## 10. Metricas de Seguimiento

| Metrica | Meta Ano 1 | Meta Ano 2 |
|---|---|---|
| ARR total | $189M COP | $746M COP |
| Colegios activos | 26 | 83 |
| Churn rate mensual | < 3% | < 2% |
| Upgrade rate (Basico → Estandar) | 20% en 12m | 30% en 12m |
| Upgrade rate (Estandar → Avanzado) | 15% en 12m | 25% en 12m |
| NPS (Net Promoter Score) | > 40 | > 50 |
| CAC (costo adquisicion cliente) | < $1.500.000 COP | < $1.200.000 COP |
| LTV/CAC ratio | > 3x | > 5x |
| Tiempo de implementacion | < 5 dias | < 3 dias |

---

## Fuentes

- [Quid — Precios 2025](https://quid.pw/planes-precio/)
- [iGradePlus — Tabla de precios verificada](https://igradeplus.com/solutions/schoolmanagementsystem/pricing)
- [Classter — Pricing](https://www.classter.com/pricing/)
- [Comparasoftware Colombia — Software gestion escolar](https://www.comparasoftware.co/administracion-escolar)
- [La Republica — Dos de cada diez estudiantes en colegios privados](https://www.larepublica.co/economia/dos-de-cada-diez-estudiantes-escolares-en-colombia-estudian-en-colegios-privados-3964187)
- [Infobae — Matrículas colegios mas caros Colombia 2025](https://www.infobae.com/colombia/2025/01/16/encarecieron-las-matriculas-hasta-de-los-colegios-mas-caros-de-colombia-hay-mensualidades-de-mas-de-5-millones/)
- [Semana — Precios colegios privados Colombia 2026](https://www.semana.com/educacion/articulo/subiran-los-precios-de-los-colegios-privados-en-colombia-en-2026-asi-puede-calcular-el-nuevo-monto-a-pagar/202550/)
- [Panorama Education — G2 Pricing](https://www.g2.com/products/panorama-education/pricing)
- [Anthropic — Claude API Pricing](https://platform.claude.com/docs/en/about-claude/pricing)
- [TRM Colombia — Dolar hoy](https://www.dolarhoy.co/)
- [Colegium FAQ](https://www.colegium.com.co/preguntas-frecuentes/)
- [GetMonetizely — AI EdTech Pricing Guide](https://www.getmonetizely.com/articles/how-much-should-ai-education-agents-cost-a-pricing-guide-for-learning-platforms)

---

*Documento generado: Marzo 2026 — Revisar cada semestre o ante cambios significativos de mercado*
