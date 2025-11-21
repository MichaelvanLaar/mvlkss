# Project Context

## Purpose
A **Kirby CMS-based website** using modern build tools and a component-based approach. This project is designed for efficient content management with a flat-file architecture, featuring a flexible page builder system, responsive image handling, and optimized asset delivery.

## Tech Stack

### Core Technologies
- **CMS**: Kirby 5.x (PHP-based flat-file CMS)
- **PHP**: >= 8.1.0
- **CSS Framework**: Tailwind CSS 4.x with PostCSS
- **JavaScript**: ES6+ transpiled with Babel
- **Build Tools**: Webpack, PostCSS, npm scripts
- **Web Server**: Caddy (preferred) or Apache with .htaccess

### Key Dependencies
- **Kirby Plugins**: Column Blocks, Hashed Assets, Retour (redirects), Snippet Controller, Minify HTML, Robots.txt
- **Image Processing**: ImageMagick (preferred) or GD library fallback
- **Cache**: APCU (preferred) or file-based fallback

## Project Conventions

### Code Style
- **CSS**: Utility-first approach with Tailwind CSS
  - All Tailwind classes must appear as complete strings in PHP files for proper purging
  - Custom brand colors defined centrally in `site/config/config.php`
  - Spacing system uses predefined utility classes (none, small, medium, large, xlarge)

- **JavaScript**: Modular ES6+ in `/src/js/main-partials/`
  - Imported into `main.js` for Webpack bundling
  - CSS imported via `maincss.js` for Webpack processing

- **PHP/Kirby**:
  - Templates in `/site/templates/`
  - Reusable components in `/site/snippets/`
  - Logic separated via snippet controllers (`kirby-snippet-controller` plugin)
  - Content stored as flat files in `/content/`

### Architecture Patterns

#### Page Builder System
- Uses Kirby's layout field with snippet controllers
- Controller: `site/snippets/fields/page-builder.controller.php`
- Processes layout rows, columns, background colors/images, spacing
- Custom blocks in `/site/blueprints/blocks/` and `/site/snippets/blocks/`
- Supports: text, images, buttons, breadcrumbs, columns, headings, quotes, code

#### Brand Color System
- Centralized in `site/config/config.php` as `$selectableBrandColors` array
- Each color defines light/dark mode utilities (background, border, text)
- Includes contrast colors for accessibility (Tailwind Typography plugin)
- Editor-friendly panel labels for content managers

#### Responsive Images
- Configuration: `site/config/thumb-config.php`
- Predefined widths: 200, 400, 600, 800, 1000, 1600, 2000
- Automatic format conversion: PNG→WebP, JPG→AVIF/WebP
- Srcsets and presets auto-generated for templates

#### Build Pipeline
1. **CSS**: `/src/css/main.css` → Tailwind + PostCSS → `/assets/css/main.css`
2. **JavaScript**: `/src/js/main.js` → Babel + Webpack → `/assets/js/main.js`
3. **Static Assets**: `/src/images` and `/src/fonts` copied to `/assets/`
4. **Cache Busting**: Production builds use `hashup` for versioned filenames

### Testing Strategy
- Manual QA on development machines before deployment
- Built assets tested locally before committing
- Production builds validated with `npm run build`

### Git Workflow
- **Commit Convention**: Conventional Commits with gitmoji
  - Example: `✨ feat(page-builder): add custom columns block component`
  - Example: `🐛 fix(images): correct AVIF fallback behavior`
  - Example: `🔧 chore(deps): update Tailwind CSS to 4.x`

- **Branch Strategy**:
  - Main branch: `main`
  - Feature branches merged into `main`
  - Clean up merged branches with: `npm run utility-git-branches-clean-up`

- **Deployment**: GitHub Actions workflow
  - Built assets committed to repository (no Node.js on production)
  - `/kirby` core and `/vendor` committed for deployment simplicity

## Domain Context

### Kirby CMS Fundamentals
- **Flat-File System**: Content stored as text files, no database required
- **Panel**: Admin interface at `/panel` for content editing
- **Blueprints**: YAML definitions for Panel fields and page types
- **Controllers**: PHP logic separated from template views
- **Multi-language Support**: Built-in via `/site/languages/`

### Content Structure
- Page builder allows flexible layouts without coding
- Content files support markdown with YAML frontmatter
- Responsive images automatically generated on first request
- Redirects managed via Retour plugin

## Important Constraints

### Technical Constraints
1. **No Node.js on Production**: All builds happen on development machines
   - Built assets must be committed to repository
   - Production servers only run PHP

2. **PHP Version**: Requires PHP >= 8.1.0

3. **Asset Processing**:
   - CSS must be imported via `/src/js/maincss.js` for Webpack processing
   - All Tailwind utility classes must appear as complete strings in PHP files
   - Custom colors must be added to `tailwind.config.js` before use in config

4. **Cache Strategy**:
   - APCU preferred, file cache fallback
   - Page cache disabled in development (`config.localhost.php`)
   - Prefill cache with: `npm run utility-kirby-cache-prefill`

5. **Image Processing**:
   - Prefers ImageMagick for AVIF support
   - Automatic fallback to GD library if ImageMagick unavailable

### Development Workflow Constraints
- Always run `npm run build` before committing production-ready code
- Dependency updates done on development machines only: `npm run utility-dependencies-update`
- Test locally with `npm run dev-server` (PHP server on http://localhost:8000)

## External Dependencies

### Content Delivery
- Static assets served from `/assets/` directory
- Fonts and images cached with long TTL (31536000s recommended)
- AVIF/WebP with fallbacks for legacy browsers

### Web Server Configuration
- **Caddy**: Custom snippet required (see CLAUDE.md)
  - PHP-FPM integration with Authorization header passthrough
  - Directory protection for `/kirby`, `/site`, `/content`
  - Try-files for clean URLs

- **Apache**: Uses included `.htaccess` file
  - URL rewriting and directory protection
  - Security headers and MIME types
  - Compression and caching rules

### Development Tools
- **npm scripts**: All build commands defined in `package.json`
- **Composer**: PHP dependency management
- **GitHub Actions**: CI/CD deployment workflow (sample in `/utilities/deploy-and-sync/`)
