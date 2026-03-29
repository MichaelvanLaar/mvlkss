# CLAUDE.md

Kirby CMS website with Tailwind CSS 4, Webpack, and PHP 8.1+.
Flat-file CMS — no database. Deployed via GitHub Actions.

@openspec/project.md for full architecture, conventions, and constraints.

## Build & Test

```bash
npm run dev-server     # PHP server (localhost:8000) + watch mode
npm run dev            # Watch mode only (remote dev, no PHP server)
npm run build          # Production build (run before committing)
```

## Critical Rules

- CSS must be imported via `/src/js/maincss.js` for Webpack — do not delete this file.
- All Tailwind utility classes must appear as **complete strings** in PHP files (no dynamic class composition).
- Custom colors beyond Tailwind defaults: add to `tailwind.config.js` first, then use in `site/config/config.php`.
- No Node.js on production — commit built assets.
- Conventional Commits with gitmoji.

## Key Files

- Brand colors & spacing: `site/config/config.php`
- Image config: `site/config/thumb-config.php`
- Page builder controller: `site/snippets/fields/page-builder.controller.php`
- Page builder blueprint: `site/blueprints/fields/page-builder.yml`

## Compact Instructions

When compacting: keep test commands, current file paths, open TODOs, and the last diff summary. Drop intermediate reasoning.

## OpenSpec

This project uses OpenSpec for structured change management.
See `openspec/config.yaml` for workflow configuration.
