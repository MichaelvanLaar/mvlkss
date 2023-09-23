<?php
/**
 * =============================================================================
 * Additional Kirby Configuration for localhost
 *
 * Overwrites the respective settings from config.php
 * =============================================================================
 */

return [
    "afbora.kirby-minify-html.enabled" => false,
    "cache" => [
        "pages" => [
            "active" => false,
        ],
    ],
    "debug" => true,
    "thumbs" => [
        "driver" => "gd",
    ],
];
