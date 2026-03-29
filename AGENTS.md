# Project: mvlkss

Kirby CMS website (PHP 8.1+, flat-file) with Tailwind CSS 4.x and Webpack.
No database. Content stored as text files in `/content/`.

## Stack

Kirby 5.x, PHP 8.1+, Tailwind CSS 4.x, PostCSS, Webpack, Babel, npm scripts, Caddy/Apache.

## Architecture

- Templates: `site/templates/`
- Snippets (reusable components): `site/snippets/`
- Blueprints (Panel field definitions): `site/blueprints/`
- Controllers (logic separation): `site/controllers/`
- CSS source: `src/css/main.css` → Tailwind + PostCSS → `assets/css/main.css`
- JS source: `src/js/main.js` → Babel + Webpack → `assets/js/main.js`
- CSS import workaround: `src/js/maincss.js` (required for Webpack processing)
- Brand colors: centralized in `site/config/config.php`
- Page builder: layout field + snippet controllers

## Commands

```bash
npm run dev-server     # PHP dev server + watch
npm run dev            # Watch mode only
npm run build          # Production build
```

## Conventions

- Conventional Commits with gitmoji.
- Tailwind classes must appear as complete strings in PHP files.
- No Node.js on production — commit built assets.
- Custom colors: add to `tailwind.config.js` before using in config.php.

## Safety

- Never read or expose `.env` files or secrets.
- Never modify CI/deployment workflows without explicit approval.
- Run `npm run build` and verify before committing production code.

## OpenSpec

This project uses OpenSpec for structured change management. See `openspec/config.yaml`.
