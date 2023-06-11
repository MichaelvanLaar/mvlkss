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
  /**
   * ---------------------------------------------------------------------------
   * Configuration
   * ---------------------------------------------------------------------------
   */

  // Set vertical padding values for the “small”, “medium” and “large” options of the
  // respective layout settings field using Tailwind CSS classes
  $layoutrowPaddingVerticalValues = [
    "small" => "py-small",
    "medium" => "py-medium",
    "large" => "py-large",
    "xlarge" => "py-xlarge",
  ];

  /**
   * ---------------------------------------------------------------------------
   * Processing rows and columns
   * ---------------------------------------------------------------------------
   */
  $layoutRows = $page->pageBuilder()->toLayouts();
  $layoutRowsData = [];

  foreach ($layoutRows as $layoutRow) {
    // Construct the ID attribute for the current row
    $layoutRowId = $layoutRow->rowId()->isNotEmpty()
      ? " id=\"" . $layoutRow->rowId() . "\""
      : "";

    // Set the vertical padding related CSS class for the current row
    $layoutrowPaddingVerticalKey = (string) $layoutRow->rowPaddingVertical();
    $rowPaddingVerticalClass =
      $layoutrowPaddingVerticalValues[$layoutrowPaddingVerticalKey] ?? "py-0";

    // Set the column splitting related CSS class for the current row
    $layoutColumnSplitting = "column-splitting-";
    foreach ($layoutRow->columns() as $layoutColumn) {
      $layoutColumnSplitting .= $layoutColumn->width() . "-";
    }
    $layoutColumnSplitting = rtrim($layoutColumnSplitting, "-");

    // Construct the classes attribute for the current row
    $layoutRowClass =
      "class=\"" .
      $layoutColumnSplitting .
      " " .
      $layoutRow->rowClasses() .
      " " .
      $rowPaddingVerticalClass .
      "\"";

    // Construct the style attribute for the current row
    $layoutRowStyle = $layoutRow->rowBackgroundColor()
      ? "style=\"background-color: " .
        $layoutRow->rowBackgroundColor()->toColor("rgb") .
        ";\""
      : "";

    // Add the row data to the array
    $layoutRowsData[] = [
      "layoutRowId" => $layoutRowId,
      "layoutRowClass" => $layoutRowClass,
      "layoutRowStyle" => $layoutRowStyle,
      "layout" => $layoutRow,
    ];
  }

  /**
   * ---------------------------------------------------------------------------
   * Return the variables to the snippet
   * ---------------------------------------------------------------------------
   */
  return [
    "layoutRowsData" => $layoutRowsData,
  ];
};
