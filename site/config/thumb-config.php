<?php
/**
 * =============================================================================
 * Thumbnail Srcsets and Presets
 * =============================================================================
 */

/**
 * Retrieves the thumbnail configuration based on the specified environment.
 *
 * @param string $env The environment (default: "production")
 * @return array The thumbnail configuration
 */
function getThumbConfig($env = "production") {
    // CONFIGURATION: Set the widths of the thumbnails
    $thumbWidths = [200, 400, 600, 800, 1000, 1600, 2000];

    // CONFIGURATION: Define the sets of srcsets with their respective settings
    //
    // Keep an eye on the order of the sets. The same order will be used for the
    // source elements in the HTML. Browsers will use the first source element
    // that they support. I.e. if you want to prioritize AVIF over WebP, the
    // AVIF configuration for a specific input type must come before the WebP
    // configuration for the same input type.
    //
    // For each of the sets, a preset will be created in addition to the srcset.
    // The preset will not inllude any width information. It will only include
    // the format and quality information.
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

    // CONFIGURATION: Define different sets of srcsets with their respective
    // settings for the localhost environment
    //
    // This is useful if you use GD as the driver for the thumbs in the
    // localhost because GD cannot handle AVIF images properly.
    if ($env === "localhost") {
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
                "name" => "jpg->webp",
                "input" => "jpg",
                "format" => "webp",
                "quality" => 70,
            ],
        ];
    }

    // Construct the srcsets and presets from the configuration
    $thumbSrcsets = getThumbSrcsets($thumbSrcsetsConfig, $thumbWidths);
    $thumbSrcsetsSelector = getThumbSrcsetsSelector($thumbSrcsetsConfig);
    $thumbPresets = getThumbPresets($thumbSrcsetsConfig);

    // Return the configuration
    return [
        "thumbWidths" => $thumbWidths,
        "thumbSrcsets" => $thumbSrcsets,
        "thumbSrcsetsSelector" => $thumbSrcsetsSelector,
        "thumbPresets" => $thumbPresets,
    ];
}

/**
 * Generates an array of thumbnail source sets based on the provided
 * configuration and widths.
 *
 * @param array $thumbSrcsetsConfig The configuration for the thumbnail source sets.
 * @param array $thumbWidths The array of thumbnail widths.
 * @return array The generated thumbnail source sets.
 */
function getThumbSrcsets($thumbSrcsetsConfig, $thumbWidths) {
    $thumbSrcsets = [];
    foreach ($thumbSrcsetsConfig as $thumbSrcset) {
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
    }
    return $thumbSrcsets;
}

/**
 * Generates a selector array for thumbnail source sets based on the given
 * configuration.
 *
 * @param array $thumbSrcsetsConfig The configuration array for thumbnail source sets.
 * @return array The selector array for thumbnail source sets.
 */
function getThumbSrcsetsSelector($thumbSrcsetsConfig) {
    $thumbSrcsetsSelector = [];
    foreach ($thumbSrcsetsConfig as $thumbSrcset) {
        if (!$thumbSrcset["input"]) {
            continue;
        }
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
    return $thumbSrcsetsSelector;
}

/**
 * Retrieves the thumb presets from the thumb srcsets configuration.
 *
 * @param array $thumbSrcsetsConfig The thumb srcsets configuration.
 * @return array The thumb presets.
 */
function getThumbPresets($thumbSrcsetsConfig) {
    $thumbPresets = [];
    foreach ($thumbSrcsetsConfig as $thumbSrcset) {
        $thumbPresets[$thumbSrcset["name"]] = [
            "format" => $thumbSrcset["format"] ?? null,
            "quality" => $thumbSrcset["quality"] ?? null,
        ];
    }
    return $thumbPresets;
}
