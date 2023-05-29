<?php snippet('header') ?>

    <!-- Main section -->
    <div class="flex-grow mt-[calc(var(--site-header-initial-height))] js-page-main-content">
      <div><!-- Row -->
        <div class="row-container-default"><!-- Inner row container -->
          <h1><?= $page->title() ?></h1>
          <?= $page->text()->kt() ?>
        </div>
      </div>
    </div>

<?php snippet('footer') ?>
