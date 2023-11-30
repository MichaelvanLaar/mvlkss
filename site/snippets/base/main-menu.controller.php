<?php
/**
 * =============================================================================
 * Controller for Main Menu Snippet (which is used on all pages)
 *
 * Uses the Kirby Snippet Controller plugin
 * Plugin details: https://github.com/lukaskleinschmidt/kirby-snippet-controller
 *
 * Provides variables for use in the header snippet:
 * - $mainMenuItems
 * =============================================================================
 */

return function ($site, $page) {
  // Construct array with content for main menu
  $mainMenuItems = [];
  foreach ($site->children()->listed() as $menuItem) {
    $menuOptions = $menuItem->includeInMenus()->split(",");
    if (in_array("main", $menuOptions)) {
      $url =
        $menuItem->intendedTemplate() == "custom-menu-item"
          ? $menuItem->menuItemLink()->toUrl()
          : $menuItem->url();
      $mainMenuItems[] = [
        "title" => $menuItem->title(),
        "url" => $url,
        "target" => $menuItem->menuItemTarget()->exists()
          ? $menuItem->menuItemTarget()
          : "_self",
        "isActive" =>
          $page->is($menuItem) || $page->parents()->has($menuItem)
            ? "active"
            : "",
      ];
    }
  }

  return [
    "mainMenuItems" => $mainMenuItems,
  ];
};
