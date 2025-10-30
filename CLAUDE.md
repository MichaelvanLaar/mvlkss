<!-- OPENSPEC:START -->

# OpenSpec Instructions

These instructions are for AI assistants working in this project.

Always open `@/openspec/AGENTS.md` when the request:

- Mentions planning or proposals (words like proposal, spec, change, plan)
- Introduces new capabilities, breaking changes, architecture shifts, or big performance/security work
- Sounds ambiguous and you need the authoritative spec before coding

Use `@/openspec/AGENTS.md` to learn:

- How to create and apply change proposals
- Spec format and conventions
- Project structure and guidelines

Keep this managed block so 'openspec update' can refresh the instructions.

<!-- OPENSPEC:END -->

# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a **Kirby CMS-based website** using Tailwind CSS for styling and Webpack for asset compilation. The project is designed for deployment via GitHub Actions, with the `/kirby` core directory committed to the repository for simplicity.

## Tech Stack

- **CMS**: Kirby 5.x (PHP-based flat-file CMS)
- **CSS**: Tailwind CSS 4.x with PostCSS
- **JavaScript**: ES6+ transpiled with Babel via Webpack
- **Build Tools**: Webpack, PostCSS, npm scripts
- **PHP**: >= 8.1.0

## Development Commands

### Start Development Server

```bash
npm run dev-server
```

Starts PHP's built-in server on `http://localhost:8000` and runs all build tools in watch mode.

### Build Tools Only (Remote Development)

```bash
npm run dev
```

Runs Tailwind CSS and Webpack in watch mode without starting the PHP server.

### Production Build

```bash
npm run build
```

Generates optimized CSS and JS files with cache busting (`hashup`). **Always run before committing production-ready code.**

### Dependency Updates

```bash
npm run utility-dependencies-update-check  # Check for updates
npm run utility-dependencies-update        # Install updates
```

Updates both Composer and npm packages. Updates should be done on development machines only.

### Utility Scripts

```bash
npm run utility-git-branches-clean-up      # Clean merged local branches
npm run utility-kirby-cache-prefill        # Prefill Kirby page cache
```

## Architecture

### Directory Structure

```
/kirby                      # Kirby CMS core (committed for deployment)
/site
  /blueprints               # Panel field/page definitions (YAML)
  /config                   # Kirby configuration files
    config.php              # Main config with brand colors & spacing
    thumb-config.php        # Responsive image configuration
  /controllers              # Page controllers
  /snippets                 # Reusable PHP templates
    /base                   # Core snippets (header, footer, menus)
    /blocks                 # Page builder block snippets
    /fields                 # Custom field snippets
  /templates                # Page templates
/src
  /css                      # Tailwind CSS input files
  /js                       # JavaScript source files
    /main-partials          # Modular JS components
  /images                   # Source images (copied to /assets)
  /fonts                    # Source fonts (copied to /assets)
/assets                     # Generated assets (git-ignored except production builds)
  /css                      # Compiled CSS (main.css)
  /js                       # Bundled JS (main.js)
/content                    # Kirby content files
/vendor                     # Composer dependencies (committed)
```

### Build Pipeline

1. **CSS**: `/src/css/main.css` → Tailwind + PostCSS → `/assets/css/main.css`
    - **Important**: CSS must be imported via `/src/js/maincss.js` for Webpack processing
    - Tailwind scans all PHP files for utility classes
    - Production: minified with cssnano

2. **JavaScript**: `/src/js/main.js` (imports partials) → Babel + Webpack → `/assets/js/main.js`
    - Also processes: `/src/js/maincss.js` (CSS import), `/src/js/highlightjscss.js`
    - Separate your code into partials in `/src/js/main-partials/`

3. **Assets**: Static files from `/src/images` and `/src/fonts` copied to `/assets/`

### Brand Color System

Brand colors are centrally configured in [site/config/config.php](site/config/config.php) as the `$selectableBrandColors` array. Each color defines:

- Light/dark mode background, border, and text utility classes
- Contrast colors for text legibility (using Tailwind Typography plugin)
- Panel labels for editor selection

**Critical**: All Tailwind utility classes used in the color config must appear as complete strings in PHP files for proper purging. Custom colors beyond Tailwind defaults should be added to `tailwind.config.js` first.

### Responsive Images

Configured in [site/config/thumb-config.php](site/config/thumb-config.php):

