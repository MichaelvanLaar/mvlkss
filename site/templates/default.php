<?php snippet('header') ?>

    <!-- Main section -->
    <div
      id="page-main-section"
      class="js-page-main-content flex-grow js:mt-[calc(var(--site-header-initial-height)_+_1.5rem)] no-js:mt-6"
    >
      <!-- Row -->  
      <div>
        <!-- Inner row container -->
        <div class="row-container-default">
          <h1><?= $page->title() ?></h1>
          <?= $page->text()->kt() ?>
        </div>
      </div>
    </div>

<?php snippet('footer') ?>
