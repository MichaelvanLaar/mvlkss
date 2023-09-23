<?php
/**
 * =============================================================================
 * Controller for Footer Snippet (which is used on all pages)
 *
 * Uses the Kirby Snippet Controller plugin
 * Plugin details: https://github.com/lukaskleinschmidt/kirby-snippet-controller
 *
 * Provides variables for use in the header snippet:
 * - $footerMenuItems
 * =============================================================================
 */

return function ($site) {
  // Construct array with content for main menu
  $footerMenuItems = [];
  foreach ($site->children()->listed() as $menuItem) {
    $menuOptions = $menuItem->includeInMenus()->split(",");
    if (in_array("footer", $menuOptions)) {
      $url =
        $menuItem->intendedTemplate() == "custom-menu-item"
          ? $menuItem->menuItemUrl()
          : $menuItem->url();
      $footerMenuItems[] = [
        "title" => $menuItem->title(),
        "url" => $url,
        "target" => $menuItem->menuItemTarget()->exists()
          ? $menuItem->menuItemTarget()
          : "_self",
      ];
    }
  }

  return [
    "footerMenuItems" => $footerMenuItems,
  ];
};
