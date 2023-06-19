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
      PADDING_TOP_CLASSES[(string) $layoutRow->rowPaddingTop()] ?? "pt-0";

    // Set the bottom padding related CSS class for the current row
    $rowPaddingBottomClass =
      PADDING_BOTTOM_CLASSES[(string) $layoutRow->rowPaddingBottom()] ?? "pb-0";

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
      implode(" ", $layoutRowClasses)
    );

    // Construct the style attribute for the current row
    $layoutRowStyleAttribute = $layoutRow->rowBackgroundColor()->isNotEmpty()
      ? sprintf(
        "style=\"--row-background-color-light-mode: %s; --row-background-color-dark-mode: %s;\"",
        SITE_COLORS[$layoutRow->rowBackgroundColor()->value()]["lightMode"],
        SITE_COLORS[$layoutRow->rowBackgroundColor()->value()]["darkMode"]
      )
      : "";

    // Add the row data to the array
    $layoutRowsData[] = [
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
