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
  $siteConstants = option("site-constants");
  $spacingUtilityClasses = $siteConstants["spacing-utility-classes"];

  foreach ($layoutRows as $layoutRow) {
    // Construct the ID attribute for the current row
    $rowId = $layoutRow->rowId();
    $layoutRowIdAttribute = $rowId->isNotEmpty()
      ? sprintf("id=\"%s\"", $rowId)
      : "";

    // Set the top and bottom padding related CSS class for the current row
    $rowPaddingTopValue = (string) $layoutRow->rowPaddingTop();
    $rowPaddingBottomValue = (string) $layoutRow->rowPaddingBottom();
    $rowPaddingTopClass =
      $spacingUtilityClasses["padding-top"][$rowPaddingTopValue] ?? "pt-0";
    $rowPaddingBottomClass =
      $spacingUtilityClasses["padding-bottom"][$rowPaddingBottomValue] ??
      "pb-0";

    // Set the column splitting related CSS class for the current row
    $layoutColumnSplitting = buildColumnSplitting($layoutRow);

    // Set the background color related CSS class for the current row
    $rowBackgroundColor = $layoutRow->rowBackgroundColor();
    $rowBackgroundColorExists = $rowBackgroundColor->isNotEmpty();
    $rowBackgroundColorValue = $rowBackgroundColor->value();
    $rowBackgroundColorClasses = $rowBackgroundColorExists
      ? "bg-[var(--row-background-color-light-mode)] dark:bg-[var(--row-background-color-dark-mode)]"
      : "";

    // Set the background image related CSS classes for the current row
    $rowBackgroundImageClasses = buildBackgroundImageClasses($layoutRow);

    // Construct the classes attribute for the current row
    $layoutRowClasses = [
      $layoutColumnSplitting,
      $layoutRow->rowClasses(),
      $rowPaddingTopClass,
      $rowPaddingBottomClass,
      $rowBackgroundColorClasses,
      $rowBackgroundImageClasses,
    ];
    $layoutRowClassAttribute = sprintf(
      "class=\"%s\"",
      implode(" ", $layoutRowClasses)
    );

    // Construct the style attribute for the current row
    $layoutRowStyleAttribute = buildStyleAttribute(
      $layoutRow,
      $rowBackgroundColorValue,
      $siteConstants,
      $rowBackgroundColorExists
    );

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
  return ["layoutRowsData" => $layoutRowsData];
};

/**
 * -----------------------------------------------------------------------------
 * Helper functions
 * -----------------------------------------------------------------------------
 */

function buildColumnSplitting($layoutRow) {
  $layoutColumnSplitting = "column-splitting-";
  foreach ($layoutRow->columns() as $layoutColumn) {
    $layoutColumnSplitting .= $layoutColumn->width() . "-";
  }
  return rtrim($layoutColumnSplitting, "-");
}

function buildBackgroundImageClasses($layoutRow) {
  $rowBackgroundImageClasses = "";

  // Early return the empty string if the row has no background image
  if ($layoutRow->rowBackgroundImage()->isEmpty()) {
    return $rowBackgroundImageClasses;
  }

  // Set the background image size related CSS class
  $rowBackgroundImageSize = $layoutRow->rowBackgroundImageSize();
  $rowBackgroundImageClasses .=
    $rowBackgroundImageSize == "cover" ? "bg-cover" : "bg-auto";

  // Set the background image position related CSS class
  $backgroundImagePositionMap = [
    "top-left" => " bg-left-top",
    "top-center" => " bg-top",
    "top-right" => " bg-right-top",
    "middle-left" => " bg-left",
    "middle-center" => " bg-center",
    "middle-right" => " bg-right",
    "bottom-left" => " bg-left-bottom",
    "bottom-center" => " bg-bottom",
    "bottom-right" => " bg-right-bottom",
  ];
  $rowBackgroundImageClasses .=
    $backgroundImagePositionMap[
      (string) $layoutRow->rowBackgroundImagePosition()
    ] ?? "";

  // Set the background image repeat related CSS class
  $backgroundImageRepeatMap = [
    "no-repeat" => " bg-no-repeat",
    "repeat" => " bg-repeat",
    "repeat-x" => " bg-repeat-x",
    "repeat-y" => " bg-repeat-y",
  ];
  $rowBackgroundImageClasses .=
    $backgroundImageRepeatMap[
      (string) $layoutRow->rowBackgroundImageRepeat()
    ] ?? "";

  // Return the background image related CSS classes
  return $rowBackgroundImageClasses;
}

function buildStyleAttribute(
  $layoutRow,
  $rowBackgroundColorValue,
  $siteConstants,
  $rowBackgroundColorExists
) {
  // Open the style attribute
  $layoutRowStyleAttribute = "style=\"";

  // Set the background color related CSS custom properties
  if ($rowBackgroundColorExists) {
    $layoutRowStyleAttribute .= sprintf(
      "--row-background-color-light-mode: %s; --row-background-color-dark-mode: %s;",
      $siteConstants["site-colors"][$rowBackgroundColorValue]["lightMode"],
      $siteConstants["site-colors"][$rowBackgroundColorValue]["darkMode"]
    );
  }

  // Close and early return the style attribute if the row has no background
  // image
  if ($layoutRow->rowBackgroundImage()->isEmpty()) {
    return $layoutRowStyleAttribute . "\"";
  }

  $rowBackgroundImageFile = $layoutRow->rowBackgroundImage()->toFile();
  $rowBackgroundImageFileExtension = $rowBackgroundImageFile->extension();

  // Add the background image related CSS rules to the style attribute
  $layoutRowStyleAttribute .= buildBackgroundImageUrl(
    $rowBackgroundImageFile,
    $rowBackgroundImageFileExtension
  );

  // Close and return the style attribute
  return $layoutRowStyleAttribute . "\"";
}

function buildBackgroundImageUrl(
  $rowBackgroundImageFile,
  $rowBackgroundImageFileExtension
) {
  // Set the background image fallback for older browsers
  $rowBackgroundImageStyle = sprintf(
    " background-image: url('%s');",
    $rowBackgroundImageFile->url()
  );

  // Add modern file formats (AVIF and/or WebP) of the background image for
  // modern browsers, in case the background image is a JPG or PNG file
  switch ($rowBackgroundImageFileExtension) {
    case "png":
      $rowBackgroundImageStyle .= sprintf(
        " background-image: url('%s');",
        $rowBackgroundImageFile->thumb("png->webp")->url()
      );
      break;
    case "jpg":
    case "jpeg":
      $rowBackgroundImageStyle .= sprintf(
        " background-image: url('%s');",
        $rowBackgroundImageFile->thumb("jpg->webp")->url()
      );
      $rowBackgroundImageStyle .= sprintf(
        " background-image: url('%s');",
        $rowBackgroundImageFile->thumb("jpg->avif")->url()
      );
      break;
  }

  // Return the background image related CSS rules
  return $rowBackgroundImageStyle;
}
