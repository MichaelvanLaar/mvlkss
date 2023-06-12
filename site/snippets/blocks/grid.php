<?php
/**
 * =============================================================================
 * Grid Block Snippet
 * =============================================================================
 */

/**
 * -----------------------------------------------------------------------------
 * Configuration
 * -----------------------------------------------------------------------------
 */

// Define class mapping as constants to avoid reinitialization

// Set padding values for the “small”, “medium” and “large” options of the
// respective layout settings field using Tailwind CSS classes
define("GRID_ROW_PADDING_TOP_CLASSES", [
  "none" => "pt-0",
  "small" => "pt-small",
  "medium" => "pt-medium",
  "large" => "pt-large",
  "xlarge" => "pt-xlarge",
]);
define("GRID_ROW_PADDING_BOTTOM_CLASSES", [
  "none" => "pb-0",
  "small" => "pb-small",
  "medium" => "pb-medium",
  "large" => "pb-large",
  "xlarge" => "pb-xlarge",
]);
define("GRID_ROW_PADDING_START_CLASSES", [
  "none" => "ps-0",
  "small" => "ps-small",
  "medium" => "ps-medium",
  "large" => "ps-large",
  "xlarge" => "ps-xlarge",
]);
define("GRID_ROW_PADDING_END_CLASSES", [
  "none" => "pe-0",
  "small" => "pe-small",
  "medium" => "pe-medium",
  "large" => "pe-large",
  "xlarge" => "pe-xlarge",
]);

// Set vertical gap values for the “small”, “medium” and “large” options of the
// respective layout settings field using Tailwind CSS classes
define("GRID_ROW_GAP_CLASSES", [
  "none" => "gap-0",
  "small" => "gap-small",
  "medium" => "gap-medium",
  "large" => "gap-large",
  "xlarge" => "gap-xlarge",
]);

// Set column width classes for the “1/1”, “1/2” and “1/3” of the respective
// layout settings field using Tailwind CSS classes
define("GRID_COLUMN_WIDTH_CLASSES", [
  "1/1" => "col-span-full",
  "1/2" => "col-span-3",
  "1/3" => "col-span-2",
]);

/**
 * -----------------------------------------------------------------------------
 * Preparation
 * -----------------------------------------------------------------------------
 */

// Loop through all grid layout rows
foreach ($block->grid()->toLayouts() as $gridLayoutRow): ?>

  <?php
  // Fetch and prepare class strings for the row
  $gridRowClasses = $gridLayoutRow->gridRowClasses()->isNotEmpty()
    ? $gridLayoutRow->gridRowClasses()
    : "";
  $gridRowGap =
    GRID_ROW_GAP_CLASSES[(string) $gridLayoutRow->gridRowGap()] ?? "gap-0";
  $gridRowPaddingTop =
    GRID_ROW_PADDING_TOP_CLASSES[
      (string) $gridLayoutRow->gridRowPaddingTop()
    ] ?? "pt-0";
  $gridRowPaddingBottom =
    GRID_ROW_PADDING_BOTTOM_CLASSES[
      (string) $gridLayoutRow->gridRowPaddingBottom()
    ] ?? "pb-0";
  $gridRowPaddingStart =
    GRID_ROW_PADDING_START_CLASSES[
      (string) $gridLayoutRow->gridRowPaddingStart()
    ] ?? "ps-0";
  $gridRowPaddingEnd =
    GRID_ROW_PADDING_END_CLASSES[
      (string) $gridLayoutRow->gridRowPaddingEnd()
    ] ?? "pe-0";

  // Prepare other row attributes
  $gridRowIdAttribute = $gridLayoutRow->gridRowId()->isNotEmpty()
    ? " id=\"{$gridLayoutRow->gridRowId()}\""
    : "";
  $gridRowStyleAttribute = $gridLayoutRow->gridRowBackgroundColor()
    ? "style=\"background-color: {$gridLayoutRow->gridRowBackgroundColor()->toColor(
      "rgb"
    )};\""
    : "";
  ?>

  <!-- Grid Row -->
  <div
    class="<?= "$gridRowClasses grid grid-cols-6 $gridRowGap $gridRowPaddingTop $gridRowPaddingBottom $gridRowPaddingStart $gridRowPaddingEnd" ?>"
    <?= $gridRowIdAttribute ?>
    <?= $gridRowStyleAttribute ?>
  >
    <?php foreach ($gridLayoutRow->columns() as $gridLayoutColumn): ?>
      <!-- Grid Column -->
      <div class="<?= GRID_COLUMN_WIDTH_CLASSES[$gridLayoutColumn->width()] ??
        "col-span-full" ?> prose prose-neutral max-w-none dark:prose-invert">
        <?= $gridLayoutColumn->blocks() ?>
      </div>
    <?php endforeach; ?>
  </div>

<?php endforeach; ?>
