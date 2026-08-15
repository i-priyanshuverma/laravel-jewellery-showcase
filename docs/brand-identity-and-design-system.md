# Sonar Haat &mdash; Brand Identity & Design System

**Design System Deliverables:**
* 🌐 **[Interactive HTML Brand Board](file:///Users/priyanshu/Desktop/Projects/laravel-jewellery-showcase/design/brand-identity-board.html)** (`design/brand-identity-board.html`)
* 🎨 **[Figma Design Tokens JSON (W3C)](file:///Users/priyanshu/Desktop/Projects/laravel-jewellery-showcase/design/figma-design-tokens.json)** (`design/figma-design-tokens.json`)
* 📖 **[Brand Identity Guidelines](file:///Users/priyanshu/Desktop/Projects/laravel-jewellery-showcase/design/brand-identity-guidelines.md)** (`design/brand-identity-guidelines.md`)

---

## 1. Brand Philosophy & Positioning

**Sonar Haat** (बंगाली / हिन्दी: *সোনার হাট / सोनार हाट* &mdash; *"The Golden Marketplace"*) is a luxury multi-vendor showcase bridging artisanal heritage goldsmiths with modern affluent digital buyers.

```
┌────────────────────────────────────────────────────────┐
│                   BRAND PILLARS                        │
├──────────────────────┬─────────────────────────────────┤
│ 💎 Regal Heritage    │ 18K/22K Gold & Solitaire Craft  │
│ ⚡ Concurrency Trust │ 10-Minute Guaranteed Stock Hold │
│ 🛡️ Strict Compliance │ OWASP Hardened & Verified Scale │
└──────────────────────┴─────────────────────────────────┘
```

---

## 2. Brand Logomark & Vector Geometry

The Sonar Haat emblem is engineered using the geometric **Table & Pavilion** proportions of a brilliant-cut solitaire diamond:

```
          / \
        /     \
      /   18K   \
    /_____________\
    \             /
     \  PAVILION /
      \         /
       \   💎  /
        \     /
         \   /
           v
```

* **Symbolism:** The upper octagon represents the diamond table; the lower prism symbolizes solid 24K bullion bars.
* **Palette Mode:** Adaptive gradient fill transitioning from Imperial Champagne Gold (`#fcd34d`) to Heritage Amber (`#d97706`).

---

## 3. Color Token Architecture

### Primary Imperial Gold Palette (11 Steps)

| Token | HEX Code | RGB | Role / Usage |
|---|---|---|---|
| `gold-50` | `#fffdf5` | `255, 253, 245` | Background Tint / Subtle Highlights |
| `gold-100` | `#fff9e6` | `255, 249, 230` | Hover States / Pill Backgrounds |
| `gold-200` | `#fef0bf` | `254, 240, 191` | Subtle Card Borders |
| `gold-300` | `#fde38a` | `253, 227, 138` | Shimmer Accent Highlights |
| `gold-400` | `#fcd34d` | `252, 211, 77` | Gold Gradient Start Tone |
| `gold-500` | `#d97706` | `217, 119, 6` | Primary Brand CTA / Buttons |
| `gold-600` | `#b45309` | `180, 83, 9` | Button Hover / Focus Ring |
| `gold-700` | `#92400e` | `146, 64, 14` | High-Contrast Typography |
| `gold-800` | `#78350f` | `120, 53, 15` | Dark Theme Accent Lines |
| `gold-900` | `#451a03` | `69, 26, 3` | Deep Gold Shadows |
| `gold-950` | `#260e02` | `38, 14, 2` | Midnight Gold Canvas |

---

## 4. Typography Scale

* **Primary Body & UI Stack:** Google Font **`Inter`** with fallbacks `['Inter', 'Helvetica', 'Roboto', 'Arial', 'sans-serif']`.
* **Editorial & Title Stack:** **`Playfair Display`** (600/700 serif italic).

| Level | Size | Weight | Line Height | Usage |
|---|---|---|---|---|
| **Display 1** | `48px / 3rem` | `800 Bold` | `1.15` | Landing Showcase Hero Title |
| **Heading 1** | `30px / 1.875rem` | `700 Bold` | `1.25` | Section Headers & Product Title |
| **Heading 2** | `20px / 1.25rem` | `600 SemiBold`| `1.3` | Category & Card Headers |
| **Body Large** | `16px / 1rem` | `500 Medium` | `1.5` | Lead Paragraphs & Descriptions |
| **Body Small** | `14px / 0.875rem` | `400 Regular` | `1.5` | Table Rows & Form Inputs |
| **Micro / Mono**| `12px / 0.75rem` | `700 Bold` | `1.0` | SKU Codes & Hold Countdown Timers |

---

## 5. UI Micro-Components & Live Badges

### 10-Minute Guaranteed Hold Countdown Badge:
```
┌────────────────────────────────────────────────────────┐
│  ⏱️ 09:48 REMAINING  •  1 Unit Held for You            │
└────────────────────────────────────────────────────────┘
```
* **Styling:** Amber gradient border with subtle pulsing dot indicator (`animate-pulse`).

---

## 6. How to Import Tokens into Figma

1. Open **Figma** and install the **Tokens Studio for Figma** plugin (free).
2. Go to **Settings / Tools** &rarr; Click **Load from JSON**.
3. Select [`design/figma-design-tokens.json`](file:///Users/priyanshu/Desktop/Projects/laravel-jewellery-showcase/design/figma-design-tokens.json).
4. All styles, gradients, and font scales will sync immediately to your Figma canvas!
