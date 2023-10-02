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

// Get the “selectable background colors” array from the site constants
$selectableBackgroundColors = option("site-constants")[
  "selectable-background-colors"
];

// Classes required for the responsive design of the multi-column layout
$innerRowContainerClasses =
  "grid grid-cols-12 gap-medium lg:gap-large md:[&_>_.column-width-1\/3:nth-child(3)]:col-start-4 lg:[&_>_.column-width-1\/3:nth-child(3)]:col-start-auto [.column-splitting-1\/4-1\/2-1\/4_>_&_>_.column-width-1\/4.empty-column]:hidden lg:[.column-splitting-1\/4-1\/2-1\/4_>_&_>_.column-width-1\/4.empty-column]:block md:[.column-splitting-1\/4-1\/2-1\/4_>_&_>_.column-width-1\/4]:col-start-4 lg:[.column-splitting-1\/4-1\/2-1\/4_>_&_>_.column-width-1\/4]:col-start-auto md:[:is(.column-splitting-1\/4-1\/2-1\/4,_.column-splitting-1\/4-1\/4-1\/2,_.column-splitting-1\/2-1\/4-1\/4)_>_&_>_.column-width-1\/2]:col-span-full lg:[:is(.column-splitting-1\/4-1\/2-1\/4,_.column-splitting-1\/4-1\/4-1\/2,_.column-splitting-1\/2-1\/4-1\/4)_>_&_>_.column-width-1\/2]:col-span-6";
?>
<?php foreach ($layoutRowsData as $layoutRow): ?>
  <!-- Row -->
  <section
    data-page-builder-element-type="layout-row"
    <?= $layoutRow["layoutRowIdAttribute"] ?>
    <?= $layoutRow["layoutRowClassAttribute"] ?>
    <?= $layoutRow["layoutRowStyleAttribute"] ?>
  >
    <!-- Inner row container -->
    <div data-page-builder-element-type="layout-inner-row-container" class="<?= $layoutRow[
      "layout"
    ]->rowWidth() ?> <?= $innerRowContainerClasses ?>">
      <?php foreach ($layoutRow["layout"]->columns() as $layoutColumn): ?>
        <!-- Column -->
        <?php
        $columnClassOutput = $layoutColumn->blocks()->isEmpty()
          ? "empty-column "
          : "";
        $columnClassOutput .=
          $columnWidthClasses[$layoutColumn->width()] ??
          "column-width-" . $layoutColumn->width() . " col-span-full";
        if ($layoutRow["layoutRowBackgroundColorExists"]) {
          $columnInnerContainerClassOutput =
            " " .
            $selectableBackgroundColors[
              $layoutRow["layoutRowBackgroundColorValue"]
            ]["light-contrast-tailwindcss-prose-class"] .
            " " .
            $selectableBackgroundColors[
              $layoutRow["layoutRowBackgroundColorValue"]
            ]["dark-contrast-tailwindcss-prose-class"];
        } else {
          $columnInnerContainerClassOutput = " prose-mvlkss dark:prose-invert";
        }
        if ($layoutRow["layout"]->rowVerticalAlign()->isNotEmpty()) {
          switch ($layoutRow["layout"]->rowVerticalAlign()->value()) {
            case "top":
              $columnClassOutput .= " flex flex-col justify-start";
              break;
            case "middle":
              $columnClassOutput .= " flex flex-col justify-center";
              break;
            case "bottom":
              $columnClassOutput .= " flex flex-col justify-end";
              break;
          }
        } else {
          $columnClassOutput .= " flex flex-col justify-start";
        }
        if ($layoutColumn->blocks()->isNotEmpty()) {
          $firstBlock = $layoutColumn->blocks()->first();
          $columnInnerContainerClassOutput .= $firstBlock
            ->sticky()
            ->toBool(false)
            ? " sticky top-[var(--site-header-height)]"
            : "";
        }
        ?>
        <div
          data-page-builder-element-type="layout-column"
          class="<?= $columnClassOutput ?>"
        >
          <?php if ($layoutColumn->blocks()->isNotEmpty()) {
            echo "<!-- Inner column container -->\n<div data-page-builder-element-type=\"layout-inner-column-container\" class=\"max-w-none prose" .
              $columnInnerContainerClassOutput .
              "\">";
          } ?>
            <?php foreach ($layoutColumn->blocks() as $block) {
              if (in_array($block->type(), ["image", "grid"])) {
                snippet("blocks/" . $block->type(), [
                  "block" => $block,
                  "layoutColumnWidth" => $layoutColumn->width(),
                  "layoutColumnSplitting" =>
                    $layoutRow["layoutColumnSplitting"],
                ]);
              } else {
                echo $block;
              }
            } ?>
          <?= $layoutColumn->blocks()->isNotEmpty() ? "</div>" : "" ?>
        </div>
      <?php endforeach; ?>
    </div>
  </section>
<?php endforeach; ?>
