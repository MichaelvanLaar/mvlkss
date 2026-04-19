# Fix Columns Block Bugs Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Fix six bugs found during PR review of the Grid-Blocks → Column-Blocks migration: broken 1/4 column grid math, variable shadowing, missing nested columns dispatch, uninitialised injected variables, unguarded brand-color array accesses, and missing responsive-image size cases.

**Architecture:** All fixes are confined to two PHP snippet files (`columns.php` and `image.php`). No new files are created. Changes are grouped so each commit leaves the snippet in a working state.

**Tech Stack:** Kirby CMS 5.x (PHP 8.1+), Tailwind CSS 4.x (grid-cols-12 system)

---

## Files

| Action | File                               |
| ------ | ---------------------------------- |
| Modify | `site/snippets/blocks/columns.php` |
| Modify | `site/snippets/blocks/image.php`   |

---

### Task 1: Fix grid column math — `grid-cols-6` → `grid-cols-12` and width map

The outer layout in `page-builder.php` already uses `grid-cols-12`. The columns block used `grid-cols-6`, making the `1/4` layout (which needs `col-span-3` of 12) mathematically impossible. All col-span values must be recalculated for 12 columns.

**Files:**

- Modify: `site/snippets/blocks/columns.php:15-21` (width map) and `:89` (grid class)

- [ ] **Step 1: Replace the `$columnWidthClasses` map and `grid-cols-6`**

    In `site/snippets/blocks/columns.php`, replace lines 15–21:

    ```php
    // Set column width classes for the layout options using Tailwind CSS classes
    $columnWidthClasses = [
        "1/1" => "col-span-full",
        "1/2" => "col-span-6",
        "1/3" => "col-span-4",
        "2/3" => "col-span-8",
        "1/4" => "col-span-3",
    ];
    ```

    Then on line 89, change `"grid-cols-6"` to `"grid-cols-12"`:

    ```php
    $columnRowClasses = [
      $columnLayoutRow->columnRowClasses(),
      "grid",
      "grid-cols-12",
      "print:block",
      ...
    ```

- [ ] **Step 2: Commit**

    ```bash
    git add site/snippets/blocks/columns.php
    git commit -m "🐛 fix(columns): switch to grid-cols-12 and fix col-span values"
    ```

---

### Task 2: Fix `$block` variable shadowing and add nested-columns dispatch

The inner `foreach` loop rebinds `$block` (the columns block itself) to each child block. Additionally, a nested `columns` block falls through to `echo $block` with no variables, causing silent PHP undefined-variable warnings and wrong CSS output.

**Files:**

- Modify: `site/snippets/blocks/columns.php:161-194`

- [ ] **Step 1: Replace the inner block-dispatch loop**

    Replace lines 161–194 in `site/snippets/blocks/columns.php` with:

    ```php
            <?php foreach ($columnLayoutColumn->blocks() as $innerBlock) {
                if ($innerBlock->type() == "image") {
                    snippet("blocks/" . $innerBlock->type(), [
                        "block" => $innerBlock,
                        "layoutColumnWidth" => $layoutColumnWidth ?? null,
                        "columnLayoutColumnWidth" => $columnLayoutColumn->width(),
                        "layoutColumnSplitting" => $layoutColumnSplitting,
                    ]);
                } elseif ($innerBlock->type() == "columns") {
                    snippet("blocks/" . $innerBlock->type(), [
                        "block" => $innerBlock,
                        "layoutColumnWidth" => $columnLayoutColumn->width(),
                        "layoutColumnSplitting" =>
                            $layoutColumnSplitting ?? null,
                        "layoutRowBackgroundColorExists" => $columnLayoutRow
                            ->columnRowBackgroundColor()
                            ->isNotEmpty(),
                        "layoutRowBackgroundColorValue" => $columnLayoutRow
                            ->columnRowBackgroundColor()
                            ->isNotEmpty()
                            ? $columnLayoutRow
                                ->columnRowBackgroundColor()
                                ->value()
                            : null,
                    ]);
                } elseif ($innerBlock->type() == "mvlkssbreadcrumb") {
                    $colorKey = null;
                    if (
                        $columnLayoutRow
                            ->columnRowBackgroundColor()
                            ->isNotEmpty()
                    ) {
                        $colorKey = $columnLayoutRow
                            ->columnRowBackgroundColor()
                            ->value();
                    } elseif ($layoutRowBackgroundColorExists) {
                        $colorKey = $layoutRowBackgroundColorValue;
                    }
                    $breadcrumbTextColorLight = $colorKey
                        ? $selectableBrandColors[$colorKey][
                                "light-contrast-tailwindcss-text-class"
                            ] ?? null
                        : null;
                    $breadcrumbTextColorDark = $colorKey
                        ? $selectableBrandColors[$colorKey][
                                "dark-contrast-tailwindcss-text-class"
                            ] ?? null
                        : null;
                    snippet("blocks/" . $innerBlock->type(), [
                        "block" => $innerBlock,
                        "textColorLight" => $breadcrumbTextColorLight,
                        "textColorDark" => $breadcrumbTextColorDark,
                    ]);
                } else {
                    echo $innerBlock;
                }
            } ?>
    ```

