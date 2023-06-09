<?php
/**
 * =============================================================================
 * Default Page Template
 * =============================================================================
 */

snippet("base/header"); ?>

    <!-- MAIN SECTION -->
    <div
      id="page-main-section"
      class="js-page-main-content flex-grow js:mt-[var(--site-header-initial-height)]"
    >
      <?php snippet("fields/page-builder"); ?>

    </div>

<?php snippet("base/footer"); ?>
