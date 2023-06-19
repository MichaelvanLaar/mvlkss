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
 * - $siteColorsArray
 * - $siteColorsCssCustomProperties
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

  // Create an array with all information about the site’s colors
  $siteColorsArray = [];
  $siteColorsCssCustomProperties = "";
  $siteColorScheme = $site->siteColorScheme()->toStructure();
  if ($siteColorScheme->isNotEmpty()) {
    $siteColorsCssCustomProperties .= "/* Site color scheme */\n";

    // Loop through each color group in the site color scheme
    foreach ($siteColorScheme as $siteColorGroup) {
      // Add the color group's information to the site colors array
      $siteColorsArray[$siteColorGroup->id()] = [
        "name" => $siteColorGroup->name()->value(),
        "includeInColorSelect" => $siteColorGroup
          ->includeInColorSelect()
          ->toBool(),
        "lightMode" => $siteColorGroup->lightMode()->value(),
        "darkMode" => $siteColorGroup->darkMode()->value(),
        "contrastForLightMode" => $siteColorGroup
          ->lightMode()
          ->toMostReadable(),
        "contrastForDarkMode" => $siteColorGroup->darkMode()->toMostReadable(),
      ];

      // Add the color group's information to the site colors CSS custom properties
      $siteColorsCssCustomProperties .=
        "        --site-color-" .
        $siteColorGroup->id() .
        "-light-mode: " .
        $siteColorGroup->lightMode()->value() .
        ";\n";
      $siteColorsCssCustomProperties .=
        "        --site-color-" .
        $siteColorGroup->id() .
        "-dark-mode: " .
        $siteColorGroup->darkMode()->value() .
        ";\n";
      $siteColorsCssCustomProperties .=
        "        --site-color-" .
        $siteColorGroup->id() .
        "-contrast-for-light-mode: " .
        $siteColorGroup->lightMode()->toMostReadable() .
        ";\n";
      $siteColorsCssCustomProperties .=
        "        --site-color-" .
        $siteColorGroup->id() .
        "-contrast-for-dark-mode: " .
        $siteColorGroup->darkMode()->toMostReadable() .
        ";\n";
    }
  }

  // Return data for use in the header snippet
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
    "siteColorsArray" => $siteColorsArray,
    "siteColorsCssCustomProperties" => $siteColorsCssCustomProperties,
  ];
};
