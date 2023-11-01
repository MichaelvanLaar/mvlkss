<?php
/**
 * =============================================================================
 * MvLKSS Breadcrumb Navigation Block Snippet
 *
 * Optional variables from the snippet call:
 * - $textColorLight (string)
 * - $textColorDark (string)
 * =============================================================================
 */

// Configuration:
// Default Tailwind CSS classes for text colors in leight and dark mode.
// Will only be used it they are not set in the snippet call.
$textColorLightDefault = "text-neutral-500";
$textColorDarkDefault = "text-neutral-500";

// Set text colors if this has not been done in the snippet call
$textColorLight = $textColorLight ?? $textColorLightDefault;
$textColorDark = $textColorDark ?? $textColorDarkDefault;

// Provide a default value for the “includeCurrentPage” variable in case it has
// not been set in the snippet call or via the block’s settings
if (!isset($includeCurrentPage)) {
  $includeCurrentPage =
    isset($block) && $block->includeCurrentPage()
      ? $block->includeCurrentPage()->toBool()
      : true;
}
?>

<!-- Breadcrumb navigation -->
<nav
  class="not-prose print:text-black
    <?= $textColorLight ?>
    <?= $textColorDark ?>"
  style="
    --breadcrumb-divider: '<?= $site->defaultBreadcrumbDivider()->or(">") ?>';"
>
  <span class="me-1 hidden text-[80%] print:inline"><?= t(
    "You are here"
  ) ?>:</span>
  <ol class="inline leading-4">
    <?php if (!$page->isHomePage()): ?>
      <!-- Home page -->
      <li class="inline text-[80%] after:mx-1 after:text-base after:content-[var(--breadcrumb-divider)]">
        <a
          href="<?= $site->url() ?>"
          class="underline"
        ><?= $site->title() ?></a>
      </li>

      <?php foreach ($page->parents() as $p): ?>
        <!-- Parent pages -->
        <li
          class="inline text-[80%] after:ms-1 after:text-base after:content-[var(--breadcrumb-divider)]
            <?= $includeCurrentPage ? "after:me-1" : "" ?>"
        >
          <a
            href="<?= $p->url() ?>"
            class="underline"
          ><?= $p->title() ?></a>
        </li>
      <?php endforeach; ?>
      
      <?php if ($includeCurrentPage): ?>
        <!-- Current page -->
        <li class="inline text-[80%]"><?= $page->title() ?></li>
      <?php endif; ?>
    <?php endif; ?>
  </ol>
</nav>
