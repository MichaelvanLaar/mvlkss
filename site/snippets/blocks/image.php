<?php
/**
 * =============================================================================
 * Image Block Snippet
 * =============================================================================
 */

$alt = $block->alt();
$caption = $block->caption();
//$crop = $block->crop()->isTrue();
$link = $block->link();
//$ratio = $block->ratio()->or("auto");
$src = null;
$image = null;

if ($block->location() == "web") {
  $src = $block->src()->esc();
} elseif ($image = $block->image()->toFile()) {
  $alt = $alt->or($image->alt());
  $src = $image->url();
}

if ($src):

  $layoutColumnMaxWidths = [
    "1/1" => [
      "min-width: 1024px" => 1524,
      "min-width: 768px" => 965,
      "default" => 714,
    ],
    "1/2" => [
      "min-width: 1024px" => 746,
      "min-width: 768px" =>
        $layoutColumnSplitting == "column-splitting-1/4-1/2-1/4" ? 965 : 468,
      "default" => 714,
    ],
    "1/3" => [
      "min-width: 1024px" => 468,
      "min-width: 768px" => 468,
      "default" => 714,
    ],
    "1/4" => [
      "min-width: 1024px" => 357,
      "min-width: 768px" => 468,
      "default" => 714,
    ],
  ];

  $linkUrl = Str::esc($link->toUrl());
  $imgSrcset = $image ? $image->srcset() : null;
  $altEsc = $alt->esc();

  $srcsetsForCurrentImageType = $image
    ? option("site-constants.thumb-srcsets-selector." . $image->extension())
    : [];

  $figureSourceSizesAttribute = [];
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
    $figureSourceSizesAttribute[] = sprintf(
      "(%s) %spx",
      $mediaQuery,
      $maxWidth,
    );
  }

  $sizesAttribute = str_replace(
    "(default) ",
    "",
    implode(", ", $figureSourceSizesAttribute),
  );
  $thumbSrcsetsSelector = option("site-constants.thumb-srcsets-selector");
  $thumbSrcsets = option("site-constants.thumb-srcsets");
  ?>
<figure>
  <?= $link->isNotEmpty() ? "<a href='$linkUrl'>" : "" ?>
  <picture>
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
            ".type-attribute-for-source-element",
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
      class="mx-auto"
      <?php if (
        $image &&
        ($image->extension() == "jpg" || $image->extension() == "jpeg")
      ): ?>
        style="background-color: <?= $image->color() ?>"
      <?php endif; ?>
      <?php if ($image): ?>
        width="<?= $image->width() ?>"
        height="<?= $image->height() ?>"
      <?php endif; ?>
      loading="lazy"
    >
  </picture>
  <?= $link->isNotEmpty() ? "</a>" : "" ?>

  <?= $caption->isNotEmpty() ? "<figcaption>{$caption}</figcaption>" : "" ?>
</figure>
<?php
endif; ?>
