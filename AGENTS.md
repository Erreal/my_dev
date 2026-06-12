# Erreality.ru — Monorepo Overview

## Project Structure

This is a monorepo for the personal portfolio website [erreality.ru](https://erreality.ru). It contains three main parts:

```
/
├── frontend/          # Next.js 16 static site (TypeScript, Tailwind CSS v4, shadcn/ui v4)
├── admin/             # PHP 8.3+ custom MVC admin panel
├── scripts/           # CLI utilities (JSON export for CI/CD)
├── .github/           # GitHub Actions workflows
├── plans/             # Architecture and planning documents
├── .htaccess          # Root Apache rewrite rules
├── AGENTS.md          # This file — AI agent context
└── CLAUDE.md          # Claude AI entry point
```

## Architecture Flow

```
Admin Panel (PHP) → MySQL Database → JSON Export → Next.js Build → Static HTML/CSS/JS
```

1. Admin creates/edits content via PHP admin panel
2. Data is stored in MySQL database
3. JSON export script writes data to `frontend/src/lib/data/*.json`
4. Next.js imports JSON at build time and generates static pages
5. Static files are deployed to shared hosting

## Key Technical Decisions

- **Next.js 16** with `output: "export"` — pure static site, no Node.js runtime on server
- **shadcn/ui v4** uses `@base-ui/react` (NOT Radix UI). No `asChild` prop — use `render` prop or wrap in `<a>`/`<Link>`
- **Tailwind CSS v4** uses `@import "tailwindcss"` syntax, `@theme inline` for custom properties
- **React 19 strict ESLint** — no `setState` in effects. Use ref-based initialization guards
- **lucide-react v1.17** — no brand icons (Github, Linkedin). Use inline SVGs
- **App Router** with `params: Promise<{ lang: string }>` (async params in Next.js 15+)
- **URL-based i18n** — `/ru/...` and `/en/...` paths with dictionary-based translations
- **Technology ID resolution** — projects JSON stores technology IDs (numbers), data index resolves to full Technology objects
- **PHP PSR-4 autoloading** — `App\` namespace → `admin/src/`

## Build Output

30 static assets generated:
- `/` (root redirect)
- `/[lang]` ×2 (ru, en)
- `/[lang]/contact` ×2
- `/[lang]/experience` ×2
- `/[lang]/portfolio` ×2
- `/[lang]/portfolio/[slug]` ×16 (8 projects × 2 languages)
- `/robots.txt`
- `/sitemap.xml`

## Important Constraints

1. **No Google Fonts** — system font stack only (`-apple-system, BlinkMacSystemFont, ...`)
2. **No Node.js on server** — everything must be static HTML
3. **No Radix UI** — shadcn/ui v4 uses `@base-ui/react`
4. **No `asChild` prop** — use `render` prop pattern or wrap in `<a>`/`<Link>`
5. **No brand icons from lucide-react** — use inline SVGs for GitHub/LinkedIn
6. **No `setState` in effects** — use ref-based initialization guards for React 19

## Quick Commands

```bash
# Frontend
cd frontend && npm run dev     # Development server
cd frontend && npm run build   # Production build (static export)
cd frontend && npx next build  # Build with output

# Admin (requires PHP + MySQL)
# Point web server to admin/public/
# Run admin/install.php for first-time setup

# JSON Export (after admin is set up)
php scripts/export-json.php

# Deployment
# Push to main branch → GitHub Actions auto-deploys
# Or manually: ./scripts/deploy.sh
```

## Required GitHub Secrets for Deployment

| Secret | Description |
|--------|-------------|
| `SSH_HOST` | Server hostname |
| `SSH_USER` | SSH username |
| `SSH_KEY` | SSH private key |
| `SSH_PORT` | SSH port (default: 22) |
| `SSH_KNOWN_HOSTS` | Server host key |
| `DEPLOY_PATH` | Server project root path |
| `DB_HOST` | Database host |
| `DB_PORT` | Database port |
| `DB_NAME` | Database name |
| `DB_USER` | Database user |
| `DB_PASS` | Database password |