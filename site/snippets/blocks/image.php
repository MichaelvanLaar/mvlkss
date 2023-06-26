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

if ($block->location() == "web") {
  $src = $block->src()->esc();
} elseif ($image = $block->image()->toFile()) {
  $alt = $alt->or($image->alt());
  $src = $image->url();
}

if ($src):

  $attr = [
    "data-layout-column-width" => $layoutColumnWidth ?? null,
    "data-grid-layout-column-width" => $gridLayoutColumnWidth ?? null,
  ];

  $linkUrl = Str::esc($link->toUrl());

  $srcsetsForCurrentImageType = $image
    ? option("site-constants.thumb-srcsets-selector." . $image->extension())
    : [];

  $imgSrcset = $image ? $image->srcset() : null;
  $altEsc = $alt->esc();
  ?>
<figure <?= Html::attr($attr, null, " ") ?>>
  <?= $link->isNotEmpty() ? "<a href='$linkUrl'>" : "" ?>
  <picture>
    <?php if (
      $image &&
      array_key_exists(
        $image->extension(),
        option("site-constants.thumb-srcsets-selector")
      )
    ): ?>
      <?php foreach ($srcsetsForCurrentImageType as $thumbSrcset): ?>
        <source srcset="<?= $image->srcset($thumbSrcset) ?>" type="<?= option(
  "site-constants.thumb-srcsets." .
    ($thumbSrcset ?? "default") .
    "." .
    array_key_first(
      option("site-constants.thumb-srcsets." . ($thumbSrcset ?? "default"))
    ) .
    ".type-attribute-for-source-element"
) ?>">
      <?php endforeach; ?>
    <?php endif; ?>
    <img
      src="<?= $src ?>"
      srcset="<?= $imgSrcset ?>"
      alt="<?= $altEsc ?>"
      class="mx-auto"
      loading="lazy"
    >
  </picture>
  <?= $link->isNotEmpty() ? "</a>" : "" ?>

  <?= $caption->isNotEmpty() ? "<figcaption>{$caption}</figcaption>" : "" ?>
</figure>
<?php
endif; ?>
