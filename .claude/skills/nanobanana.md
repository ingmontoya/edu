# Nano Banana 2 — Aula360 Ad Creative Skill

## Purpose
Convert plain-text briefs into structured JSON prompts for Nano Banana 2 / Imagen 3
that produce realistic, ad-ready images for Aula360 — a SaaS academic platform
targeting schools in the Colombian Caribbean (Cartagena, Barranquilla, Santa Marta).

---

## JSON Prompt Schema

Always output valid JSON with these fields:

```json
{
  "prompt": "Detailed visual description",
  "negative_prompt": "Elements to exclude",
  "settings": {
    "resolution": "1024x1024 | 1536x1536 | 2048x2048",
    "aspect_ratio": "1:1 | 4:5 | 16:9 | 9:16 | 3:4",
    "style": "See style guide below",
    "lighting": "See lighting guide below",
    "camera": {
      "lens": "24mm | 35mm | 50mm | 85mm | 105mm | 200mm",
      "angle": "eye-level | low-angle | high-angle | overhead | dutch-angle",
      "framing": "extreme-close-up | close-up | medium | full-body | wide",
      "height": "ground-level | waist-level | eye-level | elevated",
      "depth_of_field": "shallow | moderate | deep",
      "focus": "subject | background | split"
    },
    "color_grading": "warm | cool | neutral | muted | vibrant | cinematic"
  }
}
```

---

## Aula360 Colombian Caribbean Context (ALWAYS apply)

### People — Ethnicity & Appearance
The Colombian Caribbean is ethnically diverse. Vary representation across generations:
- **Afro-Colombian**: Common in Cartagena, Barranquilla, Santa Marta coastal areas
- **Costeño mestizo**: Mixed indigenous + European heritage, olive/brown skin tones
- **Wayuu / Zenú influence**: Visible in dress and features in some regions
- Do NOT default to European features. Reflect real diversity.
- Skin: visible pores, natural texture, not airbrushed. Tropical climate — slight humidity sheen is realistic.
- Hair: natural coily, curly, or straight hair depending on ethnicity. Avoid generic stock-photo look.

### Setting — Schools & Offices
- **Cartagena**: Colonial architecture (walls, arched corridors), colorful buildings, ceiling fans, wooden desks in older schools. Also modern private schools near Bocagrande.
- **Barranquilla**: More modern urban, concrete buildings, AC in private schools, open-air corridors in public schools. Carnivalesque color palette in the city.
- **Santa Marta**: Coastal, relaxed, mix of old and new. Sierra Nevada foothills in background when outdoors.
- **General costeño school cues**: Covered outdoor corridors (corredores techados), ceiling fans, tiled floors, brightly colored walls (yellow, orange, blue), potted plants, mural art on walls.
- **Rector/Director office**: Modest desk, diplomas on wall, small Colombian flag, laptop/tablet on desk, a stack of paper grade books visible (to contrast with Aula360).
- **Classroom**: Rows of desks, chalkboard or whiteboard, teacher standing near front, bright natural light from windows or open doors.

### Climate & Lighting
- Hot Caribbean climate. Natural light is intense and warm.
- Preferred lighting: warm natural window light, golden diffused interior light
- Avoid cold/grey lighting — it feels wrong for the Caribbean
- Outdoor shots: bright midday tropical sun or late afternoon golden hour

### Uniforms & Dress
- **Students**: Typical Colombian school uniforms — white polo shirt with school crest, dark navy or khaki pants/skirt, black shoes. Or sport uniform (sudadera) in PE.
- **Teachers**: Smart casual — teachers in the Caribbean rarely wear formal suits. Button-up shirt or blouse, slacks or skirt.
- **Rectors/Directors**: Professional but not overly formal. Women: blazer over blouse. Men: guayabera (traditional Caribbean formal shirt) or button-up shirt.

### Devices & Tech
- Laptops (Mac or generic), tablets (iPad or Samsung), smartphones (Samsung Galaxy prevalent in Colombia)
- Screen should show a clean academic dashboard (grades, attendance, charts) — not a generic OS

---

## Aula360 Ad Creative Categories

