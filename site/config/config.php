<?php

/**
 * =============================================================================
 * General Kirby Configuration
 * =============================================================================
 */

/**
 * -----------------------------------------------------------------------------
 * CONFIGURATION: Selectable Brand Colors
 *
 * These constants assign Tailwind CSS utility classes to the respective options
 * which are used in panel fields whenever a website editor should be able to
 * choose a color, e.g. as a background color of a block, button, etc.
 *
 * Colors which are not part of the default Tailwind CSS color palette can be
 * added by defining a custom color in the Tailwind CSS configuration file
 * (tailwind.config.js) and then using the respective utility class here.
 * See https://tailwindcss.com/docs/customizing-colors#adding-additional-colors
 * for more information.
 *
 * The keys of the array are the option values of the panel field. The values of
 * the array are arrays with the following keys:
 *
 * - label:
 *   Label text for select fields in the panel
 *
 * - light-tailwindcss-bg-class:
 *   Tailwind CSS utility class for the light mode brand color
 *   (must start with “bg-”)
 *
 * - light-tailwindcss-border-class:
 *   Tailwind CSS utility class for the light mode border color which is used
 *   when rendering buttons (typically in the same color as the background)
 *   (must start with “border-”)
 *
 * - light-tailwindcss-text-class:
 *   The Tailwind CSS utility class for rendering text in the light mode
 *   brand color, which is used to style the text of outline buttons (typically
 *   in the same color as the background)
 *   (must start with “text-”)
 *
 * - light-contrast-tailwindcss-prose-class:
 *   Tailwind CSS utility class of the Typography plugin color scheme used for
 *   rendering text in a contrasting color on top of this backgorund in light
 *   mode
 *   (must start with “prose-”)
 *
 * - light-contrast-tailwindcss-text-class:
 *   Tailwind CSS utility class for rendering text in a contrasting color on top
 *   of this backgorund in light mode
 *   (must start with “text-”)
 *
 * - dark-tailwindcss-bg-class:
 *   Tailwind CSS utility class for the dark mode brand color
 *   (must start with “dark:bg-”)
 *
 * - dark-tailwindcss-border-class:
 *   Tailwind CSS utility class for the dark mode border color which is used
 *   when rendering buttons (typically in the same color as the background)
 *   (must start with “dark:border-”)
 *
 * - dark-tailwindcss-text-class:
 *   The Tailwind CSS utility class for rendering text in the dark mode
 *   brand color, which is used to style the text of outline buttons (typically
 *   in the same color as the background)
 *   (must start with “dark:text-”)
 *
 * - dark-contrast-tailwindcss-prose-class:
 *   Tailwind CSS utility class of the Typography plugin color scheme used for
 *   rendering text in a contrasting color on top of this backgorund in dark
 *   mode
 *   (must start with “dark:prose-”)
 *
 * - dark-contrast-tailwindcss-text-class:
 *   Tailwind CSS utility class for rendering text in a contrasting color on top
 *   of this backgorund in dark mode
 *   (must start with “dark:text-”)
 * -----------------------------------------------------------------------------
 */

$selectableBrandColors = [
    "brand-red" => [
        "label" => "Brand Red",
        "light-tailwindcss-bg-class" => "bg-red-300",
        "light-tailwindcss-border-class" => "border-red-300",
        "light-tailwindcss-text-class" => "text-red-300",
        "light-contrast-tailwindcss-prose-class" => "prose-black",
        "light-contrast-tailwindcss-text-class" => "text-black",
        "dark-tailwindcss-bg-class" => "dark:bg-red-700",
        "dark-tailwindcss-border-class" => "dark:border-red-700",
        "dark-tailwindcss-text-class" => "dark:text-red-700",
        "dark-contrast-tailwindcss-prose-class" => "dark:prose-white",
        "dark-contrast-tailwindcss-text-class" => "dark:text-white",
    ],
    "brand-green" => [
        "label" => "Brand Green",
        "light-tailwindcss-bg-class" => "bg-green-300",
        "light-tailwindcss-border-class" => "border-green-300",
        "light-tailwindcss-text-class" => "text-green-300",
        "light-contrast-tailwindcss-prose-class" => "prose-black",
        "light-contrast-tailwindcss-text-class" => "text-black",
        "dark-tailwindcss-bg-class" => "dark:bg-green-700",
        "dark-tailwindcss-border-class" => "dark:border-green-700",
        "dark-tailwindcss-text-class" => "dark:text-green-700",
        "dark-contrast-tailwindcss-prose-class" => "dark:prose-white",
        "dark-contrast-tailwindcss-text-class" => "dark:text-white",
    ],
    "brand-blue" => [
        "label" => "Brand Blue",
        "light-tailwindcss-bg-class" => "bg-blue-300",
        "light-tailwindcss-border-class" => "border-blue-300",
        "light-tailwindcss-text-class" => "text-blue-300",
        "light-contrast-tailwindcss-prose-class" => "prose-black",
        "light-contrast-tailwindcss-text-class" => "text-black",
        "dark-tailwindcss-bg-class" => "dark:bg-blue-700",
        "dark-tailwindcss-border-class" => "dark:border-blue-700",
        "dark-tailwindcss-text-class" => "dark:text-blue-700",
        "dark-contrast-tailwindcss-prose-class" => "dark:prose-white",
        "dark-contrast-tailwindcss-text-class" => "dark:text-white",
    ],
];

