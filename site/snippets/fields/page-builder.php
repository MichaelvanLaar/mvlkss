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
 * Optional variables from the snippet call:
 * - $field (in case the field name is not "pageBuilder")
 *
 * Receives variables from snippet controller:
 * - $selectableBrandColors
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
$innerRowContainerClasses =
  "grid grid-cols-12 gap-medium lg:gap-large md:[&_>_.column-width-1\/3:nth-child(3)]:col-start-4 lg:[&_>_.column-width-1\/3:nth-child(3)]:col-start-auto [.column-splitting-1\/4-1\/2-1\/4_>_&_>_.column-width-1\/4.empty-column]:hidden lg:[.column-splitting-1\/4-1\/2-1\/4_>_&_>_.column-width-1\/4.empty-column]:block md:[.column-splitting-1\/4-1\/2-1\/4_>_&_>_.column-width-1\/4]:col-start-4 lg:[.column-splitting-1\/4-1\/2-1\/4_>_&_>_.column-width-1\/4]:col-start-auto md:[:is(.column-splitting-1\/4-1\/2-1\/4,_.column-splitting-1\/4-1\/4-1\/2,_.column-splitting-1\/2-1\/4-1\/4)_>_&_>_.column-width-1\/2]:col-span-full lg:[:is(.column-splitting-1\/4-1\/2-1\/4,_.column-splitting-1\/4-1\/4-1\/2,_.column-splitting-1\/2-1\/4-1\/4)_>_&_>_.column-width-1\/2]:col-span-6 print:block";
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
            $selectableBrandColors[$layoutRow["layoutRowBackgroundColorValue"]][
              "light-contrast-tailwindcss-prose-class"
            ] .
            " " .
            $selectableBrandColors[$layoutRow["layoutRowBackgroundColorValue"]][
              "dark-contrast-tailwindcss-prose-class"
            ] .
            " print:prose-black";
        } else {
          $columnInnerContainerClassOutput =
            " prose-mvlkss dark:prose-invert print:prose-black";
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
              if ($block->type() == "image") {
                snippet("blocks/" . $block->type(), [
                  "block" => $block,
                  "layoutColumnWidth" => $layoutColumn->width(),
                  "layoutColumnSplitting" =>
                    $layoutRow["layoutColumnSplitting"],
                ]);
              } elseif ($block->type() == "mvlkssbreadcrumb") {
                $breadcrumbTextColorLight = $layoutRow[
                  "layoutRowBackgroundColorExists"
                ]
                  ? $selectableBrandColors[
                    $layoutRow["layoutRowBackgroundColorValue"]
                  ]["light-contrast-tailwindcss-text-class"]
                  : null;
                $breadcrumbTextColorDark = $layoutRow[
                  "layoutRowBackgroundColorExists"
                ]
                  ? $selectableBrandColors[
                    $layoutRow["layoutRowBackgroundColorValue"]
                  ]["dark-contrast-tailwindcss-text-class"]
                  : null;
                snippet("blocks/" . $block->type(), [
                  "block" => $block,
                  "textColorLight" => $breadcrumbTextColorLight,
                  "textColorDark" => $breadcrumbTextColorDark,
                ]);
              } elseif ($block->type() == "grid") {
                snippet("blocks/" . $block->type(), [
                  "block" => $block,
                  "layoutColumnWidth" => $layoutColumn->width(),
                  "layoutColumnSplitting" =>
                    $layoutRow["layoutColumnSplitting"],
                  "layoutRowBackgroundColorExists" =>
                    $layoutRow["layoutRowBackgroundColorExists"],
                  "layoutRowBackgroundColorValue" => $layoutRow[
                    "layoutRowBackgroundColorExists"
                  ]
                    ? $layoutRow["layoutRowBackgroundColorValue"]
                    : null,
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