### 1. `hero-landing` — Landing Page Hero
**Brief**: Director or coordinator reviewing Aula360 dashboard
**Key elements**: Professional, warm office setting, visible screen with grade data
**Lens**: 50mm | Lighting: warm window | Color: warm neutral
**Aspect**: 16:9 for web, 4:5 for social

### 2. `rector-testimonial` — Director / Rector Testimonial
**Brief**: Mid-age Colombian woman or man, professional, warm smile, school corridor or office background
**Key elements**: Confident, approachable, real person feel — not stock photo
**Lens**: 85mm portrait | Lighting: natural window | Color: warm
**Aspect**: 1:1 or 4:5

### 3. `teacher-in-action` — Teacher Using Platform
**Brief**: Teacher at desk or standing in classroom with tablet, entering grades or viewing attendance
**Key elements**: Engaged expression, classroom background visible, Aula360-like screen
**Lens**: 35mm | Lighting: classroom natural | Color: warm
**Aspect**: 4:5 or 1:1

### 4. `parent-portal` — Parent Checking Grades
**Brief**: Colombian parent on smartphone viewing their child's grades
**Key elements**: Home setting, casual, relieved/happy expression, clear phone screen with academic data
**Lens**: 35mm | Lighting: home warm light | Color: warm
**Aspect**: 9:16 for Stories, 4:5 for feed

### 5. `before-after` — Paper Chaos vs Digital Order
**Brief**: Split image — LEFT: messy desk with paper grade books, folders, stress — RIGHT: clean laptop screen with organized Aula360 dashboard, calm expression
**Key elements**: Same person/setting, dramatic contrast in organization
**Aspect**: 1:1 for carousel, 16:9 for web

### 6. `product-dashboard` — Clean UI Showcase
**Brief**: Device mockup (laptop or tablet) showing Aula360 dashboard with Colombian academic data
**Key elements**: Realistic device, legible UI, warm background
**Lens**: 105mm | Lighting: studio softbox | Color: neutral
**Aspect**: 16:9 or 4:5

### 7. `social-media-ugc` — UGC-Style School Moment
**Brief**: Authentic costeño school moment — students in patio, teacher explaining, parent picking up child
**Key elements**: Shot on phone feel, casual, not staged, vibrant Caribbean school environment
**Lens**: 35mm | Lighting: tropical natural | Color: vibrant warm
**Aspect**: 1:1 or 4:5

### 8. `city-specific` — City-Targeted Creative
Use city context explicitly in the prompt:
- **Cartagena**: "colonial school corridor, colorful arched walls, old city feel, Caribbean light"
- **Barranquilla**: "modern urban school, open corridors, Barranquilla skyline hint, energetic atmosphere"
- **Santa Marta**: "coastal school, Sierra Nevada mountains visible in background, relaxed Caribbean vibe"

---

## Style Guide

- **ugc-selfie**: Shot on iPhone look. Slightly imperfect framing. Casual, authentic, not polished.
- **lifestyle-in-context**: Product/platform in real Caribbean school environment. Natural light. Believable.
- **studio-product-hero**: Device mockup on clean background. Perfect lighting. For web/SaaS screenshots.
- **editorial-professional**: High-end B2B feel. Strong composition. For LinkedIn and premium placements.
- **before-after**: Split or side-by-side. Paper chaos vs digital order. Same lighting both sides.

## Lighting Guide

- **warm-tropical-window**: Default for Aula360. Soft warm Caribbean daylight through open windows.
- **golden-hour-outdoor**: Warm directional tropical sun. School patio/outdoor scenes.
- **ceiling-fan-interior**: Warm interior with ceiling fans casting soft diffused light. Authentic costeño school feel.
- **studio-softbox**: Controlled, even light. Device/UI mockups and product shots.
- **ring-light**: UGC-style testimonials and selfie-format content.
- **dramatic-rim**: Premium/editorial positioning. Award moments, gala events.

---

## Platform Defaults

- Facebook/Instagram Feed: 4:5 aspect ratio
- Stories/Reels: 9:16 aspect ratio
- Carousel: 1:1 aspect ratio
- Landing page hero / web: 16:9 aspect ratio
- LinkedIn: 1:1 or 1.91:1

---

## Prompt Rules

