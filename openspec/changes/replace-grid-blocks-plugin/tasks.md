# Implementation Tasks

## 1. Preparation & Analysis
- [ ] 1.1 Create backup branch from current state
- [ ] 1.2 Document all existing grid block usage in content files
- [ ] 1.3 Test current grid block functionality as baseline
- [ ] 1.4 Review kirby-column-blocks plugin documentation thoroughly

## 2. Plugin Installation
- [ ] 2.1 Install kirby-column-blocks via Composer: `composer require plain/kirby-column-blocks`
- [ ] 2.2 Verify plugin files installed correctly in vendor directory
- [ ] 2.3 Test basic column-blocks functionality with minimal blueprint
- [ ] 2.4 Confirm no conflicts with existing plugins

## 3. Blueprint Migration
- [ ] 3.1 Copy `site/blueprints/blocks/grid.yml` to `site/blueprints/blocks/columns.yml`
- [ ] 3.2 Update blueprint to extend `blocks/columns` instead of defining from scratch
- [ ] 3.3 Adapt layout field to use new plugin's layout options
- [ ] 3.4 Add new asymmetric layouts (1/3, 2/3 and 2/3, 1/3)
- [ ] 3.5 Add four-column layout (1/4, 1/4, 1/4, 1/4)
- [ ] 3.6 Preserve all custom fields (title, sticky, padding, gap, background, attributes)
- [ ] 3.7 Update field labels to use "columns" terminology where appropriate
- [ ] 3.8 Test blueprint in Panel to verify all fields render correctly

## 4. Snippet Migration
- [ ] 4.1 Copy `site/snippets/blocks/grid.php` to `site/snippets/blocks/columns.php`
- [ ] 4.2 Adapt snippet to work with new plugin's data structure
- [ ] 4.3 Update column width mapping for new layouts:
  - [ ] Add `1/4` → appropriate Tailwind class
  - [ ] Add `1/3` (from 2/3, 1/3) → appropriate Tailwind class
  - [ ] Add `2/3` → appropriate Tailwind class
- [ ] 4.4 Preserve all custom rendering logic:
  - [ ] Spacing utility classes integration
  - [ ] Background color handling with light/dark mode
  - [ ] Vertical alignment
  - [ ] HTML attributes (ID, classes)
  - [ ] Sticky behavior
- [ ] 4.5 Maintain special block handling:
  - [ ] Image blocks in columns
  - [ ] Breadcrumb blocks with color contrast
- [ ] 4.6 Preserve data attributes for page builder elements
- [ ] 4.7 Test snippet rendering with various layouts

## 5. Page Builder Integration Updates
- [ ] 5.1 Update `site/blueprints/fields/page-builder.yml`:
  - [ ] Change fieldset reference from `grid` to `columns`
  - [ ] Verify fieldset grouping (miscBlocks) still correct
- [ ] 5.2 Update `site/snippets/fields/page-builder.php`:
  - [ ] Change block type detection from `grid` to `columns`
  - [ ] Update context passing for column blocks
  - [ ] Test page builder controller still works correctly
- [ ] 5.3 Update `site/snippets/blocks/image.php`:
  - [ ] Change column context detection from grid to columns
  - [ ] Verify image sizing adjustments still work
  - [ ] Test images within various column layouts

## 6. Testing
- [ ] 6.1 Create test page with all column layouts:
  - [ ] Single column (1/1)
  - [ ] Two equal columns (1/2, 1/2)
  - [ ] Three equal columns (1/3, 1/3, 1/3)
  - [ ] Four equal columns (1/4, 1/4, 1/4, 1/4) - NEW
  - [ ] Asymmetric 1/3, 2/3 - NEW
  - [ ] Asymmetric 2/3, 1/3 - NEW
- [ ] 6.2 Test all row settings in Panel:
  - [ ] Vertical alignment (top, middle, bottom)
  - [ ] Padding controls (top, bottom, start, end)
  - [ ] Gap between columns
  - [ ] Background colors (all brand colors)
  - [ ] HTML ID and classes
  - [ ] Sticky toggle
- [ ] 6.3 Test nested blocks within columns:
  - [ ] Text blocks (heading, text, list, markdown, quote, code)
  - [ ] Image blocks (verify sizing adjustments)
  - [ ] Button blocks
  - [ ] Line blocks
  - [ ] Breadcrumb blocks (verify color contrast)
- [ ] 6.4 Test responsive behavior across breakpoints
- [ ] 6.5 Test light/dark mode switching
- [ ] 6.6 Test print styles
- [ ] 6.7 Test reading direction (LTR/RTL) if applicable
- [ ] 6.8 Test Panel editing experience:
  - [ ] Drag-and-drop blocks into columns
  - [ ] Paste (cmd+v) blocks into columns
  - [ ] Move blocks between columns
  - [ ] Delete columns and rows

## 7. Content Migration (if existing content)
- [ ] 7.1 Identify all pages using grid blocks in content files
- [ ] 7.2 Create content migration script or manual process
- [ ] 7.3 Update block type from `grid` to `columns` in content files
- [ ] 7.4 Verify field structure compatibility
- [ ] 7.5 Test migrated content renders correctly
- [ ] 7.6 Backup original content before migration

## 8. Cleanup & Documentation
- [ ] 8.1 Remove old grid block files:
  - [ ] Delete `site/blueprints/blocks/grid.yml`
  - [ ] Delete `site/snippets/blocks/grid.php`
- [ ] 8.2 Remove old plugin directory: `site/plugins/kirby-grid-blocks/`
- [ ] 8.3 Update `composer.json` to remove `microman/kirby-grid-blocks` dependency
- [ ] 8.4 Run `composer update` to update lock file
- [ ] 8.5 Update project documentation:
  - [ ] Update CLAUDE.md plugin list
  - [ ] Update openspec/project.md plugin references
  - [ ] Add migration notes if needed
- [ ] 8.6 Update any inline code comments referencing grid blocks

## 9. Build & Deployment
- [ ] 9.1 Run `npm run build` to generate production assets
- [ ] 9.2 Test production build locally
- [ ] 9.3 Commit all changes with proper commit message
- [ ] 9.4 Create pull request with detailed description
- [ ] 9.5 Deploy to staging environment (if available)
- [ ] 9.6 Final testing on staging
- [ ] 9.7 Deploy to production
- [ ] 9.8 Monitor for issues post-deployment

## 10. Post-Migration Validation
- [ ] 10.1 Verify all existing pages with columns render correctly
- [ ] 10.2 Test Panel editing for content editors
- [ ] 10.3 Monitor error logs for any issues
- [ ] 10.4 Gather feedback from content editors
- [ ] 10.5 Address any issues or edge cases discovered
