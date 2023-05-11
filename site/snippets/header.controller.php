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
  $socialShareTitleOutput = "";
  if ($page->socialShareTitle()->length() > 0) {
    $socialShareTitleOutput = $page->socialShareTitle();
  } elseif ($page->seoTitle()->length() > 0) {
    $socialShareTitleOutput = $page->seoTitle();
  } else {
    $socialShareTitleOutput = $page->title();
  }
  $socialShareTitleOutput = $page
    ->appendDefaultDividerToSocialShareTitle()
    ->toBool()
    ? $socialShareTitleOutput . " " . $site->defaultDivider()
    : $socialShareTitleOutput;
  $socialShareTitleOutput = $page->appendSiteTitleToSocialShareTitle()->toBool()
    ? $socialShareTitleOutput . " " . $site->title()
    : $socialShareTitleOutput;

  // Construct social share description (for Open Graph and Twitter Card description)
  $socialShareDescriptionOutput = "";
  if ($page->socialShareDescription()->length() > 0) {
    $socialShareDescriptionOutput = $page->socialShareDescription();
  } elseif ($page->seoDescription()->length() > 0) {
    $socialShareDescriptionOutput = $page->seoDescription();
  }

  return [
    "pageLanguageCode" => $kirby->language()
      ? $kirby->language()->code()
      : "en",
    "pageLanguageLocale" => $kirby->language()
      ? $kirby->language()->locale()
      : "en_US",
    "metaTitle" => $metaTitle,
    "metaDescription" =>
      $page->seoDescription()->length() > 0 ? $page->seoDescription() : "",
    "socialShareTitleOutput" => $socialShareTitleOutput,
    "socialShareDescriptionOutput" => $socialShareDescriptionOutput,
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
