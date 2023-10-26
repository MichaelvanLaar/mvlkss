<?php
/**
 * =============================================================================
 * Header Snippet for All Pages
 *
 * Uses “header.controller.php” via the Kirby Snippet Controller plugin
 * Plugin details: https://github.com/lukaskleinschmidt/kirby-snippet-controller
 *
 * Receives variables from snippet controller:
 * - $pageLanguageCode
 * - $pageLanguageLocale
 * - $languages
 * - $defaultLanguage
 * - $pageLanguageDirection
 * - $hasMoreThanOneLanguage
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

// Include centrally managed global constants
?>
<!DOCTYPE html>
<html class="no-js" lang="<?= $pageLanguageCode ?>" dir="<?= $pageLanguageDirection ?>">
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

    <link
      rel="preload"
      href="/assets/fonts/nunito-sans-v15-latin-regular.woff2"
      as="font"
      type="font/woff2"
      crossorigin
    />

    <style>
      body {
        /* Set site header height */
        --site-header-initial-height: 6rem;
        --site-header-scroll-height: 3rem;
        --site-header-height: var(--site-header-initial-height);

        /* Set site header vertical padding */
        --site-header-initial-padding-y: 0.75rem;
        --site-header-scroll-padding-y: 0.3125rem;
        --site-header-padding-y: var(--site-header-initial-padding-y);

        /* Calculate site logo dimensions */
        --site-logo-initial-height: calc(var(--site-header-initial-height) - (2 * var(--site-header-initial-padding-y)));
        --site-logo-height: calc(var(--site-header-height) - (2 * var(--site-header-padding-y)));
        --site-logo-aspect-ratio: <?= $siteLogoFile->dimensions()->ratio() ?>;

        /* Calculate site logo container dimensions */
        --site-logo-initial-container-height: calc(var(--site-logo-initial-height) + 2px);
        --site-logo-container-height: calc(var(--site-logo-height) + 2px);
        --site-logo-container-width: calc(var(--site-logo-height) * var(--site-logo-aspect-ratio));

        /* Calculate navigation toggle width  */
        --main-navigation-toggle-width: calc(var(--site-header-scroll-height) - (2 * var(--site-header-scroll-padding-y)) + 1.5rem);

        /* Calculate stroke width of navigation toggle icon  */
        --nav-toggle-icon-stroke-width: calc(var(--site-header-scroll-height) / 24);
      }
    </style>
      <?= css("assets/css/main.css") ?>

    <!-- Open Graph Data -->
    <meta property="og:title" content="<?= $socialShareTitleOutput ?>" />
    <?php if (strlen($socialShareDescriptionOutput) > 0): ?>
      <meta property="og:description" content="<?= $socialShareDescriptionOutput ?>" />
    <?php endif; ?>
    <?php if (strlen($socialShareImageUrlOutput) > 0): ?>
      <meta property="og:image" content="<?= $socialShareImageUrlOutput ?>" />
    <?php endif; ?>
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
    <?php if (strlen($socialShareImageUrlOutput) > 0): ?>
      <meta name="twitter:image" content="<?= $socialShareImageUrlOutput ?>" />
    <?php endif; ?>

    <!-- Hreflang annotations -->
    <?php if ($hasMoreThanOneLanguage): ?>
      <?php foreach ($languages as $lang): ?>
        <link 
          rel="alternate" 
          hreflang="<?= $lang->code() ?>" 
          href="<?= $page->url($lang->code()) ?>" 
        />
      <?php endforeach; ?>
      <link 
        rel="alternate" 
        hreflang="x-default" 
        href="<?= $page->url($defaultLanguage->code()) ?>" 
      />
    <?php endif; ?>

    <link rel="canonical" href="<?= $page->url() ?>" />
    <?php if (!$page->seoIndex()->toBool()): ?>
      <meta name="robots" content="noindex, nofollow">
    <?php endif; ?>
  </head>

  <body class="flex min-h-screen flex-col bg-white dark:bg-neutral-950 dark:text-white print:block">

    <!-- PAGE HEADER -->
    <!-- Row -->
    <header
      id="page-header"
      class="z-30 h-[var(--site-header-height)] w-full bg-neutral-200 js:fixed dark:bg-neutral-600 print:h-[var(--site-header-initial-height)] print:bg-transparent print:js:static"
      role="banner"
    >
      <!-- Inner row container -->
      <div class="row-container-default flex justify-between py-[var(--site-header-padding-y)]">
        <!-- Site logo -->
        <div class="site-logo-container h-[var(--site-logo-container-height)] w-[var(--site-logo-container-width)] max-w-[10rem] print:h-[var(--site-logo-initial-container-height)]">
          <a
            href="<?= $site->url() ?>"
            title="<?= $site->title() ?> → <?= $site->homePage()->title() ?>"
            aria-label="<?= $site->title() ?> Logo"
          >
            <?= $siteLogoFile->extension() == "svg"
              ? svg($siteLogoFile)
              : $siteLogoFile ?>
          </a>
        </div>

        <?php snippet("base/main-menu", [
          "pageLanguageCode" => $pageLanguageCode,
          "languages" => $languages,
          "defaultLanguage" => $defaultLanguage,
          "hasMoreThanOneLanguage" => $hasMoreThanOneLanguage,
        ]); ?>

      </div>
    </header>
