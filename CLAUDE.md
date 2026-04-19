# CLAUDE.md

Kirby CMS website with Tailwind CSS 4, Webpack, and PHP 8.2+.
Flat-file CMS — no database. Deployed via GitHub Actions.

@openspec/project.md for full architecture, conventions, and constraints.

## Setup

After cloning, enable the Git hooks:

```bash
git config core.hooksPath .githooks
```

## Build & Test

```bash
npm run dev-server     # PHP server (localhost:8000) + watch mode
npm run dev            # Watch mode only (remote dev, no PHP server)
npm run build          # Production build (run before committing)
npm test               # Full suite: lint + PHPUnit + Vitest (see TESTING.md)
npm run test:js        # Vitest (jsdom) only
npm run test:php       # PHPUnit only
npm run test:phpstan   # PHPStan static analysis
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

### Key Config Files

| File | Purpose |
|------|---------|
| `.claude/settings.json` | Claude Code permissions and hooks               |
| `composer.json` | PHP dependencies (Kirby CMS, plugins)           |
| `.editorconfig` | Editor formatting rules (indent, charset, EOL)  |
| `eslint.config.js` | ESLint config (flat config) for JS linting      |
| `.github/workflows/tests.yml` | CI workflow: lint, PHPUnit, Vitest, PHPStan     |
| `.gitignore` | Git ignore patterns                             |
| `.mcp.json` | MCP server config                               |
| `package.json` | npm dependencies, build scripts                 |
| `postcss.config.js` | PostCSS/Tailwind CSS processing config          |
| `.prettierignore` | Paths excluded from Prettier formatting         |
| `.prettierrc.json` | Prettier formatting rules                       |
| `.stylelintrc.json` | Stylelint rules for CSS/Tailwind linting        |
| `vitest.config.js` | Vitest (jsdom) config for JS unit tests         |
| `webpack.config.js` | Webpack bundler config (JS, CSS, assets)        |
| `.yamllint.json` | yamllint rules for blueprint/workflow YAML      |

Skills in `.claude/skills/*/SKILL.md` are intentionally omitted — each skill's purpose lives in its own YAML frontmatter.

## Learnings

When the user corrects a mistake or points out a recurring issue, append a one-line
summary to .claude/learnings.md. Don't modify CLAUDE.md directly.

## Compact Instructions

When compacting: keep test commands, current file paths, open TODOs, and the last diff summary. Drop intermediate reasoning.

## OpenSpec

This project uses OpenSpec for structured change management.
See `openspec/config.yaml` for workflow configuration.
