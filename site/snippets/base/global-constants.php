<?php
/**
 * =============================================================================
 * Global Constants Snippet for All Pages
 *
 * Defines the following constants:
 * - MARGIN_TOP_CLASSES
 * - MARGIN_BOTTOM_CLASSES
 * - MARGIN_START_CLASSES
 * - MARGIN_END_CLASSES
 * - PADDING_TOP_CLASSES
 * - PADDING_BOTTOM_CLASSES
 * - PADDING_START_CLASSES
 * - PADDING_END_CLASSES
 * - GAP_CLASSES
 * - SITE_COLORS
 * - SITE_COLORS_CSS_CUSTOM_PROPERTIES
 * - COLOR_BLACK
 * - COLOR_WHITE
 * =============================================================================
 */

/**
 * -----------------------------------------------------------------------------
 * Configuration: Spacing Utility Classes
 *
 * These constants assign Tailwind CSS utility classes to the respective options
 * which are used in panel fields whenever a website editor should be able to
 * choose how big a spacing (e.g. margin, padding, gap) should be.
 *
 * There are always the same five options available in the panel fields:
 * - none
 * - small
 * - medium
 * - large
 * - xlarge
 * -----------------------------------------------------------------------------
 */

// Margin
const MARGIN_TOP_CLASSES = [
  "none" => "mt-0",
  "small" => "mt-small",
  "medium" => "mt-medium",
  "large" => "mt-large",
  "xlarge" => "mt-xlarge",
];
const MARGIN_BOTTOM_CLASSES = [
  "none" => "mb-0",
  "small" => "mb-small",
  "medium" => "mb-medium",
  "large" => "mb-large",
  "xlarge" => "mb-xlarge",
];
const MARGIN_START_CLASSES = [
  "none" => "ms-0",
  "small" => "ms-small",
  "medium" => "ms-medium",
  "large" => "ms-large",
  "xlarge" => "ms-xlarge",
];
const MARGIN_END_CLASSES = [
  "none" => "me-0",
  "small" => "me-small",
  "medium" => "me-medium",
  "large" => "me-large",
  "xlarge" => "me-xlarge",
];

// Padding
const PADDING_TOP_CLASSES = [
  "none" => "pt-0",
  "small" => "pt-small",
  "medium" => "pt-medium",
  "large" => "pt-large",
  "xlarge" => "pt-xlarge",
];
const PADDING_BOTTOM_CLASSES = [
  "none" => "pb-0",
  "small" => "pb-small",
  "medium" => "pb-medium",
  "large" => "pb-large",
  "xlarge" => "pb-xlarge",
];
const PADDING_START_CLASSES = [
  "none" => "ps-0",
  "small" => "ps-small",
  "medium" => "ps-medium",
  "large" => "ps-large",
  "xlarge" => "ps-xlarge",
];
const PADDING_END_CLASSES = [
  "none" => "pe-0",
  "small" => "pe-small",
  "medium" => "pe-medium",
  "large" => "pe-large",
  "xlarge" => "pe-xlarge",
];

// Gap (for grid and flexbox layouts)
const GAP_CLASSES = [
  "none" => "gap-0",
  "small" => "gap-small",
  "medium" => "gap-medium",
  "large" => "gap-large",
  "xlarge" => "gap-xlarge",
];

/**
 * -----------------------------------------------------------------------------
 * Site Color Scheme
 * -----------------------------------------------------------------------------
 */

// The following two variables are only used internally. Their final values will
// be assigned to the globally available constants below.
$siteColors = [];
$siteColorsCssCustomProperties = "";

// Get the Site Color Scheme field from the site settings
$siteColorScheme = $site->siteColorScheme()->toStructure();

// Filling the prepared empty variables only makes sense if the Site Color
// Scheme field is not empty.
if ($siteColorScheme->isNotEmpty()) {
  $siteColorsCssCustomProperties .= "/* Site color scheme */\n";

  // Loop through each color group in the site color scheme
  foreach ($siteColorScheme as $siteColorGroup) {
    // Add the color group's information to the site colors array
    $siteColors[$siteColorGroup->id()] = [
      "name" => $siteColorGroup->name()->value(),
      "includeInColorSelect" => $siteColorGroup
        ->includeInColorSelect()
        ->toBool(),
      "lightMode" => $siteColorGroup->lightMode()->value(),
      "darkMode" => $siteColorGroup->darkMode()->value(),
      "contrastForLightMode" => $siteColorGroup->lightMode()->toMostReadable(),
      "contrastForDarkMode" => $siteColorGroup->darkMode()->toMostReadable(),
    ];

    // Add the color group’s information to the site colors CSS custom
    // properties
    $siteColorsCssCustomProperties .= sprintf(
      "        --site-color-%s-light-mode: %s;\n" .
        "        --site-color-%s-dark-mode: %s;\n" .
        "        --site-color-%s-contrast-for-light-mode: %s;\n" .
        "        --site-color-%s-contrast-for-dark-mode: %s;\n",
      $siteColorGroup->id(),
      $siteColorGroup->lightMode()->value(),
      $siteColorGroup->id(),
      $siteColorGroup->darkMode()->value(),
      $siteColorGroup->id(),
      $siteColorGroup->lightMode()->toMostReadable(),
      $siteColorGroup->id(),
      $siteColorGroup->darkMode()->toMostReadable()
    );
  }
}

// Create a constants for the site colors array
define("SITE_COLORS", $siteColors);

// Create a constant for the site colors CSS custom properties
define("SITE_COLORS_CSS_CUSTOM_PROPERTIES", $siteColorsCssCustomProperties);

// Create constants for the two default contrast colors of the color field.
// They will be used in some if clauses in the templates and snippets
const COLOR_BLACK = "#000000";
const COLOR_WHITE = "#ffffff";