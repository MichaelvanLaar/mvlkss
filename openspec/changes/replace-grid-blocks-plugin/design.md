# Technical Design: Grid-Blocks to Column-Blocks Migration

## Context

The current page builder system uses `microman/kirby-grid-blocks` plugin, which is no longer actively maintained. The plugin provides grid layout functionality that has been heavily customized to integrate with the project's:
- Tailwind CSS architecture
- Brand color system (defined in `site/config/config.php`)
- Spacing utility classes system
- Light/dark mode support
- Multi-language support

The replacement plugin, `plain/kirby-column-blocks`, is the community-recommended successor with active maintenance and enhanced WYSIWYG features.

### Current Architecture

**Grid-Blocks Implementation:**
- Plugin: `microman/kirby-grid-blocks` v1.0
- Custom blueprint: `site/blueprints/blocks/grid.yml` (155 lines, heavily customized)
- Custom snippet: `site/snippets/blocks/grid.php` (199 lines, Tailwind-integrated)
- CSS Grid system: 6-column grid with Tailwind classes
- Available layouts: 1/1, 1/2 (×2), 1/3 (×3)
- Column mapping: `1/1` → `col-span-full`, `1/2` → `col-span-3`, `1/3` → `col-span-2`

**Integration Points:**
- Page builder field: `site/blueprints/fields/page-builder.yml` (references `grid` fieldset)
- Page builder snippet: `site/snippets/fields/page-builder.php` (detects `grid` blocks, passes context)
- Image block snippet: `site/snippets/blocks/image.php` (adjusts sizing for grid columns)

### Constraints

1. **No data loss**: Existing content must be preservable
2. **Feature parity**: All current customizations must be maintained
3. **Editor experience**: Panel editing should improve, not degrade
4. **Tailwind CSS**: Must continue using Tailwind utility classes
5. **Brand system**: Integration with brand colors and spacing must be preserved
6. **Accessibility**: LTR/RTL support, color contrast, semantic HTML must continue

## Goals / Non-Goals

### Goals
1. Replace deprecated plugin with actively maintained alternative
2. Maintain 100% feature parity with current implementation
3. Add new layout options (4-column, asymmetric layouts)
4. Improve editor experience with better WYSIWYG functionality
5. Simplify plugin foundation for easier future maintenance
6. Preserve all existing customizations and integrations

### Non-Goals
1. Redesigning the page builder architecture
2. Changing the visual appearance of rendered columns
3. Modifying the brand color or spacing systems
4. Altering the Tailwind CSS grid approach
5. Changing content structure beyond block type name

## Decisions

### Decision 1: Direct Plugin Replacement

**What:** Replace plugin via Composer, rename files, update references

**Why:**
- Both plugins use the same `layout` field foundation
- kirby-column-blocks is designed as a drop-in replacement
- Minimizes code changes and migration risk
- Leverages existing customization approach

**Alternatives considered:**
- Building custom column system from scratch → Too complex, reinvents wheel
- Using Kirby's built-in layout field directly → Loses specialized column block UX
- Keeping grid-blocks → Technical debt, no future support

### Decision 2: Extend Plugin Blueprint vs Override

**What:** Use `extends: blocks/columns` in custom blueprint to inherit base structure, then add custom fields

**Why:**
- Maintains compatibility with plugin updates
- Clearer separation of base functionality vs customizations
- Follows Kirby best practices
- Makes customizations more maintainable

**Implementation:**
```yaml
name: Columns
extends: blocks/columns
fields:
  title:
    # Custom title field
  sticky:
    # Custom sticky toggle
  grid:  # Inherits from blocks/columns
    # Add custom settings to layout field
```

### Decision 3: Preserve 6-Column Tailwind Grid System

**What:** Continue using `grid grid-cols-6` with column span classes

**Why:**
- Existing responsive breakpoints work correctly
- Proven approach in production
- Allows fine-grained control with Tailwind
- Supports all required layouts including new asymmetric ones

**Column Mapping (updated):**
```php
$columnWidthClasses = [
    '1/1' => 'col-span-full',    // Full width
    '1/2' => 'col-span-3',        // Half width (3 of 6)
    '1/3' => 'col-span-2',        // Third width (2 of 6)
    '2/3' => 'col-span-4',        // Two-thirds width (4 of 6)
    '1/4' => 'col-span-3 lg:col-span-1.5', // Quarter width (responsive)
];
```

**Note:** 1/4 layout requires special handling since 6 ÷ 4 = 1.5 (not integer). Options:
- Use responsive classes: Full width on mobile, proper quarters on large screens
- Switch to 12-column grid for cleaner math (6 is cleaner for current layouts)
- Use CSS Grid fraction units in custom CSS

**Recommended approach:** Keep 6-column grid, use responsive classes for 1/4 layout

### Decision 4: Content Migration Strategy

**What:** Content files will need block type renamed from `grid` to `columns`

**Why:**
- Kirby uses block type name to locate blueprint and snippet
- Field structure (layout field data) is compatible between plugins
- No data loss, only identifier change

**Migration approach:**
1. Manual approach (low volume): Edit content files directly in Panel or filesystem
2. Automated approach (high volume): Create PHP script to find/replace in content files
3. Hybrid approach: Use search/replace in IDE for bulk changes, manual verification

