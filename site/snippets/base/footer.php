<?php
/**
 * =============================================================================
 * Footer Snippet for All Pages
 * 
 * Uses “header.controller.php” via the Kirby Snippet Controller plugin
 * Plugin details: https://github.com/lukaskleinschmidt/kirby-snippet-controller
 * 
 * Receives variables from snippet controller:
 * - $footerMenuItems
 * =============================================================================
 */
?>
    <!-- PAGE FOOTER -->
    <!-- Row -->
    <footer
      id="page-footer"
      class="w-full bg-neutral-600 py-medium dark:bg-neutral-900 print:hidden"
      role="contentinfo"
    >
      <!-- Inner row container -->
      <div class="row-container-default flex flex-col items-center justify-between text-white md:flex-row md:items-stretch">
        <nav class="mb-3 flex items-center md:mb-0" aria-label="Footer Links">
          <ul class="flex">
            <?php foreach ($footerMenuItems as $menuItem): ?>
              <li class="me-medium last:me-0">
                <a
                  href="<?= $menuItem["url"] ?>"
                  target="<?= $menuItem["target"] ?>"
                >
                  <?= $menuItem["title"] ?>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        </nav>
        <div>
          Social Media Links
        </div>
      </div>
    </footer>

    <!-- Image modal (used as lightbox in image blocks) -->
    <div
      id="image-modal"
      class="hidden fixed top-0 start-0 z-40 w-screen h-screen bg-black/70 justify-center items-center opacity-0 transition-opacity duration-300 ease-in-out print:hidden"
    >
      <!-- Loading indicator -->
      <div id="image-modal-loader" class="hidden absolute inset-0 items-center justify-center" role="status">
        <div class="border-neutral-400 h-xlarge w-xlarge animate-spin rounded-full border-[1rem] border-t-neutral-800"></div>
      </div>

      <!-- Close button of the modal -->
      <button
        class="fixed z-50 top-6 end-8 text-white text-5xl font-bold [text-shadow:_0_0_20px_rgb(0_0_0_/_50%)]"
        data-image-modal-close
        >&times;</button
      >
      
      <!-- A big image will be displayed here -->
      <img id="image-modal-img" class="max-w-[90vw] max-h-[90vh] object-cover" />
    </div>

    <?= js("assets/js/main.js") ?>
  </body>
</html>
