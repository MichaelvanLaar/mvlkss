<?php
/**
 * =============================================================================
 * MvLKSS Button Block Snippet
 * =============================================================================
 */

// Check if the button color is set
$buttonColorIsSet = $block->color()->isNotEmpty();

// Set the background and/or border color related CSS classes for the button
if ($block->style() == "filled") {
  $buttonStyleClasses = $buttonColorIsSet
    ? "bg-[var(--button-color-light-mode)] dark:bg-[var(--button-color-dark-mode)] border-solid border-2 border-[var(--button-color-light-mode)] dark:border-[var(--button-color-dark-mode)] text-[var(--button-text-color-light-mode)] dark:text-[var(--button-text-color-dark-mode)]"
    : "bg-neutral-500 border-solid border-2 border-neutral-500 text-white";
} else {
  $buttonStyleClasses = $buttonColorIsSet
    ? "border-solid border-2 border-[var(--button-color-light-mode)] dark:border-[var(--button-color-dark-mode)] text-[var(--button-text-color-light-mode)] dark:text-[var(--button-text-color-dark-mode)]"
    : "border-solid border-2 border-neutral-500 text-neutral-500";
}

// Set the width related CSS class for the button
$buttonWidthClasses = $block->width() == "fullWidth" ? "w-full" : "";

// Construct the classes attribute for the button
$buttonClasses = [
  "inline-block text-center leading-none px-small py-2 no-underline text-lg",
  $buttonWidthClasses,
  $buttonStyleClasses,
];
$buttonClassAttribute = "class=\"" . implode(" ", $buttonClasses) . "\"";

// Construct the classes attribute for the button container
$alignMap = [
  "center" => "text-center",
  "end" => "text-end",
  "start" => "",
];
$alignmentValue = $block->align()->value();
$buttonContainerClasses = array_key_exists($alignmentValue, $alignMap)
  ? $alignMap[$alignmentValue]
  : $alignMap["start"];
$buttonContainerClassAttribute = "class=\"{$buttonContainerClasses}\"";

// Construct the style attribute for the button container
$buttonContainerStyleAttribute = $buttonColorIsSet
  ? "style=\"--button-color-light-mode: " .
    option("site-constants")["site-colors"][$block->color()->value()][
      "lightMode"
    ] .
    "; --button-color-dark-mode: " .
    option("site-constants")["site-colors"][$block->color()->value()][
      "darkMode"
    ] .
    "; --button-text-color-light-mode: " .
    ($block->style() == "filled"
      ? option("site-constants")["site-colors"][$block->color()->value()][
        "contrastForLightMode"
      ]
      : option("site-constants")["site-colors"][$block->color()->value()][
        "lightMode"
      ]) .
    "; --button-text-color-dark-mode: " .
    ($block->style() == "filled"
      ? option("site-constants")["site-colors"][$block->color()->value()][
        "contrastForDarkMode"
      ]
      : option("site-constants")["site-colors"][$block->color()->value()][
        "darkMode"
      ]) .
    ";\""
  : "";
?>

<!-- Button Container -->
<div
  <?= $buttonContainerClassAttribute ?>
  <?= $buttonContainerStyleAttribute ?>
>
  <!-- Button -->
  <a
    href="<?= $block->link() ?>"
    target="<?= $block->linkTarget() ?>"
    <?= $block->linkTarget() == "_blank" ? "rel=\"noopener\"" : "" ?>
    <?= $buttonClassAttribute ?>
  >
    <?= $block->text() ?>
  </a>
</div>
