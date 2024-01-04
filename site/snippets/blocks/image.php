<?php
/**
 * =============================================================================
 * Image Block Snippet
 * =============================================================================
 */

/**
 * -----------------------------------------------------------------------------
 * Configuration
 * -----------------------------------------------------------------------------
 */

// Set the values (media query and width) for the “sizes” attribute of “source”
// and “img” elements to match the column widths of the respective layout
// settings. The integer values represent sizes in pixels.
// See “page-builder.php” for column layouts depending on the viewport width.
// Also see section “Fluid font size and zoom effect” in “main.css”, since this
// setting has an effect on the sizes used here.
$layoutColumnMaxWidths = [
  "1/1" => [
    "min-width: 1024px" => 1458,
    "min-width: 768px" => 965,
    "default" => 714,
  ],
  "1/2" => [
    "min-width: 1024px" => 696,
    "min-width: 768px" =>
      $layoutColumnSplitting == "column-splitting-1/4-1/2-1/4" ? 965 : 468,
    "default" => 714,
  ],
  "1/3" => [
    "min-width: 1024px" => 442,
    "min-width: 768px" => 468,
    "default" => 714,
  ],
  "1/4" => [
    "min-width: 1024px" => 315,
    "min-width: 768px" => 468,
    "default" => 714,
  ],
];

/**
 * -----------------------------------------------------------------------------
 * Output
 * -----------------------------------------------------------------------------
 */

use Kirby\Toolkit\Str;

$alt = $block->alt();
$caption = $block->caption();
//$crop = $block->crop()->isTrue();
$lightbox = $block->lightbox()->toBool($default = false);
$link = $block->link();
$linkTarget = $block->linkTarget()->or("_self");
//$ratio = $block->ratio()->or("auto");
$src = null;
$image = null;

// Fill $src and $alt with values depending on the type of image (local or
// remote).
if ($block->location() == "web") {
  $src = $block->src()->esc();
} elseif ($image = $block->image()->toFile()) {
  $alt = $alt->or($image->alt());
  $src = $image->url();
}

// If clause just to double check that we really have an image to work with.
if ($src):

  $linkUrl = $link->isNotEmpty() ? Str::esc($link->toUrl()) : null;
  $imgSrcset = $image ? $image->srcset() : null;
  $altEsc = $alt->esc();
  $srcsetsForCurrentImageType = $image
    ? option("site-constants.thumb-srcsets-selector." . $image->extension())
    : [];
  $thumbSrcsetsSelector = option("site-constants.thumb-srcsets-selector");
  $thumbSrcsets = option("site-constants.thumb-srcsets");

  // Extract the correct set of values for the “sizes” attribute of “source” and
  // “img” elements and take into account the column width reduction in case the
  // image is contained in a grid block within the layout field.
  $sizesAttribute = [];
  foreach (
    $layoutColumnMaxWidths[$layoutColumnWidth]
    as $mediaQuery => $maxWidth
  ) {
    if ($gridLayoutColumnWidth ?? null) {
      switch ($gridLayoutColumnWidth) {
        case "1/2":
          $maxWidth = ceil($maxWidth / 2);
          break;
        case "1/3":
          $maxWidth = ceil($maxWidth / 3);
          break;
      }
    }
    $sizesAttribute[] = sprintf("(%s) %spx", $mediaQuery, $maxWidth);
  }

  // Remove the incorrect “default media query” from the “sizes” attribute,
  // since the default value is simply set as the last option in the “sizes”
  // attribute without a preceding media query.
  $sizesAttribute = str_replace(
    "(default) ",
    "",
    implode(", ", $sizesAttribute)
  );
  ?>
<figure>
  <?= $link->isNotEmpty() && !$lightbox
    ? "<a href='$linkUrl' target='$linkTarget'>"
    : "" ?>
  <picture class="not-prose">
    <?php if (
      $image &&
      array_key_exists($image->extension(), $thumbSrcsetsSelector)
    ): ?>
      <?php foreach ($srcsetsForCurrentImageType as $thumbSrcset): ?>
        <?php
        $sourceSrcsetAttribute = $image->srcset($thumbSrcset);
        $sourceTypeAttribute = option(
          "site-constants.thumb-srcsets." .
            ($thumbSrcset ?? "default") .
            "." .
            array_key_first($thumbSrcsets[$thumbSrcset ?? "default"]) .
            ".type-attribute-for-source-element"
        );
        ?>
        <source
          srcset="<?= $sourceSrcsetAttribute ?>"
          <?php if ($image->extension() != "svg"): ?>
            sizes="<?= $sizesAttribute ?>"
          <?php endif; ?>
          type="<?= $sourceTypeAttribute ?>"
        >
      <?php endforeach; ?>
    <?php endif; ?>

    <img
      src="<?= $src ?>"
      <?php if ($image && $image->extension() != "svg"): ?>
        srcset="<?= $imgSrcset ?>"
        sizes="<?= $sizesAttribute ?>"
      <?php endif; ?>
      alt="<?= $altEsc ?>"
      class="mx-auto
        <?= $lightbox ? "cursor-pointer" : "" ?>"
      <?php if ($image): ?>
        width="<?= $image->width() ?>"
        height="<?= $image->height() ?>"
      <?php endif; ?>
      loading="lazy"
      <?= $lightbox
        ? "data-image-modal-trigger data-image-modal-url=\"" .
          $image->url() .
          "\""
        : "" ?>
    >
  </picture>
  <?= $link->isNotEmpty() && !$lightbox ? "</a>" : "" ?>

  <?= $caption->isNotEmpty() ? "<figcaption>{$caption}</figcaption>" : "" ?>
</figure>
<?php
endif; ?>
