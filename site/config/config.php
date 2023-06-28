<?php

/**
 * =============================================================================
 * General Kirby Configuration
 * =============================================================================
 */

/**
 * -----------------------------------------------------------------------------
 * Thumbnail Srcsets
 * -----------------------------------------------------------------------------
 */

// CONFIGURATION: Set the widths of the thumbnails
$thumbWidths = [200, 400, 600, 800, 1000, 1600, 2000];

// CONFIGURATION: Define the sets of srcsets with their respective settings
//
// Keep an eye on the order of the sets. The same order will be used for the
// source elements in the HTML. Browsers will use the first source element that
// they support. I.e. if you want to prioritize AVIF over WebP, the AVIF
// configuration for a specific input type must come before the WebP
// configuration for the same input type.
$thumbSrcsetsConfig = [
    [
        "name" => "default",
        "input" => null,
        "format" => null,
        "quality" => 90,
    ],
    [
        "name" => "png->webp",
        "input" => "png",
        "format" => "webp",
        "quality" => 100,
    ],
    [
        "name" => "jpg->avif",
        "input" => "jpg",
        "format" => "avif",
        "quality" => 60,
    ],
    [
        "name" => "jpg->webp",
        "input" => "jpg",
        "format" => "webp",
        "quality" => 70,
    ],
];

$thumbSrcsets = [];
$thumbSrcsetsSelector = [];
foreach ($thumbSrcsetsConfig as $thumbSrcset) {
    // Fill $thumbSrcsets
    $thumbSrcsets[$thumbSrcset["name"]] = [];
    foreach ($thumbWidths as $thumbWidth) {
        $thumbSrcsets[$thumbSrcset["name"]][(string) $thumbWidth . "w"] = [
            "width" => $thumbWidth,
            "format" => $thumbSrcset["format"] ?? null,
            "quality" => $thumbSrcset["quality"] ?? null,
            "type-attribute-for-source-element" => $thumbSrcset["format"]
                ? "image/" . $thumbSrcset["format"]
                : null,
        ];
    }

    // Fill $thumbSrcsetsSelector

    // Ignore default
    if (!$thumbSrcset["input"]) {
        continue;
    }
    // Handle both "jpg" and "jpeg"
    $inputFileExtensions = in_array($thumbSrcset["input"], ["jpg", "jpeg"])
        ? ["jpg", "jpeg"]
        : [$thumbSrcset["input"]];
    foreach ($inputFileExtensions as $inputFileExtension) {
        if (!isset($thumbSrcsetsSelector[$inputFileExtension])) {
            $thumbSrcsetsSelector[$inputFileExtension] = [];
        }
        $thumbSrcsetsSelector[$inputFileExtension][] = $thumbSrcset["name"];
    }
}

/**
 * -----------------------------------------------------------------------------
 * Return Configuration
 * -----------------------------------------------------------------------------
 */

return [
    "afbora.kirby-minify-html.enabled" => true,
    "cache" => [
        "pages" => [
            "active" => true,
        ],
    ],
    "debug" => false,
    "distantnative.retour.logs" => false,
    "languages" => true,
    "lukaskleinschmidt.resolve.cache" => true,
    "ready" => function ($kirby) use (
        $thumbWidths,
        $thumbSrcsets,
        $thumbSrcsetsSelector
    ) {
        return [
            "bnomei.robots-txt.content" =>
                "# https://www.robotstxt.org/\n\n# Allow crawling of all content\nUser-agent: *\nDisallow:\n\nSitemap: " .
                $kirby->site()->url() .
                "/sitemap.xml",
            "bnomei.robots-txt.groups" => null,
            "bnomei.robots-txt.sitemap" => null,
            "isaactopo.xmlsitemap.ignore" => $kirby
                ->site()
                ->index()
                ->filterBy("seoIndex", "false")
                ->pluck("uri"),
            "isaactopo.xmlsitemap.includeImages" => true,
            "site-constants" => [
                "thumb-widths" => $thumbWidths,
                "thumb-srcsets" => $thumbSrcsets,
                "thumb-srcsets-selector" => $thumbSrcsetsSelector,
            ],
        ];
    },
    "thumbs" => [
        "driver" => "im",
        "srcsets" => $thumbSrcsets,
    ],
];
