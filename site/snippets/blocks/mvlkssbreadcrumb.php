<?php
/**
 * =============================================================================
 * MvLKSS Breadcrumb Navigation Block Snippet
 * =============================================================================
 */
?>

<nav
  class="not-prose text-neutral-500 print:text-black"
  style="
    --breadcrumb-divider: '<?= $site->defaultBreadcrumbDivider()->or(">") ?>';"
>
  <span class="me-1 hidden text-[80%] print:inline"><?= t(
    "You are here"
  ) ?>:</span>
  <ol class="inline leading-4">
    <?php if (!$page->isHomePage()): ?>
      <li class="inline text-[80%] after:mx-1 after:text-base after:content-[var(--breadcrumb-divider)]">
        <a
          href="<?= $site->url() ?>"
          class="underline print:text-black"
        ><?= $site->title() ?></a>
      </li>
      <?php foreach ($page->parents() as $p): ?>
        <li class="inline text-[80%] after:mx-1 after:text-base after:content-[var(--breadcrumb-divider)]">
          <a
            href="<?= $p->url() ?>"
            class="underline print:text-black"
          ><?= $p->title() ?></a>
        </li>
      <?php endforeach; ?>
      
      <li class="inline text-[80%]"><?= $page->title() ?></li>
    <?php endif; ?>
  </ol>
</nav>