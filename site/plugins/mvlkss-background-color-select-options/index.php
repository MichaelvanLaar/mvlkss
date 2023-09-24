<?php
/**
 * =============================================================================
 * Options for select field “background color”
 * =============================================================================
 */

Kirby::plugin("mvlkss/background-color-select-options", [
    "pageMethods" => [
        "backgroundColorOptions" => function () {
            $colorConfig = kirby()->option("site-constants")[
                "selectable-background-colors"
            ];
            $options = [];
            foreach ($colorConfig as $key => $colorInfo) {
                $options[] = [
                    "text" => $colorInfo["label"],
                    "value" => $key,
                ];
            }
            return $options;
        },
    ],
]);
