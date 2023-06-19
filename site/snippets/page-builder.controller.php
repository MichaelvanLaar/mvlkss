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

return function ($page, $data) {
  /**
   * ---------------------------------------------------------------------------
   * Configuration
   * ---------------------------------------------------------------------------
   */

  // Set vertical padding values for the “small”, “medium” and “large” options
  // of the respective layout settings field using Tailwind CSS classes
  $rowPaddingTopValues = [
    "none" => "pt-0",
    "small" => "pt-small",
    "medium" => "pt-medium",
    "large" => "pt-large",
    "xlarge" => "pt-xlarge",
  ];
  $rowPaddingBottomValues = [
    "none" => "pb-0",
    "small" => "pb-small",
    "medium" => "pb-medium",
    "large" => "pb-large",
    "xlarge" => "pb-xlarge",
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
    $layoutRowIdAttribute = $layoutRow->rowId()->isNotEmpty()
      ? " id=\"" . $layoutRow->rowId() . "\""
      : "";

    // Set the top padding related CSS class for the current row
    $rowPaddingTopKey = (string) $layoutRow->rowPaddingTop();
    $rowPaddingTopClass = $rowPaddingTopValues[$rowPaddingTopKey] ?? "pt-0";

    // Set the bottom padding related CSS class for the current row
    $rowPaddingBottomKey = (string) $layoutRow->rowPaddingBottom();
    $rowPaddingBottomClass =
      $rowPaddingBottomValues[$rowPaddingBottomKey] ?? "pb-0";

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
    $layoutRowClassAttribute =
      "class=\"" . implode(" ", $layoutRowClasses) . "\"";

    // Construct the style attribute for the current row
    if ($layoutRow->rowBackgroundColor()->isNotEmpty()) {
      $layoutRowStyleAttribute =
        "style=\"--row-background-color-light-mode: " .
        $data["siteColors"][$layoutRow->rowBackgroundColor()->value()][
          "lightMode"
        ] .
        "; --row-background-color-dark-mode: " .
        $data["siteColors"][$layoutRow->rowBackgroundColor()->value()][
          "darkMode"
        ] .
        ";\"";
    } else {
      $layoutRowStyleAttribute = "";
    }

    // Add the row data to the array
    $layoutRowsData[] = [
      "layoutRowIdAttribute" => $layoutRowIdAttribute,
      "layoutRowClassAttribute" => $layoutRowClassAttribute,
      "layoutRowStyleAttribute" => $layoutRowStyleAttribute,
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
