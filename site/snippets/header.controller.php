<?php
/**
 * =============================================================================
 * Controller for Header snippet (which is used on all pages)
 *
 * Uses the Kirby Snippet Controller plugin
 * Plugin details: https://github.com/lukaskleinschmidt/kirby-snippet-controller
 * =============================================================================
 */

return function ($kirby, $site, $page) {
  // Construct meta title (for <title> element)
  $metaTitle =
    $page->seoTitle()->length() > 0 ? $page->seoTitle() : $page->title();
  $metaTitle = $page->appendDefaultDividerToTitleElement()->toBool()
    ? $metaTitle . " " . $site->defaultDivider()
    : $metaTitle;
  $metaTitle = $page->appendSiteTitleToTitleElement()->toBool()
    ? $metaTitle . " " . $site->title()
    : $metaTitle;

  // Construct social share title (for Open Graph and Twitter Card title)
  $socialShareTitle =
    $page->socialShareTitle()->length() > 0
      ? $page->socialShareTitle()
      : $page->title();
  $socialShareTitle = $page->appendDefaultDividerToSocialShareTitle()->toBool()
    ? $socialShareTitle . " " . $site->defaultDivider()
    : $socialShareTitle;
  $socialShareTitle = $page->appendSiteTitleToSocialShareTitle()->toBool()
    ? $socialShareTitle . " " . $site->title()
    : $socialShareTitle;

  return [
    "pageLanguageCode" => $kirby->language()
      ? $kirby->language()->code()
      : "en",
    "pageLanguageLocale" => $kirby->language()
      ? $kirby->language()->locale()
      : "en_US",
    "metaTitle" => $metaTitle,
    "socialShareTitle" => $socialShareTitle,
    "socialShareDescription" =>
      $page->socialShareDescription()->length() > 0
        ? $page->socialShareDescription()
        : "",
    "twitterSiteHandle" =>
      $site->twitterSiteHandle()->length() > 0
        ? $site->twitterSiteHandle()
        : "",
    "twitterCreatorHandle" =>
      $page->twitterCreatorHandle()->length() > 0
        ? $site->twitterCreatorHandle()
        : "",
  ];
};