- [ ] **Step 2: Commit**

    ```bash
    git add site/snippets/blocks/columns.php
    git commit -m "🐛 fix(columns): rename inner \$block to \$innerBlock and add nested columns dispatch"
    ```

---

### Task 3: Initialize injected variables at top of file

`$layoutRowBackgroundColorExists`, `$layoutRowBackgroundColorValue`, `$layoutColumnSplitting`, and `$layoutColumnWidth` are injected by the caller but never declared inside the snippet. Any caller that omits them (including the recursive path fixed in Task 2) triggers PHP 8 undefined-variable warnings and silently uses `null`.

**Files:**

- Modify: `site/snippets/blocks/columns.php` — insert after line 27 (end of Configuration section)

- [ ] **Step 1: Add variable initialization block**

    After the closing `*/` of the Configuration doc-comment block (after line 27, before the `// Get the "selectable brand colors"` comment), insert:

    ```php
    // Variables injected by the caller via snippet():
    $layoutColumnWidth = $layoutColumnWidth ?? null;
    $layoutColumnSplitting = $layoutColumnSplitting ?? null;
    $layoutRowBackgroundColorExists = $layoutRowBackgroundColorExists ?? false;
    $layoutRowBackgroundColorValue = $layoutRowBackgroundColorValue ?? null;
    ```

- [ ] **Step 2: Commit**

    ```bash
    git add site/snippets/blocks/columns.php
    git commit -m "🐛 fix(columns): initialise injected variables at snippet top"
    ```

---

### Task 4: Guard all `$selectableBrandColors[$colorKey]` array accesses

Four locations access brand-color array entries by a key that comes from stored content. If the color is later removed from `config.php`, PHP 8 silently returns `null` and the wrong (or empty) CSS classes are emitted, with no diagnostic.

**Files:**

- Modify: `site/snippets/blocks/columns.php:72-83`, `:113-123`, `:124-134`

- [ ] **Step 1: Guard the row background-color class lookup (lines 72–83)**

    Replace the ternary that builds `$columnRowBackgroundColorClasses` with:

    ```php
    // Set the background color related CSS class for the current column row
    $columnRowBgColorValue = $columnLayoutRow
        ->columnRowBackgroundColor()
        ->isNotEmpty()
        ? $columnLayoutRow->columnRowBackgroundColor()->value()
        : null;
    if (
        $columnRowBgColorValue !== null &&
        isset($selectableBrandColors[$columnRowBgColorValue])
    ) {
        $colorEntry = $selectableBrandColors[$columnRowBgColorValue];
        $columnRowBackgroundColorClasses =
            $colorEntry["light-tailwindcss-bg-class"] .
            " " .
            $colorEntry["dark-tailwindcss-bg-class"] .
            " print:bg-transparent";
    } elseif ($columnRowBgColorValue !== null) {
        error_log(
            "columns.php: Unknown background color key \"{$columnRowBgColorValue}\"",
        );
        $columnRowBackgroundColorClasses = "";
    } else {
        $columnRowBackgroundColorClasses = "";
    }
    ```

- [ ] **Step 2: Guard the inner-container prose-class lookups (lines 113–134)**

    Replace the `if / elseif / else` block that sets `$columnInnerContainerClassOutput`:

    ```php
    if ($columnLayoutRow->columnRowBackgroundColor()->isNotEmpty()) {
        $bgKey = $columnLayoutRow->columnRowBackgroundColor()->value();
        if (isset($selectableBrandColors[$bgKey])) {
            $colorEntry = $selectableBrandColors[$bgKey];
            $columnInnerContainerClassOutput =
                " " .
                $colorEntry["light-contrast-tailwindcss-prose-class"] .
                " " .
                $colorEntry["dark-contrast-tailwindcss-prose-class"] .
                " print:prose-black";
        } else {
            error_log(
                "columns.php: Unknown prose color key \"{$bgKey}\" (column row bg)",
            );
            $columnInnerContainerClassOutput =
                " prose-mvlkss dark:prose-invert print:prose-black";
        }
    } elseif (
        $layoutRowBackgroundColorExists &&
        isset($selectableBrandColors[$layoutRowBackgroundColorValue])
    ) {
        $colorEntry = $selectableBrandColors[$layoutRowBackgroundColorValue];
        $columnInnerContainerClassOutput =
            " " .
            $colorEntry["light-contrast-tailwindcss-prose-class"] .
            " " .
            $colorEntry["dark-contrast-tailwindcss-prose-class"] .
            " print:prose-black";
    } else {
        $columnInnerContainerClassOutput =
            " prose-mvlkss dark:prose-invert print:prose-black";
    }
    ```

