<?php
/**
 * =============================================================================
 * Page Builder (default)
 * 
 * Layout field for main content of a page.
 * 
 * Uses “main-menu.controller.php” via the Kirby Snippet Controller plugin
 * Plugin details: https://github.com/lukaskleinschmidt/kirby-snippet-controller
 * 
 * Receives variables from snippet controller:
 * - ???
 * =============================================================================
 */
?>
      <?php foreach ($page->mainContent()->toLayouts() as $layout): ?>
        <!-- Row -->
        <?php switch ($layout->rowVerticalPadding()) {
          case "small":
            $rowVerticalPaddingOutput = "py-3";
            break;
          case "medium":
            $rowVerticalPaddingOutput = "py-6";
            break;
          case "large":
            $rowVerticalPaddingOutput = "py-12";
            break;
          default:
            $rowVerticalPaddingOutput = "py-0";
        } ?>
        <section
          <?= $layout->rowId()->isNotEmpty()
            ? " id=\"" . $layout->rowId() . "\""
            : "" ?>
          <?= $layout->rowClasses()->isNotEmpty()
            ? " class=\"" . $layout->rowClasses() . "\""
            : "" ?>
          class="<?= $rowVerticalPaddingOutput ?> <?= $layout->rowBackgroundColor() ?>"
        >
          <!--Inner row container -->
          <div class="<?= $layout->rowWidth() ?> grid grid-cols-12 gap-6">
          <?php foreach ($layout->columns() as $column): ?>
            <!-- Column -->
            <?php switch ($column->width()) {
              case "1/2":
                $columnClassOutput = "col-span-full md:col-span-6";
                break;
              case "1/3":
                $columnClassOutput =
                  "col-span-full md:col-span-6 lg:col-span-4";
                break;
              case "2/3":
                $columnClassOutput =
                  "col-span-full md:col-span-6 lg:col-span-8";
                break;
              case "1/4":
                $columnClassOutput =
                  "col-span-full md:col-span-6 lg:col-span-3";
                break;
              default:
                $columnClassOutput = "col-span-full";
            } ?>
            <div class="<?= $columnClassOutput ?> bg-neutral-500">
              <?= $column->blocks() ?>
            </div>
          <?php endforeach; ?>
          </div>
        </section>
      <?php endforeach; ?>
