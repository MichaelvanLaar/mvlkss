<?php snippet("base/header"); ?>

    <!-- MAIN SECTION -->
    <div
      id="page-main-section"
      class="js-page-main-content flex-grow js:mt-[var(--site-header-initial-height)]"
    >
      <?php snippet("page-builder", data: ["siteColors" => $GLOBALS["siteColors"]]); ?>

    </div>

<?php snippet("base/footer"); ?>
