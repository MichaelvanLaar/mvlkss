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
?>
<?php if ($src): ?>
  <figure
    <?= Html::attr(
      [
        //"data-ratio" => $ratio,
        //"data-crop" => $crop ? "true" : null,
        "data-layout-column-width" => $layoutColumnWidth ?? null,
        "data-grid-layout-column-width" => $gridLayoutColumnWidth ?? null,
      ],
      null,
      " "
    ) ?>
  >
    <?php if ($link->isNotEmpty()): ?>
      <a href="<?= Str::esc($link->toUrl()) ?>">
    <?php endif; ?>
      <img src="<?= $src ?>" alt="<?= $alt->esc() ?>" loading="lazy">
    <?php if ($link->isNotEmpty()): ?>
      </a>
    <?php endif; ?>

    <?php if ($caption->isNotEmpty()): ?>
      <figcaption>
        <?= $caption ?>
      </figcaption>
    <?php endif; ?>
  </figure>
<?php endif; ?>