/**
 * -----------------------------------------------------------------------------
 * CONFIGURATION: Spacing Utility Classes
 *
 * These constants assign Tailwind CSS utility classes to the respective options
 * which are used in panel fields whenever a website editor should be able to
 * choose how big a spacing (e.g. margin, padding, gap) should be.
 *
 * There are always the same five options available in the panel fields:
 * - none
 * - small
 * - medium
 * - large
 * - xlarge
 * -----------------------------------------------------------------------------
 */

$spacingUtilityClasses = [
    "margin-top" => [
        "none" => "mt-0",
        "small" => "mt-small",
        "medium" => "mt-medium",
        "large" => "mt-large",
        "xlarge" => "mt-xlarge",
    ],
    "margin-bottom" => [
        "none" => "mb-0",
        "small" => "mb-small",
        "medium" => "mb-medium",
        "large" => "mb-large",
        "xlarge" => "mb-xlarge",
    ],
    "margin-start" => [
        "none" => "ms-0",
        "small" => "ms-small",
        "medium" => "ms-medium",
        "large" => "ms-large",
        "xlarge" => "ms-xlarge",
    ],
    "margin-end" => [
        "none" => "me-0",
        "small" => "me-small",
        "medium" => "me-medium",
        "large" => "me-large",
        "xlarge" => "me-xlarge",
    ],
    "padding-top" => [
        "none" => "pt-0",
        "small" => "pt-small",
        "medium" => "pt-medium",
        "large" => "pt-large",
        "xlarge" => "pt-xlarge",
    ],
    "padding-bottom" => [
        "none" => "pb-0",
        "small" => "pb-small",
        "medium" => "pb-medium",
        "large" => "pb-large",
        "xlarge" => "pb-xlarge",
    ],
    "padding-start" => [
        "none" => "ps-0",
        "small" => "ps-small",
        "medium" => "ps-medium",
        "large" => "ps-large",
        "xlarge" => "ps-xlarge",
    ],
    "padding-end" => [
        "none" => "pe-0",
        "small" => "pe-small",
        "medium" => "pe-medium",
        "large" => "pe-large",
        "xlarge" => "pe-xlarge",
    ],
    "gap" => [
        "none" => "gap-0",
        "small" => "gap-small",
        "medium" => "gap-medium",
        "large" => "gap-large",
        "xlarge" => "gap-xlarge",
    ],
];

/**
 * -----------------------------------------------------------------------------
 * Thumbnail Srcsets and Presets
 *
 * The configuration for thumbnail creation can be found in the file
 * `site/config/thumb-config.php`.
 * -----------------------------------------------------------------------------
 */

require_once "site/config/thumb-config.php";

$thumbConfig = getThumbConfig();

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
            "type" => "apcu",
        ],
    ],
    "debug" => false,
    "distantnative.retour.logs" => false,
    "editor" => "vscode",
    "languages" => true,
    "markdown" => [
        "extra" => true,
    ],
    "ready" => function ($kirby) {
        // Fetch XML sitemap URLs for robots.txt file
        $sitemapPages = $kirby
            ->site()
            ->index()
            ->filterBy("template", "xml-sitemap");
        $sitemapEntriesForRobotsTxt = [];
        foreach ($sitemapPages as $page) {
            foreach ($kirby->languages() as $language) {
                $sitemapEntriesForRobotsTxt[] =
                    "Sitemap: " . $page->url($language->code()) . ".xml";
            }
        }
        $sitemapContentForRobotsTxt = implode(
            "\n",
            $sitemapEntriesForRobotsTxt
        );

        return [
            "bnomei.robots-txt.content" =>
                "# https://www.robotstxt.org/\n\n# Allow crawling of all content\nUser-agent: *\nDisallow:\n\n" .
                $sitemapContentForRobotsTxt,
            "bnomei.robots-txt.groups" => null,
            "bnomei.robots-txt.sitemap" => null,
        ];
    },
    "site-constants" => [
        "thumb-widths" => $thumbConfig["thumbWidths"],
        "thumb-srcsets" => $thumbConfig["thumbSrcsets"],
        "thumb-srcsets-selector" => $thumbConfig["thumbSrcsetsSelector"],
        "selectable-brand-colors" => $selectableBrandColors,
        "spacing-utility-classes" => $spacingUtilityClasses,
    ],
    "thumbs" => [
        "driver" => "im",
        "srcsets" => $thumbConfig["thumbSrcsets"],
        "presets" => $thumbConfig["thumbPresets"],
    ],
];
