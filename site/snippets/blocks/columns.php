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
$columnWidthClasses = [
  "1/1" => "col-span-full",
  "1/2" => "col-span-3",
  "1/3" => "col-span-2",
  "2/3" => "col-span-4",
  "1/4" => "col-span-full sm:col-span-3 lg:col-span-3/2", // Responsive: full on mobile, half on small, quarter on large
];

/**
 * -----------------------------------------------------------------------------
 * Output
 * -----------------------------------------------------------------------------
 */

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
  $columnRowBackgroundColorClasses = $columnLayoutRow
    ->columnRowBackgroundColor()
    ->isNotEmpty()
    ? $selectableBrandColors[$columnLayoutRow->columnRowBackgroundColor()->value()][
        "light-tailwindcss-bg-class"
      ] .
      " " .
      $selectableBrandColors[$columnLayoutRow->columnRowBackgroundColor()->value()][
        "dark-tailwindcss-bg-class"
      ] .
      " print:bg-transparent"
    : "";

  // Construct the classes attribute for the current column row
  $columnRowClasses = [
    $columnLayoutRow->columnRowClasses(),
    "grid",
    "grid-cols-6",
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
      if ($columnLayoutRow->columnRowBackgroundColor()->isNotEmpty()) {
        $columnInnerContainerClassOutput =
          " " .
          $selectableBrandColors[
            $columnLayoutRow->columnRowBackgroundColor()->value()
          ]["light-contrast-tailwindcss-prose-class"] .
          " " .
          $selectableBrandColors[
            $columnLayoutRow->columnRowBackgroundColor()->value()
          ]["dark-contrast-tailwindcss-prose-class"] .
          " print:prose-black";
        } elseif ($layoutRowBackgroundColorExists) {
          $columnInnerContainerClassOutput =
            " " .
            $selectableBrandColors[$layoutRowBackgroundColorValue][
              "light-contrast-tailwindcss-prose-class"
            ] .
            " " .
            $selectableBrandColors[$layoutRowBackgroundColorValue][
              "dark-contrast-tailwindcss-prose-class"
            ] .
            " print:prose-black";
        } else {
        $columnInnerContainerClassOutput =
          " prose-mvlkss dark:prose-invert print:prose-black";
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
          <?php foreach ($columnLayoutColumn->blocks() as $block) {
            if ($block->type() == "image") {
              snippet("blocks/" . $block->type(), [
                "block" => $block,
                "layoutColumnWidth" => $layoutColumnWidth ?? null,
                "columnLayoutColumnWidth" => $columnLayoutColumn->width(),
                "layoutColumnSplitting" => $layoutColumnSplitting,
              ]);
            } elseif ($block->type() == "mvlkssbreadcrumb") {
              $colorKey = null;
              if ($columnLayoutRow->columnRowBackgroundColor()->isNotEmpty()) {
                $colorKey = $columnLayoutRow->columnRowBackgroundColor()->value();
              } elseif ($layoutRowBackgroundColorExists) {
                $colorKey = $layoutRowBackgroundColorValue;
              }
              $breadcrumbTextColorLight = $colorKey
                ? $selectableBrandColors[$colorKey][
                  "light-contrast-tailwindcss-text-class"
                ]
                : null;
              $breadcrumbTextColorDark = $colorKey
                ? $selectableBrandColors[$colorKey][
                  "dark-contrast-tailwindcss-text-class"
                ]
                : null;
              snippet("blocks/" . $block->type(), [
                "block" => $block,
                "textColorLight" => $breadcrumbTextColorLight,
                "textColorDark" => $breadcrumbTextColorDark,
              ]);
            } else {
              echo $block;
            }
          } ?>
        <?= $columnLayoutColumn->blocks()->isNotEmpty() ? "</div>" : "" ?>
      </div>
    <?php endforeach; ?>
  </div>
<?php endforeach; ?>
