# Frontend — Next.js 16 Static Site

## Tech Stack

- **Framework:** Next.js 16 (App Router, `output: "export"`)
- **Language:** TypeScript (strict mode)
- **Styling:** Tailwind CSS v4 (`@import "tailwindcss"`, `@theme inline`)
- **UI Library:** shadcn/ui v4 (uses `@base-ui/react`, NOT Radix UI)
- **Icons:** lucide-react v1.17 (no brand icons — use inline SVGs for GitHub/LinkedIn)
- **Animations:** tw-animate-css (via `@import "tw-animate-css"`)

## Project Structure

```
frontend/src/
├── app/
│   ├── [lang]/
│   │   ├── page.tsx                    # Home page (SSG)
│   │   ├── layout.tsx                  # Locale layout (Header/Footer)
│   │   ├── not-found.tsx               # 404 page
│   │   ├── contact/page.tsx            # Contact page
│   │   ├── experience/page.tsx         # Experience timeline page
│   │   ├── portfolio/
│   │   │   ├── page.tsx                # Portfolio grid page
│   │   │   └── [slug]/page.tsx         # Project detail page
│   ├── layout.tsx                      # Root layout (theme script, fonts)
│   ├── page.tsx                        # Root redirect
│   ├── globals.css                     # Global styles, CSS variables
│   ├── sitemap.ts                      # Dynamic sitemap generation
│   └── robots.ts                       # Robots.txt generation
├── components/
│   ├── home/                           # Home page components
│   │   ├── HeroSection.tsx
│   │   ├── TechStackSection.tsx
│   │   └── FeaturedProjects.tsx
│   ├── layout/                         # Layout components
│   │   ├── Header.tsx
│   │   ├── Footer.tsx
│   │   ├── ThemeSwitcher.tsx
│   │   └── LanguageSwitcher.tsx
│   ├── portfolio/                      # Portfolio components
│   │   ├── ProjectCard.tsx
│   │   ├── ProjectFilter.tsx
│   │   ├── ScreenshotGallery.tsx
│   │   └── Lightbox.tsx
│   ├── experience/                     # Experience components
│   │   └── ExperienceTimeline.tsx
│   ├── shared/                         # Shared components
│   │   ├── SocialLinks.tsx
│   │   ├── FadeIn.tsx
│   │   └── JsonLd.tsx
│   └── ui/                             # shadcn/ui components
│       ├── button.tsx
│       ├── card.tsx
│       ├── badge.tsx
│       ├── dialog.tsx
│       ├── dropdown-menu.tsx
│       ├── separator.tsx
│       ├── sheet.tsx
│       └── skeleton.tsx
└── lib/
    ├── types.ts                        # TypeScript interfaces
    ├── utils.ts                        # Utility functions (cn)
    ├── data/
    │   ├── index.ts                    # Data loader with tech ID resolution
    │   ├── profile.json
    │   ├── technologies.json
    │   ├── experience.json
    │   └── projects.json
    └── i18n/
        ├── index.ts                    # i18n helpers (getDictionary, getLocalizedField, formatDate)
        └── dictionaries/
            ├── ru.json
            └── en.json
```

## Key Patterns

### Async Params (Next.js 15+)
```typescript
export default async function Page({
  params,
}: {
  params: Promise<{ lang: string }>;
}) {
  const { lang } = await params;
}
```

### generateStaticParams
```typescript
export async function generateStaticParams() {
  return locales.map((locale) => ({ lang: locale }));
}
```

### generateMetadata (per-page SEO)
```typescript
export async function generateMetadata({
  params,
}: {
  params: Promise<{ lang: string }>;
}): Promise<Metadata> {
  const { lang } = await params;
  // Return localized metadata with alternates
}
```

### Technology ID Resolution
Projects JSON stores technology IDs (numbers). The data index resolves them:
```typescript
const projects: Project[] = rawProjects.map((p) => ({
  ...p,
  technologies: resolveTechnologies(p.technologies),
}));
```

### Localized Fields
```typescript
const title = getLocalizedField(project.title_ru, project.title_en, locale);
```

### Button without asChild
```tsx
<Link href="/portfolio">
  <Button>View Portfolio</Button>
</Link>
```

### Ref-based Initialization Guard (React 19)
```typescript
const initialized = useRef(false);
useEffect(() => {
  if (initialized.current) return;
  initialized.current = true;
  // Your effect logic here
}, []);
```

## Important Rules

1. **No Google Fonts** — use system font stack only
2. **No Node.js on server** — everything must be static HTML (`output: "export"`)
3. **No Radix UI** — shadcn/ui v4 uses `@base-ui/react`
4. **No `asChild` prop** — use `render` prop or wrap in `<a>`/`<Link>`
5. **No brand icons from lucide-react** — use inline SVGs for GitHub/LinkedIn
6. **No `setState` in effects** — use ref-based initialization guards
7. **Always use `params: Promise<...>`** — async params pattern
8. **Always use `output: "export"`** — static site generation
9. **Always use `trailingSlash: true`** — for shared hosting compatibility
10. **Always use `images: { unoptimized: true }`** — no image optimization needed

## Build Commands

```bash
npm run dev      # Development server (http://localhost:3000)
npm run build    # Production build (static export to out/)
npx next build   # Build with verbose output
