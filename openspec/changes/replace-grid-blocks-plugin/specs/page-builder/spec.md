# Page Builder Specification Delta

## MODIFIED Requirements

### Requirement: Column-Based Layouts

The page builder SHALL provide flexible column-based layouts using the `plain/kirby-column-blocks` plugin instead of the deprecated `microman/kirby-grid-blocks` plugin. The system SHALL maintain all existing customization features including spacing controls, background colors, vertical alignment, and HTML attributes.

#### Scenario: Editor creates columns block
- **GIVEN** the editor is editing a page with the page builder field
- **WHEN** the editor adds a "Columns" block to the page builder
- **THEN** the Panel SHALL display the columns block with WYSIWYG editing capability
- **AND** the editor SHALL be able to choose from six layout options: 1/1, 1/2 (×2), 1/3 (×3), 2/3 + 1/3, 1/3 + 2/3, 1/4 (×4)
- **AND** the editor SHALL be able to configure row settings (vertical alignment, padding, gap, background color, HTML attributes)
- **AND** the editor SHALL be able to add nested blocks within each column

#### Scenario: Editor uses enhanced WYSIWYG features
- **GIVEN** the editor has a columns block in the page builder
- **WHEN** the editor pastes (cmd+v) content while focused on a column
- **THEN** the system SHALL insert the pasted block into that column
- **AND** the editor SHALL be able to drag blocks between columns
- **AND** the editor SHALL be able to drag blocks into and out of columns

#### Scenario: Two equal columns render correctly
- **GIVEN** a page has a columns block with 1/2, 1/2 layout
- **WHEN** the page is rendered on the frontend
- **THEN** the system SHALL display a 6-column CSS grid
- **AND** each column SHALL span 3 grid columns (col-span-3)
- **AND** the columns SHALL apply custom spacing utility classes based on row settings
- **AND** the columns SHALL apply background color based on row settings with light/dark mode support

#### Scenario: Three equal columns render correctly
- **GIVEN** a page has a columns block with 1/3, 1/3, 1/3 layout
- **WHEN** the page is rendered on the frontend
- **THEN** the system SHALL display a 6-column CSS grid
- **AND** each column SHALL span 2 grid columns (col-span-2)
- **AND** the columns SHALL apply row settings as configured

#### Scenario: Asymmetric layout (1/3 + 2/3) renders correctly
- **GIVEN** a page has a columns block with 1/3, 2/3 layout
- **WHEN** the page is rendered on the frontend
- **THEN** the system SHALL display a 6-column CSS grid
- **AND** the first column SHALL span 2 grid columns (col-span-2)
- **AND** the second column SHALL span 4 grid columns (col-span-4)
- **AND** the columns SHALL maintain proper proportional widths

#### Scenario: Asymmetric layout (2/3 + 1/3) renders correctly
- **GIVEN** a page has a columns block with 2/3, 1/3 layout
- **WHEN** the page is rendered on the frontend
- **THEN** the system SHALL display a 6-column CSS grid
- **AND** the first column SHALL span 4 grid columns (col-span-4)
- **AND** the second column SHALL span 2 grid columns (col-span-2)
- **AND** the columns SHALL maintain proper proportional widths

#### Scenario: Four equal columns render correctly
- **GIVEN** a page has a columns block with 1/4, 1/4, 1/4, 1/4 layout
- **WHEN** the page is rendered on the frontend
- **THEN** the system SHALL display a 6-column CSS grid with responsive column spans
- **AND** each column SHALL use appropriate responsive classes for quarter-width display
- **AND** the columns SHALL stack appropriately on mobile devices

#### Scenario: Column row settings apply correctly
- **GIVEN** a columns block with configured row settings
- **WHEN** the page is rendered
- **THEN** the system SHALL apply vertical alignment classes (top, middle, bottom) if configured
- **AND** the system SHALL apply padding top spacing class if configured
- **AND** the system SHALL apply padding bottom spacing class if configured
- **AND** the system SHALL apply padding start spacing class if configured
- **AND** the system SHALL apply padding end spacing class if configured
- **AND** the system SHALL apply gap spacing class between columns if configured
- **AND** the system SHALL apply background color with light/dark mode support if configured
- **AND** the system SHALL apply custom HTML ID if configured
- **AND** the system SHALL apply custom CSS classes if configured

