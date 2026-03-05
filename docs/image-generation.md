# Generación de Imágenes con IA — Aula360

Sistema para generar imágenes de marketing y ad creative para Aula360 usando
modelos de IA (Google Imagen 4 / Nano Banana 2 vía fal.ai), con prompts JSON
estructurados y contexto específico del Caribe colombiano.

---

## Stack

| Herramienta | Rol |
|-------------|-----|
| Claude Code + skill `nanobanana.md` | Convierte briefs en JSON prompts estructurados |
| Google Imagen 4.0 Fast | Modelo principal (requiere billing en GCP) |
| fal.ai — Nano Banana 2 | Fallback automático si Google falla |
| `scripts/generate.py` | Dispara el JSON a la API y guarda la imagen |

---

## Estructura de carpetas

```
scripts/
└── generate.py               # Script principal

.claude/skills/
└── nanobanana.md             # Skill de Claude para construir prompts JSON

prompts/
├── rector-testimonial/       # Fotos tipo testimonial de rectores/directivos
├── teacher-in-action/        # Docentes usando la plataforma con tablet/laptop
├── parent-portal/            # Acudientes revisando notas en celular
├── hero-landing/             # Hero de la landing page
├── before-after/             # Caos papel vs orden digital
├── ugc-style/                # Estilo UGC / selfie auténtico
├── lifestyle/                # Escenas cotidianas del colegio
├── city-specific/            # Creativos segmentados por ciudad
├── product-hero/             # Mockups del dashboard en dispositivo
├── infographics/             # Comparativos, diagramas de cómo funciona
└── social-proof/             # Screenshots de reseñas, testimonios

images/
└── (misma estructura que prompts/ — aquí se guardan los .png generados)
```

---

## Configuración

### Variables de entorno

```bash
# Opción A — Google AI Studio (principal, requiere billing activo en GCP)
export GOOGLE_API_KEY="tu_key_aqui"

# Opción B — fal.ai (fallback, ~$0.04/imagen)
export FAL_KEY="tu_key_aqui"
```

El script intenta Google primero. Si falla, usa fal.ai automáticamente.

### Obtener una API key con billing

