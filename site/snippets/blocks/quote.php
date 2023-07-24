<?php
/**
 * =============================================================================
 * Quote Block Snippet
 * =============================================================================
 */
?>

<blockquote>
  <?= $block->text() ?>
  <?php if ($block->citation()->isNotEmpty()): ?>
    <footer>
      <cite class="not-italic"><?= $block->citation() ?></cite>
    </footer>
  <?php endif; ?>
</blockquote>