#### Scenario: Sticky columns behavior
- **GIVEN** a columns block with sticky toggle enabled
- **WHEN** the page is rendered and the user scrolls
- **THEN** the columns block SHALL stick to the top of the viewport
- **AND** the sticky behavior SHALL use appropriate CSS positioning

#### Scenario: Images within columns adjust sizing
- **GIVEN** a columns block containing image blocks
- **WHEN** the page is rendered
- **THEN** the system SHALL detect the column context
- **AND** the system SHALL adjust image max-width based on column width (1/2 or 1/3)
- **AND** the images SHALL render at appropriate sizes for their container

#### Scenario: Breadcrumbs within columns maintain color contrast
- **GIVEN** a columns block with background color containing breadcrumb blocks
- **WHEN** the page is rendered
- **THEN** the system SHALL detect the background color context
- **AND** the system SHALL apply appropriate contrast text colors to breadcrumbs
- **AND** the breadcrumbs SHALL remain readable against the background color

#### Scenario: Columns support all nested block types
- **GIVEN** a columns block in the page builder
- **WHEN** the editor adds blocks within a column
- **THEN** the system SHALL support heading blocks
- **AND** the system SHALL support text blocks
- **AND** the system SHALL support list blocks
- **AND** the system SHALL support markdown blocks
- **AND** the system SHALL support quote blocks
- **AND** the system SHALL support code blocks
- **AND** the system SHALL support image blocks
- **AND** the system SHALL support button blocks
- **AND** the system SHALL support line blocks
- **AND** the system SHALL support breadcrumb blocks

#### Scenario: Print styles apply correctly
- **GIVEN** a page with columns blocks
- **WHEN** the page is printed or print preview is shown
- **THEN** the grid system SHALL switch to block layout for print
- **AND** the columns SHALL stack vertically for better print readability

#### Scenario: Reading direction awareness
- **GIVEN** a page with columns blocks in an RTL (right-to-left) language
- **WHEN** the page is rendered
- **THEN** the padding-start and padding-end classes SHALL respect reading direction
- **AND** the column order SHALL respect reading direction
- **AND** the layout SHALL be appropriately mirrored

## REMOVED Requirements

### Requirement: Grid Block Plugin

**Reason:** The `microman/kirby-grid-blocks` plugin is deprecated and no longer maintained. It has been replaced with `plain/kirby-column-blocks`.

**Migration:** Existing grid blocks in content files must be renamed from type `grid` to type `columns`. The field structure remains compatible, preserving all content data. All custom features and integrations are maintained in the new columns implementation.

## ADDED Requirements

### Requirement: Enhanced Editor Experience

The columns block editing experience SHALL provide improved WYSIWYG functionality beyond the previous grid block implementation.

#### Scenario: Paste blocks directly into columns
- **GIVEN** the editor has copied a block in the Panel
- **WHEN** the editor pastes (cmd+v) while focused on a column in a columns block
- **THEN** the system SHALL insert the copied block into that specific column
- **AND** the paste operation SHALL work seamlessly without additional dialogs

#### Scenario: Improved drag-and-drop
- **GIVEN** the editor is working with a columns block
- **WHEN** the editor drags a block from outside the columns
- **THEN** the system SHALL allow dropping the block into any column
- **AND** the system SHALL show visual feedback during drag operation
- **AND** the system SHALL allow dragging blocks between different columns within the same row

### Requirement: Extended Layout Options

The columns block SHALL support additional layout options beyond the original three layouts, providing more flexibility for content editors.

#### Scenario: Four-column layout available
- **GIVEN** the editor is configuring a columns block
- **WHEN** the editor selects the layout option
- **THEN** the system SHALL offer a four equal columns layout (1/4, 1/4, 1/4, 1/4)
- **AND** this layout SHALL render correctly on the frontend with appropriate responsive behavior

#### Scenario: Asymmetric layouts available
- **GIVEN** the editor is configuring a columns block
- **WHEN** the editor selects the layout option
- **THEN** the system SHALL offer an asymmetric layout with narrow left column (1/3, 2/3)
- **AND** the system SHALL offer an asymmetric layout with wide left column (2/3, 1/3)
- **AND** these layouts SHALL render correctly with proper proportional widths
