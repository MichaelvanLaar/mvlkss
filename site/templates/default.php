<?php snippet('header') ?>

    <!-- Main section -->
    <div class="flex-grow js:mt-[calc(var(--site-header-initial-height)_+_1.5rem)] no-js:mt-6 js-page-main-content">
      <div><!-- Row -->
        <div class="row-container-default"><!-- Inner row container -->
          <h1><?= $page->title() ?></h1>
          <?= $page->text()->kt() ?>
        </div>
      </div>
    </div>

<?php snippet('footer') ?>
