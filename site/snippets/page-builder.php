<?php
/**
 * =============================================================================
 * Page Builder Snippet
 *
 * Layout field for main content of a page.
 *
 * Uses “page-builder.controller.php” via the Kirby Snippet Controller plugin
 * Plugin details: https://github.com/lukaskleinschmidt/kirby-snippet-controller
 *
 * Receives variables from snippet controller:
 * - $layoutRowsData
 * =============================================================================
 */

/**
 * -----------------------------------------------------------------------------
 * Configuration: Column Width Classes
 * -----------------------------------------------------------------------------
 */

// Set column width classes for the “1/2”, “1/3”, “2/3” and “1/4” options of the
// respective layout settings field using Tailwind CSS classes
$columnWidthClasses = [
  "1/2" => "column-width-1/2 col-span-full md:col-span-6",
  "1/3" => "column-width-1/3 col-span-full md:col-span-6 lg:col-span-4",
  "2/3" => "column-width-2/3 col-span-full md:col-span-6 lg:col-span-8",
  "1/4" => "column-width-1/4 col-span-full md:col-span-6 lg:col-span-3",
];

/**
 * -----------------------------------------------------------------------------
 * Output
 * -----------------------------------------------------------------------------
 */

// Classes required for the responsive design of the multi-column layout
$innerRowGridClasses =
  "grid grid-cols-12 gap-medium md:[&_>_.column-width-1\/3:nth-child(3)]:col-start-4 lg:[&_>_.column-width-1\/3:nth-child(3)]:col-start-auto [.column-splitting-1\/4-1\/2-1\/4_>_&_>_.column-width-1\/4.empty-column]:hidden lg:[.column-splitting-1\/4-1\/2-1\/4_>_&_>_.column-width-1\/4.empty-column]:block md:[.column-splitting-1\/4-1\/2-1\/4_>_&_>_.column-width-1\/4]:col-start-4 lg:[.column-splitting-1\/4-1\/2-1\/4_>_&_>_.column-width-1\/4]:col-start-auto md:[:is(.column-splitting-1\/4-1\/2-1\/4,_.column-splitting-1\/4-1\/4-1\/2,_.column-splitting-1\/2-1\/4-1\/4)_>_&_>_.column-width-1\/2]:col-span-full lg:[:is(.column-splitting-1\/4-1\/2-1\/4,_.column-splitting-1\/4-1\/4-1\/2,_.column-splitting-1\/2-1\/4-1\/4)_>_&_>_.column-width-1\/2]:col-span-6";
?>
<?php foreach ($layoutRowsData as $layoutRow): ?>
  <!-- Row -->
  <section
    <?= $layoutRow["layoutRowIdAttribute"] ?>
    <?= $layoutRow["layoutRowClassAttribute"] ?>
    <?= $layoutRow["layoutRowStyleAttribute"] ?>
  >
    <!-- Inner row container -->
    <div class="<?= $layoutRow[
      "layout"
    ]->rowWidth() ?> <?= $innerRowGridClasses ?>">
      <?php foreach ($layoutRow["layout"]->columns() as $layoutColumn): ?>
        <!-- Column -->
        <?php
        $columnClassOutput = $layoutColumn->blocks()->isEmpty()
          ? "empty-column "
          : "";
        $columnClassOutput .=
          $columnWidthClasses[$layoutColumn->width()] ??
          "column-width-" . $layoutColumn->width() . " col-span-full";
        if ($layoutRow["layout"]->rowBackgroundColor()->isNotEmpty()) {
          $rowBackgroundColorValue = $layoutRow["layout"]
            ->rowBackgroundColor()
            ->value();
          $contrastColorForLightMode =
            SITE_COLORS[$rowBackgroundColorValue]["contrastForLightMode"];
          $contrastColorForDarkMode =
            SITE_COLORS[$rowBackgroundColorValue]["contrastForDarkMode"];
          switch ($contrastColorForLightMode) {
            case COLOR_BLACK:
              $columnClassOutput .= " prose-black";
              break;
            case COLOR_WHITE:
              $columnClassOutput .= " prose-white";
              break;
          }
          switch ($contrastColorForDarkMode) {
            case COLOR_BLACK:
              $columnClassOutput .= " dark:prose-black";
              break;
            case COLOR_WHITE:
              $columnClassOutput .= " dark:prose-white";
              break;
          }
        } else {
          $columnClassOutput .= " prose-neutral dark:prose-invert";
        }
        ?>
        <div class="<?= $columnClassOutput ?> prose max-w-none">
          <?php foreach ($layoutColumn->blocks() as $block) {
            if (in_array($block->type(), ["image", "grid"])) {
              snippet("blocks/" . $block->type(), [
                "block" => $block,
                "layoutColumnWidth" => $layoutColumn->width(),
              ]);
            } else {
              echo $block;
            }
          } ?>
        </div>
      <?php endforeach; ?>
    </div>
  </section>
<?php endforeach; ?>
