<?php
/**
 * =============================================================================
 * MvLKSS Button Block Snippet
 * =============================================================================
 */

// Check if the button color is set
$buttonColorIsSet = $block->color()->isNotEmpty();

// Get the “selectable brand colors” array from the site constants
$selectableBrandColors = option("site-constants")[
  "selectable-brand-colors"
];

// Set the background and/or border color related CSS classes as well as the
// text color related CSS classes for the button
if ($block->style() == "filled") {
  $buttonStyleClasses = $buttonColorIsSet
    ? $selectableBrandColors[$block->color()->value()][
        "light-tailwindcss-bg-class"
      ] .
      " " .
      $selectableBrandColors[$block->color()->value()][
        "dark-tailwindcss-bg-class"
      ] .
      " border-solid border-2 " .
      $selectableBrandColors[$block->color()->value()][
        "light-tailwindcss-border-class"
      ] .
      " " .
      $selectableBrandColors[$block->color()->value()][
        "dark-tailwindcss-border-class"
      ] .
      " " .
      $selectableBrandColors[$block->color()->value()][
        "light-contrast-tailwindcss-text-class"
      ] .
      " " .
      $selectableBrandColors[$block->color()->value()][
        "dark-contrast-tailwindcss-text-class"
      ]
    : "bg-neutral-500 dark:bg-neutral-500 border-solid border-2 border-neutral-500 dark:border-neutral-500 text-white dark:text-white";
} else {
  $buttonStyleClasses = $buttonColorIsSet
    ? "border-solid border-2 " .
      $selectableBrandColors[$block->color()->value()][
        "light-tailwindcss-border-class"
      ] .
      " " .
      $selectableBrandColors[$block->color()->value()][
        "dark-tailwindcss-border-class"
      ] .
      " " .
      $selectableBrandColors[$block->color()->value()][
        "light-tailwindcss-text-class"
      ] .
      " " .
      $selectableBrandColors[$block->color()->value()][
        "dark-tailwindcss-text-class"
      ]
    : "border-solid border-2 border-neutral-500 dark:border-neutral-500 text-neutral-500 dark:text-neutral-500";
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
?>

<!-- Button Container -->
<div
  <?= $buttonContainerClassAttribute ?>
>
  <!-- Button -->
  <a
    href="<?= $block->link()->toUrl() ?>"
    target="<?= $block->linkTarget() ?>"
    <?= $block->linkTarget() == "_blank" ? "rel=\"noopener\"" : "" ?>
    <?= $buttonClassAttribute ?>
  >
    <?= $block->text() ?>
  </a>
</div>