1. ALWAYS use JSON — never plain-text
2. ALWAYS include a negative_prompt
3. ALWAYS vary ethnicity — reflect the Afro-Colombian, mestizo costeño diversity of the Caribbean
4. For people: specify "visible pores, natural skin texture, real hair" — never airbrushed
5. For screen/UI elements: describe the content on screen explicitly ("a grade table with student names and scores in Spanish")
6. For Colombian cues: mention school uniforms, guayabera shirts, ceiling fans, tiled floors, colorful walls as appropriate
7. For city targeting: add the city name and 1-2 architectural/environmental cues in the prompt
8. Default negative_prompt:
   "blurry, low quality, extra fingers, watermark, cartoon, anime, 3d render, plastic skin, airbrushed, stock photo feel, European-only features, cold grey lighting, generic office"

---

## Camera Lens Quick Reference

| Lens | Best For | Aula360 Use Case |
|------|----------|-----------------|
| 24mm | Wide scene | Full classroom or school patio with context |
| 35mm | Environmental portrait | Teacher/parent with device, person in school setting |
| 50mm | General | Rector at desk, versatile school scenes |
| 85mm | Portrait | Testimonial headshots, close emotional expressions |
| 105mm | Detail | Tablet/laptop screen close-up, hand on device |
| 200mm | Isolation | Device floating against blurred school background |

---

## Example Prompts

### Rector Testimonial — Cartagena
```json
{
  "prompt": "Colombian woman in her early 50s, Afro-Colombian, warm confident smile, wearing a navy blazer over a white blouse, standing in a colonial school corridor in Cartagena, colorful arched walls behind her, ceiling fan visible, soft warm natural light through open archway, she is the school rector, natural skin texture, real hair, authentic expression, not stock photo",
  "negative_prompt": "blurry, extra fingers, watermark, cartoon, plastic skin, airbrushed, stock photo feel, European-only features, cold lighting",
  "settings": {
    "resolution": "1536x1536",
    "aspect_ratio": "1:1",
    "style": "editorial-professional",
    "lighting": "warm-tropical-window",
    "camera": {
      "lens": "85mm",
      "angle": "eye-level",
      "framing": "medium",
      "height": "eye-level",
      "depth_of_field": "shallow",
      "focus": "subject"
    },
    "color_grading": "warm"
  }
}
```

### Teacher in Action — Barranquilla
```json
{
  "prompt": "Colombian male teacher in his 30s, costeño mestizo, wearing a light blue button-up shirt, sitting at a classroom desk in Barranquilla, holding a Samsung tablet showing a grade spreadsheet with student names in Spanish, open classroom behind him with students in white school uniforms, ceiling fan, bright tropical window light, engaged and focused expression, natural skin texture",
  "negative_prompt": "blurry, extra fingers, watermark, cartoon, plastic skin, airbrushed, stock photo, cold grey lighting, generic office",
  "settings": {
    "resolution": "1536x1536",
    "aspect_ratio": "4:5",
    "style": "lifestyle-in-context",
    "lighting": "warm-tropical-window",
    "camera": {
      "lens": "35mm",
      "angle": "eye-level",
      "framing": "medium",
      "height": "eye-level",
      "depth_of_field": "moderate",
      "focus": "subject"
    },
    "color_grading": "warm"
  }
}
```

### Parent Checking Grades — Santa Marta
```json
{
  "prompt": "Colombian woman in her late 30s, mestiza costeña, casual home clothes, sitting on a sofa in a modest Santa Marta apartment, holding a Samsung Galaxy phone with a student grade portal visible on screen showing a child's academic report in Spanish, relieved and happy expression, warm afternoon light through window, coastal city feel, natural skin texture",
  "negative_prompt": "blurry, extra fingers, watermark, cartoon, plastic skin, airbrushed, stock photo feel, cold grey lighting",
  "settings": {
    "resolution": "1024x1024",
    "aspect_ratio": "9:16",
    "style": "ugc-selfie",
    "lighting": "warm-tropical-window",
    "camera": {
      "lens": "35mm",
      "angle": "eye-level",
      "framing": "medium",
      "height": "eye-level",
      "depth_of_field": "shallow",
      "focus": "subject"
    },
    "color_grading": "warm"
  }
}
```
