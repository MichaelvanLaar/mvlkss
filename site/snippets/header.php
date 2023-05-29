<?php
/**
 * =============================================================================
 * Header snippet for all pages
 * 
 * Uses “header.controller.php” via the Kirby Snippet Controller plugin
 * Plugin details: https://github.com/lukaskleinschmidt/kirby-snippet-controller
 * 
 * Receives variables from snippet controller:
 * - $pageLanguageCode
 * - $pageLanguageLocale
 * - $metaTitle
 * - $metaDescription
 * - $socialShareTitleOutput
 * - $socialShareDescriptionOutput
 * - $twitterSiteHandle
 * - $twitterCreatorHandle
 * - $siteLogoFile
 * - $mainMenuItems
 * =============================================================================
 */
?>
<!DOCTYPE html>
<html class="no-js" lang="<?= $pageLanguageCode ?>">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title><?= $metaTitle ?></title>
    <?php if (strlen($metaDescription) > 0): ?>
      <meta name="description" content="<?= $metaDescription ?>" />
    <?php endif; ?>

    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
    <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png" />
    <link rel="manifest" href="/site.webmanifest" />
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#000000" />
    <meta name="msapplication-TileColor" content="#ffffff" />
    <meta name="theme-color" content="#ffffff" />

    <!-- Change “no-js” class in <html> to “js” if JavaScript is enabled -->
    <script>
      (function (H) {
        H.className = H.className.replace(/\bno-js\b/, "js");
      })(document.documentElement);
    </script>

    <?= css("assets/css/main.css") ?>

    <!-- Open Graph Data -->
    <meta property="og:title" content="<?= $socialShareTitleOutput ?>" />
    <?php if (strlen($socialShareDescriptionOutput) > 0): ?>
      <meta property="og:description" content="<?= $socialShareDescriptionOutput ?>" />
    <?php endif; ?>
    <!-- <meta property="og:image" content="[Insert URL of image with 2:1 ratio]" /> -->
    <meta property="og:locale" content="<?= $pageLanguageLocale ?>" />

    <!-- Twitter Data -->
    <meta name="twitter:card" content="summary_large_image" />
    <?php if (strlen($twitterSiteHandle) > 0): ?>
      <meta name="twitter:site" content="<?= $twitterSiteHandle ?>" />
    <?php endif; ?>
    <?php if (strlen($twitterCreatorHandle) > 0): ?>
      <meta name="twitter:creator" content="<?= $twitterCreatorHandle ?>" />
    <?php endif; ?>
    <meta name="twitter:title" content="<?= $socialShareTitleOutput ?>" />
    <?php if (strlen($socialShareDescriptionOutput) > 0): ?>
      <meta name="twitter:description" content="<?= $socialShareDescriptionOutput ?>" />
    <?php endif; ?>
    <!-- <meta name="twitter:image" content="[Insert URL of image with 2:1 ratio]" /> -->

    <link rel="canonical" href="<?= $page->url() ?>" />
    <?php if (!$page->seoIndex()->toBool()): ?>
      <meta name="robots" content="noindex, nofollow">
    <?php endif; ?>
  </head>

  <body
    class="flex min-h-screen flex-col bg-white text-black dark:bg-black dark:text-white"
  >

    <!-- Page header -->
    <header
      style="--site-header-height: 6rem; --site-header-padding-y: 0.75rem;"
    >
      <div class="row-container-default flex py-[--site-header-padding-y]">
        <div
          style="
            --site-logo-height: calc(
              var(--site-header-height) - var(--site-header-padding-y)
            );
            --site-logo-aspect-ratio: <?= $siteLogoFile
              ->dimensions()
              ->ratio() ?>;
          "
          class="site-logo-container h-[calc(var(--site-logo-height)_+_2px)] w-[calc(var(--site-logo-height)_*_var(--site-logo-aspect-ratio))] max-w-[10rem]"
        >
          <a
            href="<?= $site->url() ?>"
            title="<?= $site->title() ?> → <?= $site->homePage()->title() ?>"
          >
            <?= $siteLogoFile->extension() == "svg"
              ? svg($siteLogoFile)
              : $siteLogoFile ?>
          </a>
        </div>
        <nav class="flex flex-grow items-center justify-end">
          <ul class="flex">
            <?php foreach ($mainMenuItems as $menuItem): ?>
              <li class="<?= $menuItem["isActive"] ?> ms-6">
                <a href="<?= $menuItem["url"] ?>">
                  <?= $menuItem["title"] ?>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        </nav>
      </div>
    </header>
 