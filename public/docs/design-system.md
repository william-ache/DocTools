# Design System Document: Ethereal ORL

## 1. Overview & Creative North Star: "The Clinical Sanctuary"
This design system moves beyond the cold, sterile aesthetic of traditional medical portals to create a high-end editorial experience for Ethereal ORL. Our Creative North Star is **"The Clinical Sanctuary"**—a digital environment that mimics the clarity of breath and the precision of sound.

In the context of Otorhinolaryngology (Ear, Nose, and Throat), the UI must feel airy yet authoritative. We achieve this through intentional asymmetry, breaking the rigid grid to allow content to "breathe" like a clear nasal passage. By layering translucent surfaces and utilizing high-contrast typography, we transform a clinical tool into a premium wellness experience.

---

## 2. Colors: Tonal Depth & Patient-Admin Logic
The palette is rooted in medical trust but elevated through Material Design 3 logic. We use color not just for branding, but as a functional "Wayfinding" tool.

### Functional Logic
*   **Patient-Centric (Deep Blue):** Uses the `primary` (#00478d) and `primary_container` (#005eb8) tokens. This conveys stability and surgical expertise.
*   **Admin-Centric (Celeste):** Uses the `secondary` (#006687) and `secondary_container` (#80d3fd) tokens. Optimizes focus for staff.

### The "No-Line" Rule
Standard 1px solid borders are strictly prohibited. Boundaries must be defined through:
1.  **Background Shifts:** `surface_container_low` card on a `surface` background.
2.  **Tonal Transitions:** `surface_container_highest` for sidebars against a `surface_bright` canvas.

### Signature Textures
Main CTAs use a linear gradient from `primary` to `primary_container` at a 135° angle.

---

## 3. Typography: The Editorial Voice
**Font:** Manrope (Geometric clarity, modern humanist feel).

*   **Display-LG (3.5rem):** Hero sections. -0.02em letter spacing.
*   **Headline-MD (1.75rem):** Specialty sections (Ear, Nose, Throat).
*   **Body-LG (1rem):** Patient education and clinical findings.
*   **Label-MD (0.75rem):** All-caps, +0.05em spacing for metadata.

---

## 4. Elevation & Depth: Tonal Layering
Depth is an atmospheric quality, not structural.

*   **Layering Principle:** Treat UI as stacked sheets of fine medical-grade paper.
*   **Glassmorphism:** `surface_container_lowest` at 80% opacity with `24px` backdrop-blur.
*   **Ambient Shadows:** `on_surface` color at 6% opacity, `40px` blur, `10px` Y-offset.
*   **Ghost Borders:** Use `outline_variant` at 15% opacity for input fields only.

---

## 5. Components: Bespoke ENT Elements

### Buttons (Roundedness: `xl` - 0.75rem)
*   **Primary:** Solid `primary` with gradient. "Start Consultation."
*   **Secondary:** `secondary_fixed_dim` with `on_secondary_fixed_variant`.
*   **Tertiary:** No background, `primary` text.

### Specialized ENT Components
*   **Auditory Frequency Slider:** `primary` track, `surface_container_highest` thumb.
*   **Symptom Mapping Grid:** Asymmetric selection chips transitioning to `primary`.

---

## 6. Do’s and Don'ts

### Do:
*   Use asymmetrical layouts.
*   Prioritize "negative space" to reduce patient anxiety.
*   Use `surface_container` variations to nest content.

### Don't:
*   Use 1px solid lines to separate data.
*   Use standard "Web Blue."
*   Use sharp corners (Stay on `xl` or `lg` scale).