- Predefined widths: 200, 400, 600, 800, 1000, 1600, 2000
- Format conversion: PNG→WebP, JPG→AVIF/WebP (when ImageMagick available)
- Automatic fallback to GD library if ImageMagick unavailable
- Srcsets and presets auto-generated for templates

### Page Builder System

Uses Kirby's layout field with snippet controllers (via `kirby-snippet-controller` plugin):

- **Controller**: [site/snippets/fields/page-builder.controller.php](site/snippets/fields/page-builder.controller.php)
- Processes layout rows, columns, background colors/images, spacing
- Custom blocks in `/site/blueprints/blocks/` and `/site/snippets/blocks/`
- Supports: text, images, buttons, breadcrumbs, grids, headings, quotes, code

### Spacing System

Defined in [site/config/config.php](site/config/config.php) as the `$spacingUtilityClasses` array:

- Five sizes: none, small, medium, large, xlarge
- Applied to: margin (top/bottom/start/end), padding (top/bottom/start/end), gap
- Maps to Tailwind utility classes (e.g., `mt-small`, `pb-large`)

## Key Kirby Plugins

- **Grid Block**: Custom grid layouts for page builder
- **Hashed Assets**: Cache-busting for CSS/JS
- **Retour**: 301/302 redirect management
- **Snippet Controller**: Separation of logic and templates
- **Minify HTML**: Production HTML minification
- **Robots.txt**: Dynamic robots.txt generation

## Important Conventions

1. **No Node.js on Production**: All builds happen on dev machines. Commit built assets for deployment.
2. **Multi-language Support**: Site uses Kirby's language features (check `/site/languages/`)
3. **Cache Strategy**:
    - APCU cache preferred if available, falls back to file cache
    - Page cache disabled in `config.localhost.php` during development
4. **Image Processing**: Prefers ImageMagick over GD for AVIF support
5. **Deployment**: GitHub Actions workflow sample in `/utilities/deploy-and-sync/`

## Server Configuration

### Caddy Web Server (Recommended)

This template includes Apache `.htaccess` for compatibility, but the author uses Caddy. Add this Caddyfile snippet for Kirby sites:

```caddy
# Kirby-specific configuration snippet
(kirby_config) {
    # PHP setup (adjust socket path as needed)
    php_fastcgi unix//run/php/php8.4-fpm.sock {
        # Pass Authorization header to PHP (required for API auth)
        env HTTP_AUTHORIZATION {http.request.header.Authorization}
    }

    # Block access to Kirby core directories
    @kirby_blocked path /kirby/* /site/* /content/*
    respond @kirby_blocked 404

    # Enable directory indexes (handled by index.php)
    try_files {path} {path}/ /index.php?{query}
}
```

**Usage example for a new Kirby site:**

```caddy
example.com, www.example.com {
    root * /var/www/example.com
    import www_to_naked example.com
    import common_config
    import security_headers
    import content_security_policy
    import kirby_config  # Add this line
    file_server

    # Optional: Cache static assets
    header /fonts/* Cache-Control "public, max-age=31536000"
    header /images/* Cache-Control "public, max-age=31536000"
}
```

**Key differences from Apache `.htaccess`:**

- MIME types: Caddy handles automatically, no manual configuration needed
- Compression: Handled by `encode gzip` in `common_config`
- CORS for images/fonts: Only add if serving assets cross-domain (not needed for most sites)
- Security headers: Already covered in `security_headers` snippet
- URL rewriting: Handled by `try_files` directive in `kirby_config`

### Apache Web Server

The included [.htaccess](.htaccess) file provides:

- Kirby URL rewriting and directory protection
- Security headers and file access restrictions
- MIME type definitions and UTF-8 encoding
- Compression and caching rules
- www to non-www redirects

The `.htaccess` file is production-ready and requires no modifications for most deployments.

## File References

When working with brand colors or spacing, always check:

- [site/config/config.php](site/config/config.php) - Brand color and spacing definitions
- [site/config/thumb-config.php](site/config/thumb-config.php) - Image processing configuration

When modifying page builder functionality:

- [site/snippets/fields/page-builder.controller.php](site/snippets/fields/page-builder.controller.php) - Main controller logic
- [site/blueprints/fields/page-builder.yml](site/blueprints/fields/page-builder.yml) - Panel field definition

## Notes

- Always use Conventional Commits and gitmoji when creating git commit messages.
