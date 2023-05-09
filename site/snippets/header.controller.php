<?php
/**
 * Controller for Header snippet (which is used on all pages)
 * 
 * Uses the Kirby Snippet Controller plugin
 * Plugin details: https://github.com/lukaskleinschmidt/kirby-snippet-controller
 */

return function ($kirby) {
  return [
    "pageLanguageCode" => $kirby->language()
      ? $kirby->language()->code()
      : "en",
  ];
};
