# Replace Grid-Blocks Plugin with kirby-column-blocks

## Why

The current `microman/kirby-grid-blocks` plugin is deprecated and no longer actively maintained. The Kirby community recommends migrating to `plain/kirby-column-blocks`, which is the actively maintained successor with better WYSIWYG editing, improved drag-and-drop functionality, and broader layout options.

## What Changes

- **BREAKING**: Replace `microman/kirby-grid-blocks` (v1.0) with `plain/kirby-column-blocks` (v1.2.3+)
- Update Composer dependency from `microman/kirby-grid-blocks` to `plain/kirby-column-blocks`
- Rename custom blueprint from `site/blueprints/blocks/grid.yml` to `site/blueprints/blocks/columns.yml`
- Rename custom snippet from `site/snippets/blocks/grid.php` to `site/snippets/blocks/columns.php`
- Update all blueprint references from `grid` block to `columns` block
- Adapt custom blueprint to extend new `blocks/columns` schema while maintaining all current features:
  - Vertical alignment settings
  - Spacing controls (padding top/bottom/start/end, gap)
  - Background color selection
  - HTML attributes (ID, classes)
  - Sticky behavior toggle
  - Title field
- Maintain full compatibility with existing custom functionality:
  - Tailwind CSS integration
  - Brand color system integration
  - Spacing utility classes system
  - Light/dark mode support
  - Reading-direction awareness (LTR/RTL)
  - Print styles
  - Special handling for images and breadcrumbs within columns
- Add new layout options from kirby-column-blocks:
  - Four equal columns: `1/4, 1/4, 1/4, 1/4`
  - Asymmetric layouts: `1/3, 2/3` and `2/3, 1/3`
- Update page builder field definition to use `columns` instead of `grid` fieldset
- Update page builder controller/snippet to detect and handle `columns` blocks
- Update image block snippet to detect column context using new block type name

## Impact

### Affected Specs
- `page-builder` - Core page builder system specification

### Affected Code
- **Composer dependencies:**
  - `composer.json` - Replace grid-blocks with column-blocks dependency

- **Blueprints:**
  - `site/blueprints/blocks/grid.yml` → `site/blueprints/blocks/columns.yml` (rename + adapt)
  - `site/blueprints/fields/page-builder.yml` - Update fieldset reference

- **Snippets:**
  - `site/snippets/blocks/grid.php` → `site/snippets/blocks/columns.php` (rename + adapt)
  - `site/snippets/fields/page-builder.php` - Update block type detection
  - `site/snippets/blocks/image.php` - Update column context detection

- **Plugin removal:**
  - Remove `site/plugins/kirby-grid-blocks/` directory after migration

### Breaking Changes
- **Content migration required**: Existing pages using grid blocks will need conversion
  - Block type changes from `grid` to `columns` in content files
  - Field structure remains compatible (uses same layout field)
  - Content structure is preserved (no data loss expected)

- **Editor impact**: Panel interface changes slightly with new WYSIWYG features
  - Improved paste functionality (cmd+v to insert blocks)
  - Enhanced drag-and-drop between columns
  - Same visual appearance for editors

### Benefits
- Active maintenance and bug fixes from kirby-column-blocks maintainers
- More layout options (6 layouts vs 3 layouts)
- Better editing experience with enhanced WYSIWYG features
- Future-proof solution aligned with Kirby community recommendations
- Simplified plugin foundation (easier to customize and maintain)

### Risks
- Content migration complexity for existing grid blocks in production
- Potential for edge cases in custom rendering logic
- Requires thorough testing of all page builder features
- Temporary disruption during migration period
