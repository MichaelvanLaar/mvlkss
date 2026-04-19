<?php
/**
 * =============================================================================
 * Columns Block Snippet
 * =============================================================================
 */

/**
 * -----------------------------------------------------------------------------
 * Configuration
 * -----------------------------------------------------------------------------
 */

// Set column width classes for the layout options using Tailwind CSS classes
// Grid uses 12 columns to support all fractional widths as integer spans.
$columnWidthClasses = [
  "1/1" => "col-span-full",
  "1/2" => "col-span-6",
  "1/3" => "col-span-4",
  "2/3" => "col-span-8",
  "1/4" => "col-span-3",
];

/**
 * -----------------------------------------------------------------------------
 * Output
 * -----------------------------------------------------------------------------
 */

// Variables injected by the caller via snippet():
$layoutColumnWidth = $layoutColumnWidth ?? null;
$layoutColumnSplitting = $layoutColumnSplitting ?? null;
$layoutRowBackgroundColorExists = $layoutRowBackgroundColorExists ?? false;
$layoutRowBackgroundColorValue = $layoutRowBackgroundColorValue ?? null;

// Get the "selectable brand colors" array from the site constants
$selectableBrandColors = option("site-constants")["selectable-brand-colors"];

// Loop through all column layout rows
foreach ($block->layout()->toLayouts() as $columnLayoutRow): ?>

  <?php
  // Construct the ID attribute for the current column row
  $columnRowIdAttribute = $columnLayoutRow->columnRowId()->isNotEmpty()
    ? " id=\"{$columnLayoutRow->columnRowId()}\""
    : "";

  // Set the gap related CSS class for the current column row
  $columnRowGapClass =
    option("site-constants")["spacing-utility-classes"]["gap"][
      (string) $columnLayoutRow->columnRowGap()
    ] ?? "gap-0";

  // Set the top padding related CSS class for the current column row
  $columnRowPaddingTopClass =
    option("site-constants")["spacing-utility-classes"]["padding-top"][
      (string) $columnLayoutRow->columnRowPaddingTop()
    ] ?? "pt-0";

  // Set the bottom padding related CSS class for the current column row
  $columnRowPaddingBottomClass =
    option("site-constants")["spacing-utility-classes"]["padding-bottom"][
      (string) $columnLayoutRow->columnRowPaddingBottom()
    ] ?? "pb-0";

  // Set the start padding related CSS class for the current column row
  $columnRowPaddingStartClass =
    option("site-constants")["spacing-utility-classes"]["padding-start"][
      (string) $columnLayoutRow->columnRowPaddingStart()
    ] ?? "ps-0";

  // Set the end padding related CSS class for the current column row
  $columnRowPaddingEndClass =
    option("site-constants")["spacing-utility-classes"]["padding-end"][
      (string) $columnLayoutRow->columnRowPaddingEnd()
    ] ?? "pe-0";

  // Set the background color related CSS class for the current column row
  $columnRowBgColorValue = $columnLayoutRow->columnRowBackgroundColor()->isNotEmpty()
    ? $columnLayoutRow->columnRowBackgroundColor()->value()
    : null;
  if ($columnRowBgColorValue !== null && isset($selectableBrandColors[$columnRowBgColorValue])) {
    $colorEntry = $selectableBrandColors[$columnRowBgColorValue];
    $columnRowBackgroundColorClasses =
      $colorEntry["light-tailwindcss-bg-class"] . " " .
      $colorEntry["dark-tailwindcss-bg-class"] . " print:bg-transparent";
  } else {
    if ($columnRowBgColorValue !== null) {
      error_log("columns.php: Unknown background color key \"{$columnRowBgColorValue}\"");
    }
    $columnRowBackgroundColorClasses = "";
  }

  // Construct the classes attribute for the current column row
  $columnRowClasses = [
    $columnLayoutRow->columnRowClasses(),
    "grid",
    "grid-cols-12",
    "print:block",
    $columnRowGapClass,
    $columnRowPaddingTopClass,
    $columnRowPaddingBottomClass,
    $columnRowPaddingStartClass,
    $columnRowPaddingEndClass,
    $columnRowBackgroundColorClasses,
  ];
  $columnRowClassAttribute = "class=\"" . implode(" ", $columnRowClasses) . "\"";
  ?>

  <!-- Column Row -->
  <div
    data-page-builder-element-type="column-row"
    <?= $columnRowIdAttribute ?>
    <?= $columnRowClassAttribute ?>
  >
    <?php foreach ($columnLayoutRow->columns() as $columnLayoutColumn): ?>
      <!-- Column -->
      <?php
      $columnClassOutput = "";
      $columnClassOutput .=
        $columnWidthClasses[$columnLayoutColumn->width()] ?? "col-span-full";
      if ($columnRowBgColorValue !== null && isset($selectableBrandColors[$columnRowBgColorValue])) {
        $colorEntry = $selectableBrandColors[$columnRowBgColorValue];
        $columnInnerContainerClassOutput =
          " " . $colorEntry["light-contrast-tailwindcss-prose-class"] .
          " " . $colorEntry["dark-contrast-tailwindcss-prose-class"] .
          " print:prose-black";
      } elseif ($layoutRowBackgroundColorExists && isset($selectableBrandColors[$layoutRowBackgroundColorValue])) {
        $colorEntry = $selectableBrandColors[$layoutRowBackgroundColorValue];
        $columnInnerContainerClassOutput =
          " " . $colorEntry["light-contrast-tailwindcss-prose-class"] .
          " " . $colorEntry["dark-contrast-tailwindcss-prose-class"] .
          " print:prose-black";
      } else {
        $columnInnerContainerClassOutput = " prose-mvlkss dark:prose-invert print:prose-black";
      }
      if ($columnLayoutRow->columnRowVerticalAlign()->isNotEmpty()) {
        switch ($columnLayoutRow->columnRowVerticalAlign()->value()) {
          case "top":
            $columnClassOutput .= " flex flex-col justify-start";
            break;
          case "middle":
            $columnClassOutput .= " flex flex-col justify-center";
            break;
          case "bottom":
            $columnClassOutput .= " flex flex-col justify-end";
            break;
        }
      } else {
        $columnClassOutput .= " flex flex-col justify-start";
      }
      ?>
      <div data-page-builder-element-type="column" class="<?= $columnClassOutput ?>">
        <?php if ($columnLayoutColumn->blocks()->isNotEmpty()) {
          echo "<!-- Inner column container -->\n<div data-page-builder-element-type=\"column-inner-container\" class=\"max-w-none prose" .
            $columnInnerContainerClassOutput .
            "\">";
        } ?>
          <?php foreach ($columnLayoutColumn->blocks() as $innerBlock) {
            if ($innerBlock->type() == "image") {
              snippet("blocks/" . $innerBlock->type(), [
                "block" => $innerBlock,
                "layoutColumnWidth" => $layoutColumnWidth ?? null,
                "columnLayoutColumnWidth" => $columnLayoutColumn->width(),
                "layoutColumnSplitting" => $layoutColumnSplitting,
              ]);
            } elseif ($innerBlock->type() == "columns") {
              snippet("blocks/" . $innerBlock->type(), [
                "block" => $innerBlock,
                "layoutColumnWidth" => $columnLayoutColumn->width(),
                "layoutColumnSplitting" => $layoutColumnSplitting ?? null,
                "layoutRowBackgroundColorExists" =>
                  $columnLayoutRow->columnRowBackgroundColor()->isNotEmpty(),
                "layoutRowBackgroundColorValue" =>
                  $columnLayoutRow->columnRowBackgroundColor()->isNotEmpty()
                    ? $columnLayoutRow->columnRowBackgroundColor()->value()
                    : null,
              ]);
            } elseif ($innerBlock->type() == "mvlkssbreadcrumb") {
              $colorKey = null;
              if ($columnLayoutRow->columnRowBackgroundColor()->isNotEmpty()) {
                $colorKey = $columnLayoutRow->columnRowBackgroundColor()->value();
              } elseif ($layoutRowBackgroundColorExists) {
                $colorKey = $layoutRowBackgroundColorValue;
              }
              $breadcrumbTextColorLight = $colorKey && isset($selectableBrandColors[$colorKey])
                ? $selectableBrandColors[$colorKey]["light-contrast-tailwindcss-text-class"]
                : null;
              $breadcrumbTextColorDark = $colorKey && isset($selectableBrandColors[$colorKey])
                ? $selectableBrandColors[$colorKey]["dark-contrast-tailwindcss-text-class"]
                : null;
              snippet("blocks/" . $innerBlock->type(), [
                "block" => $innerBlock,
                "textColorLight" => $breadcrumbTextColorLight,
                "textColorDark" => $breadcrumbTextColorDark,
              ]);
            } else {
              echo $innerBlock;
            }
          } ?>
        <?= $columnLayoutColumn->blocks()->isNotEmpty() ? "</div>" : "" ?>
      </div>
    <?php endforeach; ?>
  </div>
<?php endforeach; ?>