**Data structure compatibility:**
```yaml
# Old (grid block)
blocks:
  - type: grid
    content:
      grid:
        - columns:
            - width: 1/2
              blocks: [...]

# New (columns block) - SAME STRUCTURE
blocks:
  - type: columns
    content:
      layout:  # Field name might differ, verify!
        - columns:
            - width: 1/2
              blocks: [...]
```

**Risk mitigation:** Test migration on copy of content first, verify in Panel

### Decision 5: Backward Compatibility During Migration

**What:** Keep both plugins installed temporarily during migration period

**Why:**
- Allows gradual content migration
- Reduces risk of breaking production
- Enables A/B testing of functionality

**Timeline:**
1. Install column-blocks alongside grid-blocks
2. Create columns blueprint and snippet
3. Add both `grid` and `columns` to page builder fieldsets
4. Test thoroughly
5. Migrate content
6. Remove grid block references
7. Uninstall grid-blocks plugin

**Trade-off:** Temporary code duplication, but safer migration

## Risks / Trade-offs

### Risk 1: Column Width Mapping Complexity

**Issue:** Four-column layout (1/4) doesn't divide evenly into 6-column grid

**Impact:** Potential visual inconsistencies or complex CSS

**Mitigation:**
- Use responsive approach: Stack on mobile, 4 columns on desktop
- Consider 12-column grid if 4-column layout is critical
- Document limitation for content editors
- Test thoroughly across breakpoints

### Risk 2: Content Migration Errors

**Issue:** Manual find/replace could miss edge cases or corrupt content

**Impact:** Broken pages, data loss

**Mitigation:**
- Create full backup before migration
- Test migration process on copy first
- Develop automated script with dry-run mode
- Verify each migrated page in Panel
- Keep git history for rollback

### Risk 3: Plugin API Changes

**Issue:** kirby-column-blocks may have different data structure than expected

**Impact:** Custom snippet code may need significant changes

**Mitigation:**
- Install plugin early and inspect actual data structure
- Create test blocks to verify API
- Review plugin source code for data structures
- Budget extra time for snippet adaptation
- Test all features before removing old plugin

### Risk 4: Editor Training

**Issue:** Panel interface changes may confuse content editors

**Impact:** Support requests, editing errors

**Mitigation:**
- Document new features (paste, improved drag-drop)
- Create training materials or quick guide
- Communicate changes before deployment
- Provide hands-on demo if possible
- Monitor for editor issues post-deployment

## Migration Plan

### Phase 1: Preparation (No Production Impact)
1. Create feature branch
2. Install kirby-column-blocks via Composer
3. Inspect plugin data structures and API
4. Create custom columns blueprint (keep grid.yml for now)
5. Create custom columns snippet (keep grid.php for now)
6. Add columns to page builder fieldsets (alongside grid)
7. Test columns block thoroughly in development

### Phase 2: Parallel Testing (Dual Mode)
1. Both grid and columns blocks available in Panel
2. Create test pages using columns blocks
3. Verify all features work correctly
4. Gather feedback from select editors
5. Fix any issues discovered
6. Confirm 100% feature parity

### Phase 3: Content Migration (Breaking Change)
1. Create full backup of content files
2. Remove grid from page builder fieldsets
3. Migrate existing grid blocks to columns:
   - Option A: Manual in Panel (low volume)
   - Option B: Automated script (high volume)
4. Verify all pages render correctly
5. Test Panel editing on migrated content
6. Deploy to staging

### Phase 4: Cleanup (Remove Old Plugin)
1. Remove grid.yml blueprint
2. Remove grid.php snippet
3. Remove grid-blocks plugin directory
4. Update Composer dependencies
5. Update documentation
6. Deploy to production
7. Monitor for issues

### Rollback Plan

**If issues discovered in Phase 2 (Parallel Testing):**
- Remove columns fieldset from page builder
- Continue using grid blocks
- Investigate and fix issues
- Return to Phase 1

**If issues discovered in Phase 3 (Content Migration):**
- Revert content files from backup
- Re-add grid to page builder fieldsets
- Fix issues before re-attempting migration

**If issues discovered in Phase 4 (Production):**
- Revert git commits
- Reinstall grid-blocks plugin
- Restore content from backup if needed
- Investigate root cause

## Open Questions

1. **Content migration volume:** How many pages currently use grid blocks?
   - Answer: Need to scan content directory
   - Impact: Determines manual vs automated approach

2. **Field name consistency:** Does kirby-column-blocks use `layout` or `grid` for the layout field?
   - Answer: Verify in actual plugin installation
   - Impact: May need to adjust field references in snippet

3. **Four-column layout priority:** How important is the 1/4 layout to content editors?
   - Answer: Consult with stakeholders
   - Impact: May influence grid system choice (6 vs 12 columns)

4. **Staging environment:** Is staging available for testing before production?
   - Answer: Confirm deployment pipeline
   - Impact: Testing strategy and risk level

5. **Editor notification:** What's the best way to communicate changes to content editors?
   - Answer: Determine communication channels
   - Impact: Training and support approach

6. **Timeline:** What's the deadline for this migration?
   - Answer: Confirm with stakeholders
   - Impact: Rushed migration increases risk