- [ ] **Step 3: Commit**

    ```bash
    git add site/snippets/blocks/columns.php
    git commit -m "🐛 fix(columns): guard brand-color array accesses and log unknown keys"
    ```

---

### Task 5: Fix `image.php` — add missing 1/4 and 2/3 size cases, remove dead reference

The `sizes` switch in `image.php` handles `1/2` and `1/3` nested column widths but not `1/4` or `2/3`, both of which are now exposed by the columns blueprint. Images in those layouts get oversized `sizes` values. Also remove the dead `$gridLayoutColumnWidth` fallback.

**Files:**

- Modify: `site/snippets/blocks/image.php:91-102`

- [ ] **Step 1: Replace the nested-column-width switch**

    Replace lines 91–102:

    ```php
    $nestedColumnWidth = $columnLayoutColumnWidth ?? null;
    if ($nestedColumnWidth) {
        switch ($nestedColumnWidth) {
            case "1/2":
                $maxWidth = ceil($maxWidth / 2);
                break;
            case "1/3":
                $maxWidth = ceil($maxWidth / 3);
                break;
            case "2/3":
                $maxWidth = ceil(($maxWidth * 2) / 3);
                break;
            case "1/4":
                $maxWidth = ceil($maxWidth / 4);
                break;
        }
    }
    ```

    Also update the comment at lines 83–85 to remove the now-stale "grid block" reference:

    ```php
    // Extract the correct set of values for the "sizes" attribute of "source" and
    // "img" elements, taking into account column width reduction when the image
    // is nested inside a columns block within the layout field.
    ```

- [ ] **Step 2: Commit**

    ```bash
    git add site/snippets/blocks/image.php
    git commit -m "🐛 fix(image): add 1/4 and 2/3 nested column size cases, remove dead grid reference"
    ```

---

### Task 6: Add error_log to spacing-class fallbacks in columns.php

The five spacing lookups (`gap`, `padding-top`, `padding-bottom`, `padding-start`, `padding-end`) silently fall back to `gap-0` / `pt-0` etc. when a stored value is not in the config. Adding a log makes stale-content issues diagnosable.

**Files:**

- Modify: `site/snippets/blocks/columns.php:41-69`

- [ ] **Step 1: Replace the five spacing lookups with logged fallbacks**

    Replace lines 41–69 with:

    ```php
    $spacingClasses = option("site-constants")["spacing-utility-classes"];

    // Set the gap related CSS class for the current column row
    $gapValue = (string) $columnLayoutRow->columnRowGap();
    $columnRowGapClass =
        $spacingClasses["gap"][$gapValue] ??
        (error_log("columns.php: Unknown gap value \"{$gapValue}\"") ?:
            "gap-0");

    // Set the top padding related CSS class for the current column row
    $paddingTopValue = (string) $columnLayoutRow->columnRowPaddingTop();
    $columnRowPaddingTopClass =
        $spacingClasses["padding-top"][$paddingTopValue] ??
        (error_log(
            "columns.php: Unknown padding-top value \"{$paddingTopValue}\"",
        ) ?:
            "pt-0");

    // Set the bottom padding related CSS class for the current column row
    $paddingBottomValue = (string) $columnLayoutRow->columnRowPaddingBottom();
    $columnRowPaddingBottomClass =
        $spacingClasses["padding-bottom"][$paddingBottomValue] ??
        (error_log(
            "columns.php: Unknown padding-bottom value \"{$paddingBottomValue}\"",
        ) ?:
            "pb-0");

    // Set the start padding related CSS class for the current column row
    $paddingStartValue = (string) $columnLayoutRow->columnRowPaddingStart();
    $columnRowPaddingStartClass =
        $spacingClasses["padding-start"][$paddingStartValue] ??
        (error_log(
            "columns.php: Unknown padding-start value \"{$paddingStartValue}\"",
        ) ?:
            "ps-0");

    // Set the end padding related CSS class for the current column row
    $paddingEndValue = (string) $columnLayoutRow->columnRowPaddingEnd();
    $columnRowPaddingEndClass =
        $spacingClasses["padding-end"][$paddingEndValue] ??
        (error_log(
            "columns.php: Unknown padding-end value \"{$paddingEndValue}\"",
        ) ?:
            "pe-0");
    ```

- [ ] **Step 2: Commit**

    ```bash
    git add site/snippets/blocks/columns.php
    git commit -m "🔧 chore(columns): log unknown spacing values before falling back"
    ```

---

### Task 7: Build assets and verify

- [ ] **Step 1: Run production build**

    ```bash
    npm run build
    ```

    Expected: exits 0, new hashed CSS files appear in `assets/css/`.

- [ ] **Step 2: Stage and commit built assets**

    ```bash
    git add assets/
    git commit -m "🔧 chore(build): rebuild assets after columns block fixes"
    ```
