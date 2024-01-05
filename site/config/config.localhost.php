<?php
/**
 * =============================================================================
 * Additional Kirby Configuration for localhost
 *
 * Overwrites the respective settings from config.php
 * =============================================================================
 */

/**
 * -----------------------------------------------------------------------------
 * Thumbnail Srcsets and Presets
 *
 * The configuration for thumbnail creation can be found in the file
 * `site/config/thumb-config.php`.
 * -----------------------------------------------------------------------------
 */

require_once "site/config/thumb-config.php";

$thumbConfig = getThumbConfig("gd");

/**
 * -----------------------------------------------------------------------------
 * Return Configuration
 * -----------------------------------------------------------------------------
 */

return [
    "afbora.kirby-minify-html.enabled" => false,
    "cache" => [
        "pages" => [
            "active" => false,
        ],
    ],
    "debug" => true,
    "site-constants" => [
        "thumb-widths" => $thumbConfig["thumbWidths"],
        "thumb-srcsets" => $thumbConfig["thumbSrcsets"],
        "thumb-srcsets-selector" => $thumbConfig["thumbSrcsetsSelector"],
    ],
    "thumbs" => [
        "driver" => "gd",
        "srcsets" => $thumbConfig["thumbSrcsets"],
        "presets" => $thumbConfig["thumbPresets"],
    ],
];
