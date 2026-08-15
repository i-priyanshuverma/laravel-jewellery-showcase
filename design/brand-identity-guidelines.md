# Brand Identity & Design System Specification

**Project:** Artisan Jewellery Showcase & Reserve  
**Design Lead:** Priyanshu Verma  
**Version:** 1.0.0 (Production Release)  
**Deliverables Included:**
- [Design Tokens (Figma JSON)](file:///Users/priyanshu/Desktop/Projects/laravel-jewellery-showcase/design/figma-design-tokens.json)
- [Interactive Brand Board (HTML Canvas)](file:///Users/priyanshu/Desktop/Projects/laravel-jewellery-showcase/design/brand-identity-board.html)

---

## 1. Brand Philosophy & Aesthetic DNA

The **Artisan Jewellery Showcase** design language is engineered around **Luxury Atelier Sophistication** combined with **Modern Real-Time Precision**.

### Key Aesthetic Pillars:
1. **Warm Obsidian Dark Mode & Platinum Light Mode:**
   - Dark Mode: `#020617` (Obsidian Canvas) + `#0F172A` (Surface Fill) with subtle gold edge highlights.
   - Light Mode: `#F8FAFC` (Platinum Snow) with warm amber borders.
2. **Imperial Gold Metallic Spectrum:**
   - Warm champagne `#FDE68A` to vibrant amber `#F59E0B` and antique bronze `#B45309`.
3. **High-Contrast Micro-Typography:**
   - Geometric sans (`Plus Jakarta Sans`) with tight tracking and uppercase 10px eyebrow headers (`tracking-widest`).
4. **Real-Time Visual State Language:**
   - Dynamic pulsing badges for 10-minute stock holds (`#10B981` Emerald, `#F43F5E` Ruby Rose urgency).
   - Gemstone purple accents (`#A855F7`) for solitaire carats and cut clarity.

---

## 2. Color Palette & Token Definitions

### A. Primary Brand (Imperial Gold)
| Token | Hex Code | Purpose |
|---|---|---|
| `brand-50` | `#FFFBEB` | Lightest champagne tint (hover fills) |
| `brand-100` | `#FEF3C7` | Badge background (Light mode) |
| `brand-200` | `#FDE68A` | Gold border highlight & facet glow |
| `brand-300` | `#FCD34D` | Luminous gold accent |
| `brand-400` | `#FBBF24` | Primary gold text (Dark mode) |
| `brand-500` | `#F59E0B` | **Primary Brand Color (Base)** |
| `brand-600` | `#D97706` | Primary CTA Button gradient base |
| `brand-700` | `#B45309` | Antique bronze borders |
| `brand-800` | `#92400E` | High-contrast dark amber |
| `brand-900` | `#78350F` | Shadow accent |
| `brand-950` | `#451A03` | Deepest gold shadow |

### B. Surface & Elevation (Obsidian Slate)
| Token | Hex Code | Purpose |
|---|---|---|
| `slate-50` | `#F8FAFC` | Light mode canvas background |
| `slate-800` | `#1E293B` | Card borders & recessed wells (Dark Mode) |
| `slate-900` | `#0F172A` | Primary card background (Dark Mode) |
| `slate-950` | `#020617` | Root background (Dark Mode) |

### C. Semantic Accents
| Token | Hex Code | Meaning |
|---|---|---|
| **Emerald 500** | `#10B981` | Certified Approved Vendor, Stock In-Hand, Active Hold |
| **Amethyst 500** | `#A855F7` | Gemstones, Diamonds, VVS Clarity attributes |
| **Rose 500** | `#F43F5E` | Out of Stock, Delisted, Hold Expired |

---

## 3. Typography System

- **Primary Font Family:** `Plus Jakarta Sans`, sans-serif
- **Data / SKU Monospace:** `JetBrains Mono` / `ui-monospace`

### Hierarchy Scale:
1. **Display 3XL (36px / ExtraBold / -0.02em):** Hero Showcase titles & catalogue banners.
2. **Heading XL (24px / ExtraBold):** Product Detail titles & dashboard metrics.
3. **Heading MD (16px / Bold):** Section headers, table labels.
4. **Body Regular (14px / Regular):** Descriptions, specifications, instructions.
5. **Micro Eyebrow (10px / ExtraBold / +0.1em uppercase):** Vendor badges, category chips, SKU tags.

---

## 4. UI Components & Micro-Interactions

### A. Gold Gradient CTA Buttons
- **Gradient:** `linear-gradient(135deg, #d97706 0%, #f59e0b 50%, #b45309 100%)`
- **Border Radius:** `12px` (`rounded-xl` to `rounded-2xl`)
- **Hover State:** `brightness(1.1) + translateY(-1px) + shadow-lg`

### B. Real-Time Hold Countdown Pill
- **Container:** `px-2.5 py-0.5 rounded-full bg-emerald-50 dark:bg-emerald-950 border border-emerald-300 dark:border-emerald-700`
- **Icon:** SVG clock / timer spinner
- **Font:** `11px font-mono font-bold text-emerald-700 dark:text-emerald-400`

### C. Luxury Product Cards
- **Aspect Ratio:** `4:3` with 16px corner radius.
- **Glass Panel:** `backdrop-filter: blur(12px)` + `rgba(255,255,255,0.06)` border.
- **Hover Effect:** Product image scale `1.05` on container hover.

---

## 5. How to Load this Design System into Figma

1. **Option 1: Tokens Studio for Figma Plugin**
   - Open your Figma file.
   - Run the **Tokens Studio for Figma** plugin.
   - Click **Import** &rarr; Select [`design/figma-design-tokens.json`](file:///Users/priyanshu/Desktop/Projects/laravel-jewellery-showcase/design/figma-design-tokens.json).
   - All color variables, typography styles, shadows, and radiuses will be instantly generated.

2. **Option 2: Convert Visual Canvas via HTML to Design**
   - Open [`design/brand-identity-board.html`](file:///Users/priyanshu/Desktop/Projects/laravel-jewellery-showcase/design/brand-identity-board.html) in your browser.
   - In Figma, run the **html.to.design** plugin.
   - Paste the local URL / HTML code to generate 100% editable vector components, auto-layout frames, and logo marks.

3. **Option 3: Direct Vector Copy**
   - The logo marks and UI elements in `brand-identity-board.html` are clean SVG vectors and can be directly copied and pasted onto any Figma artboard.
