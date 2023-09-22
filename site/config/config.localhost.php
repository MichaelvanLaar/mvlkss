<?php
/**
 * =============================================================================
 * Additional Kirby Configuration for localhost
 *
 * Overwrites the respective settings from config.php
 * =============================================================================
 */

return [
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
