<?php
/**
 * =============================================================================
 * Page Builder Snippet
 * 
 * Layout field for main content of a page.
 * 
 * Uses “main-menu.controller.php” via the Kirby Snippet Controller plugin
 * Plugin details: https://github.com/lukaskleinschmidt/kirby-snippet-controller
 * 
 * Receives variables from snippet controller:
 * - $layoutRowsData
 * =============================================================================
 */
?>
<?php foreach ($layoutRowsData as $layoutRow): ?>
  <section <?= $layoutRow["layoutRowId"] ?> <?= $layoutRow[
   "layoutRowClass"
 ] ?> <?= $layoutRow["layoutRowStyle"] ?>>
    <!--Inner row container -->
    <div class="<?= $layoutRow[
      "layout"
    ]->rowWidth() ?> grid grid-cols-12 gap-medium md:[&_>_.column-width-1\/3:nth-child(3)]:col-start-4 lg:[&_>_.column-width-1\/3:nth-child(3)]:col-start-auto [.column-splitting-1\/4-1\/2-1\/4_>_&_>_.column-width-1\/4.empty-column]:hidden lg:[.column-splitting-1\/4-1\/2-1\/4_>_&_>_.column-width-1\/4.empty-column]:block md:[.column-splitting-1\/4-1\/2-1\/4_>_&_>_.column-width-1\/4]:col-start-4 lg:[.column-splitting-1\/4-1\/2-1\/4_>_&_>_.column-width-1\/4]:col-start-auto md:[:is(.column-splitting-1\/4-1\/2-1\/4,_.column-splitting-1\/4-1\/4-1\/2,_.column-splitting-1\/2-1\/4-1\/4)_>_&_>_.column-width-1\/2]:col-span-full lg:[:is(.column-splitting-1\/4-1\/2-1\/4,_.column-splitting-1\/4-1\/4-1\/2,_.column-splitting-1\/2-1\/4-1\/4)_>_&_>_.column-width-1\/2]:col-span-6">
      <?php $columnWidthClasses = [
        "1/2" => "column-width-1/2 col-span-full md:col-span-6",
        "1/3" => "column-width-1/3 col-span-full md:col-span-6 lg:col-span-4",
        "2/3" => "column-width-2/3 col-span-full md:col-span-6 lg:col-span-8",
        "1/4" => "column-width-1/4 col-span-full md:col-span-6 lg:col-span-3",
      ]; ?>
      <?php foreach ($layoutRow["layout"]->columns() as $layoutColumn): ?>
        <!-- Column -->
        <?php
        $columnClassOutput = $layoutColumn->blocks()->isEmpty()
          ? "empty-column "
          : "";
        $columnClassOutput .=
          $columnWidthClasses[$layoutColumn->width()] ??
          "column-width-" . $layoutColumn->width() . " col-span-full";
        ?>
        <div class="<?= $columnClassOutput ?>">
          <?= $layoutColumn->blocks() ?>
        </div>
      <?php endforeach; ?>
    </div>
  </section>
<?php endforeach; ?>
