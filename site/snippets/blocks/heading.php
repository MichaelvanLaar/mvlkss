<?php
/**
 * =============================================================================
 * Heading Block Snippet
 * =============================================================================
 */
?>

<<?php
echo $level = $block->level()->or("h2");
echo $block->autoHyphenation()->toBool($default = false)
  ? " class=\"hyphens-auto\""
  : "";
?>><?= $block->text() ?></<?= $level ?>>