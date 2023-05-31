<?php
/**
 * =============================================================================
 * Footer snippet for all pages
 * 
 * Uses “header.controller.php” via the Kirby Snippet Controller plugin
 * Plugin details: https://github.com/lukaskleinschmidt/kirby-snippet-controller
 * 
 * Receives variables from snippet controller:
 * - $footerMenuItems
 * =============================================================================
 */
?>
    <!-- Page footer -->
    <!-- Row -->
    <footer
      id="page-footer"
      class="w-full bg-neutral-600 py-6 dark:bg-neutral-900"
    >
      <!-- Inner row container -->
      <div class="row-container-default flex justify-between text-white">
        <nav class="flex items-center">
          <ul class="flex">
            <?php foreach ($footerMenuItems as $menuItem): ?>
              <li class="me-6">
                <a href="<?= $menuItem["url"] ?>" target="<?= $menuItem["target"] ?>">
                  <?= $menuItem["title"] ?>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        </nav>
        <div>Social Media</div>
      </div>
    </footer>

    <?= js("assets/js/main.js") ?>
  </body>
</html>
