# CLAUDE.md

Kirby CMS website with Tailwind CSS 4, Webpack, and PHP 8.1+.
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
| `.claude/settings.json` | Claude Code permissions and hooks        |
| `.claude/skills/cc-init/SKILL.md` | TODO: add description       |
| `.claude/skills/cc-optimize/SKILL.md` | TODO: add description       |
| `.claude/skills/openspec-apply-change/SKILL.md` | TODO: add description       |
| `.claude/skills/openspec-archive-change/SKILL.md` | TODO: add description       |
| `.claude/skills/openspec-bulk-archive-change/SKILL.md` | TODO: add description       |
| `.claude/skills/openspec-continue-change/SKILL.md` | TODO: add description       |
| `.claude/skills/openspec-explore/SKILL.md` | TODO: add description       |
| `.claude/skills/openspec-ff-change/SKILL.md` | TODO: add description       |
| `.claude/skills/openspec-new-change/SKILL.md` | TODO: add description       |
| `.claude/skills/openspec-onboard/SKILL.md` | TODO: add description       |
| `.claude/skills/openspec-sync-specs/SKILL.md` | TODO: add description       |
| `.claude/skills/openspec-verify-change/SKILL.md` | TODO: add description       |
| `composer.json` | PHP dependencies (Kirby CMS, plugins)        |
| `.editorconfig` | Editor formatting rules (indent, charset, EOL)        |
| `.gitignore` | Git ignore patterns        |
| `.mcp.json` | MCP server config        |
| `package.json` | npm dependencies, build scripts        |
| `postcss.config.js` | PostCSS/Tailwind CSS processing config        |
| `.prettierrc.json` | Prettier formatting rules        |
| `webpack.config.js` | Webpack bundler config (JS, CSS, assets)        |

## Configuration Management

When running config optimization or audit tasks, always check for duplicate entries across `.claude/settings.json`, `.claude/settings.local.json`, and project-level configs before proposing changes.

## Learnings

When the user corrects a mistake or points out a recurring issue, append a one-line
summary to .claude/learnings.md. Don't modify CLAUDE.md directly.

## Compact Instructions

When compacting: keep test commands, current file paths, open TODOs, and the last diff summary. Drop intermediate reasoning.

## OpenSpec

This project uses OpenSpec for structured change management.
See `openspec/config.yaml` for workflow configuration.

## Handoff

Before ending a session, the user may invoke `/handoff` to create a machine-transfer summary.
When resuming work, always check if HANDOFF.md exists in the project root. If it does, read it
first and continue from where it left off. After confirming the context is restored, delete the file.
