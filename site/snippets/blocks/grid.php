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

// Set column width classes for the “1/1”, “1/2” and “1/3” options of the
// respective layout settings field using Tailwind CSS classes
$gridColumnWidthClasses = [
  "1/1" => "col-span-full",
  "1/2" => "col-span-3",
  "1/3" => "col-span-2",
];

/**
 * -----------------------------------------------------------------------------
 * Output
 * -----------------------------------------------------------------------------
 */

// Loop through all grid layout rows
foreach ($block->grid()->toLayouts() as $gridLayoutRow): ?>

  <?php
  // Construct the ID attribute for the current grid row
  $gridRowIdAttribute = $gridLayoutRow->gridRowId()->isNotEmpty()
    ? " id=\"{$gridLayoutRow->gridRowId()}\""
    : "";

  // Set the gap related CSS class for the current grid row
  $gridRowGapClass =
    option("site-constants")["spacing-utility-classes"]["gap"][
      (string) $gridLayoutRow->gridRowGap()
    ] ?? "gap-0";

  // Set the top padding related CSS class for the current grid row
  $gridRowPaddingTopClass =
    option("site-constants")["spacing-utility-classes"]["padding-top"][
      (string) $gridLayoutRow->gridRowPaddingTop()
    ] ?? "pt-0";

  // Set the bottom padding related CSS class for the current grid row
  $gridRowPaddingBottomClass =
    option("site-constants")["spacing-utility-classes"]["padding-bottom"][
      (string) $gridLayoutRow->gridRowPaddingBottom()
    ] ?? "pb-0";

  // Set the start padding related CSS class for the current grid row
  $gridRowPaddingStartClass =
    option("site-constants")["spacing-utility-classes"]["padding-start"][
      (string) $gridLayoutRow->gridRowPaddingStart()
    ] ?? "ps-0";

  // Set the end padding related CSS class for the current grid row
  $gridRowPaddingEndClass =
    option("site-constants")["spacing-utility-classes"]["padding-end"][
      (string) $gridLayoutRow->gridRowPaddingEnd()
    ] ?? "pe-0";

  // Set the background color related CSS class for the current grid row
  $gridRowBackgroundColorClasses = $gridLayoutRow
    ->gridRowBackgroundColor()
    ->isNotEmpty()
    ? "bg-[var(--grid-row-background-color-light-mode)] dark:bg-[var(--grid-row-background-color-dark-mode)]"
    : "";

  // Construct the classes attribute for the current grid row
  $gridRowClasses = [
    $gridLayoutRow->gridRowClasses(),
    "grid",
    "grid-cols-6",
    $gridRowGapClass,
    $gridRowPaddingTopClass,
    $gridRowPaddingBottomClass,
    $gridRowPaddingStartClass,
    $gridRowPaddingEndClass,
    $gridRowBackgroundColorClasses,
  ];
  $gridRowClassAttribute = "class=\"" . implode(" ", $gridRowClasses) . "\"";

  // Construct the style attribute for the current grid row
  if ($gridLayoutRow->gridRowBackgroundColor()->isNotEmpty()) {
    $gridRowStyleAttribute =
      "style=\"--grid-row-background-color-light-mode: " .
      option("site-constants")["site-colors"][
        $gridLayoutRow->gridRowBackgroundColor()->value()
      ]["lightMode"] .
      "; --grid-row-background-color-dark-mode: " .
      option("site-constants")["site-colors"][
        $gridLayoutRow->gridRowBackgroundColor()->value()
      ]["darkMode"] .
      ";\"";
  } else {
    $gridRowStyleAttribute = "";
  }
  ?>

  <!-- Grid Row -->
  <div
    <?= $gridRowIdAttribute ?>
    <?= $gridRowClassAttribute ?>
    <?= $gridRowStyleAttribute ?>
  >
    <?php foreach ($gridLayoutRow->columns() as $gridLayoutColumn): ?>
      <!-- Grid Column -->
      <?php
      $gridColumnClassOutput = "";
      $gridColumnClassOutput .=
        $gridColumnWidthClasses[$gridLayoutColumn->width()] ?? "col-span-full";
      if ($gridLayoutRow->gridRowBackgroundColor()->isNotEmpty()) {
        $gridRowBackgroundColorValue = $gridLayoutRow
          ->gridRowBackgroundColor()
          ->value();
        $gridContrastColorForLightMode = option("site-constants")[
          "site-colors"
        ][$gridRowBackgroundColorValue]["contrastForLightMode"];
        $gridContrastColorForDarkMode = option("site-constants")["site-colors"][
          $gridRowBackgroundColorValue
        ]["contrastForDarkMode"];
        switch ($gridContrastColorForLightMode) {
          case "#000000":
            $gridColumnClassOutput .= " prose-black";
            break;
          case "#ffffff":
            $gridColumnClassOutput .= " prose-white";
            break;
        }
        switch ($gridContrastColorForDarkMode) {
          case "#000000":
            $gridColumnClassOutput .= " dark:prose-black";
            break;
          case "#ffffff":
            $gridColumnClassOutput .= " dark:prose-white";
            break;
        }
      } else {
        $gridColumnClassOutput .= " prose-neutral dark:prose-invert";
      }
      ?>
      <div class="<?= $gridColumnClassOutput ?> prose max-w-none">
        <?php foreach ($gridLayoutColumn->blocks() as $block) {
          if (in_array($block->type(), ["image"])) {
            snippet("blocks/" . $block->type(), [
              "block" => $block,
              "layoutColumnWidth" => $layoutColumnWidth ?? null,
              "gridLayoutColumnWidth" => $gridLayoutColumn->width(),
              "layoutColumnSplitting" => $layoutColumnSplitting,
            ]);
          } else {
            echo $block;
          }
        } ?>
      </div>
    <?php endforeach; ?>
  </div>
<?php endforeach; ?>
