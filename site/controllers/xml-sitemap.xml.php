<?php
/**
 * =============================================================================
 * Controller for XML Sitemap Template
 *
 * Provides variables for use in the template:
 * - $filteredPages (Kirby pages collection)
 * =============================================================================
 */

return function ($site) {
    $filteredPages = $site
        ->index()
        ->filter(function ($page) {
            // Exclude all unlisted pages, but include the unlisted homepage
            if (!$page->isListed() && !$page->isHomePage()) {
                return false;
            }

            // Exclude pages with template "custom-menu-item"
            $excludedTemplates = ["custom-menu-item", "xml-sitemap"];
            if (in_array($page->intendedTemplate(), $excludedTemplates)) {
                return false;
            }

            // Exclude pages with the "seoIndex" field set to "false"
            if ($page->seoIndex()->exists() && !$page->seoIndex()->toBool()) {
                return false;
            }

            return true;
        })
        ->sortBy("url", "asc");

    return ["filteredPages" => $filteredPages];
};
