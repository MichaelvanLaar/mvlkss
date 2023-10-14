<?php
/**
 * =============================================================================
 * Options for select field “brand color”
 * =============================================================================
 */

Kirby::plugin("mvlkss/brand-color-select-options", [
    "pageMethods" => [
        "brandColorOptions" => function () {
            $colorConfig = kirby()->option("site-constants")[
                "selectable-brand-colors"
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