**Google Cloud (recomendado — $300 gratis para cuentas nuevas):**
1. Ve a [cloud.google.com](https://cloud.google.com) → Start free
2. Activa billing (pide tarjeta, no cobra hasta gastar los $300)
3. En [aistudio.google.com](https://aistudio.google.com) → Settings → vincula el proyecto con billing
4. Crea una nueva API key desde ese proyecto

**fal.ai:**
1. Regístrate en [fal.ai](https://fal.ai)
2. Dashboard → API Keys → crear key
3. Recarga créditos mínimos ($5 = ~125 imágenes)

### Instalar dependencia

```bash
pip install requests
```

---

## Uso

```bash
# Generar una imagen
python3 scripts/generate.py <ruta-al-prompt.json> <carpeta-de-salida>

# Ejemplos
python3 scripts/generate.py prompts/rector-testimonial/rectora-cartagena-01.json images/rector-testimonial
python3 scripts/generate.py prompts/teacher-in-action/docente-barranquilla-tablet-01.json images/teacher-in-action
python3 scripts/generate.py prompts/parent-portal/acudiente-santamarta-celular-01.json images/parent-portal
```

La imagen se guarda como `images/<carpeta>/YYYYMMDD_HHMMSS.png`.

---

## Modelos disponibles (verificado con la API key actual)

```
imagen-4.0-generate-001        ← calidad máxima, más lento
imagen-4.0-fast-generate-001   ← usado por defecto, más rápido y barato
imagen-4.0-ultra-generate-001  ← ultra calidad, más caro
gemini-2.5-flash-image
gemini-3.1-flash-image-preview
gemini-3-pro-image-preview
```

Todos requieren billing activo. El script usa `imagen-4.0-fast-generate-001`.

### Precios aproximados

| Proveedor | Modelo | Costo/imagen |
|-----------|--------|-------------|
| Google AI Studio | Imagen 4.0 Fast | ~$0.03 |
| Google AI Studio | Imagen 4.0 | ~$0.04 |
| fal.ai | Nano Banana 2 | ~$0.04 |

---

## Formato del JSON prompt

```json
{
  "prompt": "Descripción visual detallada",
  "negative_prompt": "Elementos a excluir",
  "settings": {
    "resolution": "1024x1024 | 1536x1536 | 2048x2048",
    "aspect_ratio": "1:1 | 4:5 | 9:16 | 16:9 | 3:4",
    "style": "ugc-selfie | lifestyle-in-context | studio-product-hero | ...",
    "lighting": "warm-tropical-window | golden-hour-outdoor | ring-light | ...",
    "camera": {
      "lens": "35mm | 50mm | 85mm | 105mm | ...",
      "angle": "eye-level | low-angle | overhead",
      "framing": "close-up | medium | full-body",
      "depth_of_field": "shallow | moderate | deep",
      "focus": "subject | background"
    },
    "color_grading": "warm | cool | neutral | vibrant"
  }
}
```

> **Nota:** Google Imagen no soporta el ratio `4:5` (Facebook feed). El script
> lo mapea automáticamente a `3:4`, que es el más cercano disponible.

---

## Contexto Caribe colombiano (Aula360)

La skill `nanobanana.md` está configurada con contexto específico para Aula360:

### Personas
- Diversidad étnica: Afro-colombiano, mestizo costeño, influencia Wayuu/Zenú
- Nunca rasgos únicamente europeos
- Piel natural con poros visibles, no retocada

### Ambientación por ciudad
| Ciudad | Cues visuales |
|--------|--------------|
| Cartagena | Corredores coloniales, arcos coloridos, abanicos de techo, madera |
| Barranquilla | Urbano moderno, corredores abiertos, energía carnavalesca |
| Santa Marta | Costera y relajada, Sierra Nevada al fondo en exteriores |

### Vestimenta
- **Estudiantes**: Uniforme colombiano — polo blanco con escudo, pantalón/falda azul oscuro o kaki
- **Docentes**: Smart casual — camisa o blusa, sin traje formal
- **Rectores/as**: Blazer + blusa (mujeres), guayabera o camisa formal (hombres)

### Iluminación
- Luz tropical cálida como default — nunca gris ni fría
- Ventiladores de techo como elemento de ambientación auténtico

---

## Categorías de creativos para Aula360

| Carpeta | Descripción | Ratio recomendado |
|---------|-------------|-------------------|
| `rector-testimonial` | Directivo/rector con expresión cálida y confiada | 1:1 o 4:5 |
| `teacher-in-action` | Docente con tablet/laptop en aula | 4:5 |
| `parent-portal` | Acudiente revisando notas en celular | 9:16 (Stories) |
| `hero-landing` | Director revisando dashboard en laptop, oficina | 16:9 |
| `before-after` | Cuadernos de papel caóticos vs dashboard organizado | 1:1 |
| `city-specific` | Mismo creativo segmentado por Cartagena/Barranquilla/Santa Marta | 4:5 |

---

## Flujo con Claude Code

1. Abrir Claude Code en la raíz del proyecto
2. Describir el creativo en español natural:
   ```
   "Necesito una imagen de una rectora afrocartagenera revisando el
    índice de riesgo estudiantil en su laptop, para la landing page"
   ```
3. Claude lee la skill `nanobanana.md` y genera el JSON prompt
4. Guardar el JSON en la carpeta correspondiente bajo `prompts/`
5. Correr el script para generar la imagen
6. Dar feedback → Claude ajusta el JSON → regenerar

---

## Prompts de ejemplo incluidos

| Archivo | Descripción |
|---------|-------------|
| `prompts/rector-testimonial/rectora-cartagena-01.json` | Rectora Afro-colombiana, corredor colonial Cartagena, 1:1 |
| `prompts/teacher-in-action/docente-barranquilla-tablet-01.json` | Docente con tablet en aula, Barranquilla, 4:5 |
| `prompts/parent-portal/acudiente-santamarta-celular-01.json` | Acudiente revisando notas en Samsung, Santa Marta, 9:16 |
| `prompts/ugc-style/test-serum-ugc-01.json` | Prompt de prueba genérico (UGC skincare) |

---

## Problemas conocidos

| Error | Causa | Solución |
|-------|-------|----------|
| `404 Not Found` | Nombre de modelo incorrecto | Verificar modelos disponibles con `GET /v1beta/models` |
| `400 Imagen 3 is only available on paid plans` | Billing no activado en GCP | Activar billing y vincular proyecto en AI Studio |
| `Quota exceeded, limit: 0` | Modelo no tiene cuota gratis | Activar billing |
| `FAL_KEY is not set` | No hay fallback configurado | Exportar `FAL_KEY` o activar billing en Google |
| `os.environ.get("AIzaSy...")` retorna None | Key hardcodeada como nombre de variable | Usar siempre `os.environ.get("GOOGLE_API_KEY")` |
