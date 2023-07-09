<?php
/**
 * =============================================================================
 * Controller for Page Builder Snippet
 *
 * Uses the Kirby Snippet Controller plugin
 * Plugin details: https://github.com/lukaskleinschmidt/kirby-snippet-controller
 *
 * Provides variables for use in the header snippet:
 * - $layoutRowsData
 * =============================================================================
 */

return function ($page) {
  $layoutRows = $page->pageBuilder()->toLayouts();
  $layoutRowsData = [];

  foreach ($layoutRows as $layoutRow) {
    // Construct the ID attribute for the current row
    $layoutRowIdAttribute = $layoutRow->rowId()->isNotEmpty()
      ? sprintf("id=\"%s\"", $layoutRow->rowId())
      : "";

    // Set the top padding related CSS class for the current row
    $rowPaddingTopClass =
      option("site-constants")["spacing-utility-classes"]["padding-top"][
        (string) $layoutRow->rowPaddingTop()
      ] ?? "pt-0";

    // Set the bottom padding related CSS class for the current row
    $rowPaddingBottomClass =
      option("site-constants")["spacing-utility-classes"]["padding-bottom"][
        (string) $layoutRow->rowPaddingBottom()
      ] ?? "pb-0";

    // Set the column splitting related CSS class for the current row
    $layoutColumnSplitting = "column-splitting-";
    foreach ($layoutRow->columns() as $layoutColumn) {
      $layoutColumnSplitting .= $layoutColumn->width() . "-";
    }
    $layoutColumnSplitting = rtrim($layoutColumnSplitting, "-");

    // Set the background color related CSS class for the current row
    $rowBackgroundColorClasses = $layoutRow->rowBackgroundColor()->isNotEmpty()
      ? "bg-[var(--row-background-color-light-mode)] dark:bg-[var(--row-background-color-dark-mode)]"
      : "";

    // Construct the classes attribute for the current row
    $layoutRowClasses = [
      $layoutColumnSplitting,
      $layoutRow->rowClasses(),
      $rowPaddingTopClass,
      $rowPaddingBottomClass,
      $rowBackgroundColorClasses,
    ];
    $layoutRowClassAttribute = sprintf(
      "class=\"%s\"",
      implode(" ", $layoutRowClasses),
    );

    // Construct the style attribute for the current row
    $layoutRowStyleAttribute = $layoutRow->rowBackgroundColor()->isNotEmpty()
      ? sprintf(
        "style=\"--row-background-color-light-mode: %s; --row-background-color-dark-mode: %s;\"",
        option("site-constants")["site-colors"][
          $layoutRow->rowBackgroundColor()->value()
        ]["lightMode"],
        option("site-constants")["site-colors"][
          $layoutRow->rowBackgroundColor()->value()
        ]["darkMode"],
      )
      : "";

    // Add the row data to the array
    $layoutRowsData[] = [
      "layoutColumnSplitting" => $layoutColumnSplitting,
      "layoutRowIdAttribute" => $layoutRowIdAttribute,
      "layoutRowClassAttribute" => $layoutRowClassAttribute,
      "layoutRowStyleAttribute" => $layoutRowStyleAttribute,
      "layout" => $layoutRow,
    ];
  }

  // Return the variables to the snippet
  return [
    "layoutRowsData" => $layoutRowsData,
  ];
};
