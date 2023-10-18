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
      class="js-page-main-content flex-grow js:mt-[var(--site-header-initial-height)] print:js:mt-0"
      role="main"
    >
      <?php snippet("fields/page-builder"); ?>

    </div>

<?php snippet("base/footer"); ?>
