<?php
/**
 * =============================================================================
 * Controller for Header Snippet (which is used on all pages)
 *
 * Uses the Kirby Snippet Controller plugin
 * Plugin details: https://github.com/lukaskleinschmidt/kirby-snippet-controller
 *
 * Provides variables for use in the header snippet:
 * - $pageLanguageCode
 * - $pageLanguageLocale
 * - $metaTitle
 * - $metaDescription
 * - $socialShareTitleOutput
 * - $socialShareDescriptionOutput
 * - $socialShareImageUrlOutput
 * - $twitterSiteHandle
 * - $twitterCreatorHandle
 * - $siteLogoFile
 * =============================================================================
 */

return function ($kirby, $site, $page) {
  // Minimize repetitive method calls
  $seoTitle = $page->seoTitle();
  $title = $page->title();
  $defaultDivider = $site->defaultDivider();
  $siteTitle = $site->title();
  $socialShareTitle = $page->socialShareTitle();
  $seoDescription = $page->seoDescription();
  $twitterSiteHandle = $site->twitterSiteHandle();
  $twitterCreatorHandle = $page->twitterCreatorHandle();

  // Construct meta title (for <title> element)
  $metaTitle = $seoTitle->length() > 0 ? $seoTitle : $title;
  $metaTitle .= $page->appendDefaultDividerToTitleElement()->toBool()
    ? " " . $defaultDivider
    : "";
  $metaTitle .= $page->appendSiteTitleToTitleElement()->toBool()
    ? " " . $siteTitle
    : "";

  // Construct social share title (for Open Graph and Twitter Card title)
  $socialShareTitleOutput =
    $socialShareTitle->length() > 0
      ? $socialShareTitle
      : ($seoTitle->length() > 0
        ? $seoTitle
        : $title);
  $socialShareTitleOutput .= $page
    ->appendDefaultDividerToSocialShareTitle()
    ->toBool()
    ? " " . $defaultDivider
    : "";
  $socialShareTitleOutput .= $page
    ->appendSiteTitleToSocialShareTitle()
    ->toBool()
    ? " " . $siteTitle
    : "";

  // Construct social share description (for Open Graph and Twitter Card description)
  $socialShareDescriptionOutput =
    $page->socialShareDescription()->length() > 0
      ? $page->socialShareDescription()
      : $seoDescription;

  return [
    "pageLanguageCode" => $kirby->language()
      ? $kirby->language()->code()
      : "en",
    "pageLanguageLocale" => $kirby->language()
      ? $kirby->language()->locale(LC_ALL)
      : "en_US",
    "metaTitle" => $metaTitle,
    "metaDescription" => $seoDescription->length() > 0 ? $seoDescription : "",
    "socialShareTitleOutput" => $socialShareTitleOutput,
    "socialShareDescriptionOutput" => $socialShareDescriptionOutput,
    "socialShareImageUrlOutput" => $page->socialShareImage()->toFile()
      ? $page
        ->socialShareImage()
        ->toFile()
        ->url()
      : ($site->siteSocialShareImage()->toFile()
        ? $site
          ->siteSocialShareImage()
          ->toFile()
          ->url()
        : ""),
    "twitterSiteHandle" =>
      $twitterSiteHandle->length() > 0 ? $twitterSiteHandle : "",
    "twitterCreatorHandle" =>
      $twitterCreatorHandle->length() > 0 ? $twitterCreatorHandle : "",
    "siteLogoFile" => $site->siteLogo()->toFile(),
  ];
};
