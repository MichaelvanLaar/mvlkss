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
 * - $mainMenuOpenLabel
 * - $mainMenuCloseLabel
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
        --site-logo-height: calc(var(--site-header-height) - (2 * var(--site-header-padding-y)));
        --site-logo-aspect-ratio: <?= $siteLogoFile->dimensions()->ratio() ?>;

        /* Calculate site logo container dimensions */
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

  <body class="flex min-h-screen flex-col bg-white text-black dark:bg-black dark:text-white">

    <!-- PAGE HEADER -->
    <!-- Row -->
    <header
      id="page-header"
      class="h-[var(--site-header-height)] w-full bg-neutral-200 js:fixed dark:bg-neutral-600 print:bg-transparent"
    >
      <!-- Inner row container -->
      <div class="row-container-default flex justify-between py-[var(--site-header-padding-y)]">
        <!-- Site logo -->
        <div class="site-logo-container h-[var(--site-logo-container-height)] w-[var(--site-logo-container-width)] max-w-[10rem]">
          <a
            href="<?= $site->url() ?>"
            title="<?= $site->title() ?> → <?= $site->homePage()->title() ?>"
          >
            <?= $siteLogoFile->extension() == "svg"
              ? svg($siteLogoFile)
              : $siteLogoFile ?>
          </a>
        </div>

        <!-- Main menu -->
        <div class="-me-6 flex justify-end ps-6 md:me-0 print:hidden">
          <input
            type="checkbox"
            id="main-menu-state"
            class="hidden"
            aria-hidden="true"
          />
          <nav class="flex items-center">
            <!-- Toggle button for mobile menu, see:
                 https://www.pausly.app/blog/accessible-hamburger-buttons-without-javascript
                 (with animated toogle icon instead of two unicode characters) -->
            <div class="relative h-[var(--site-logo-height)] w-[var(--main-navigation-toggle-width)] md:hidden">
              <a
                href="#main-menu-state"
                class="main-menu-open absolute inset-0"
                role="button"
              >
                <span class="absolute m-[-1px] h-px w-px overflow-hidden whitespace-nowrap border-0 p-0">
                  <?= $mainMenuOpenLabel ?>
                </span>
              </a>
              <a
                href="#"
                class="main-menu-close absolute inset-0 hidden"
                role="button"
              >
                <span class="absolute m-[-1px] h-px w-px overflow-hidden whitespace-nowrap border-0 p-0">
                  <?= $mainMenuCloseLabel ?>
                </span>
              </a>
              <label
                for="main-menu-state"
                class="absolute inset-0 flex cursor-pointer items-center justify-end pe-6"
                aria-hidden="true"
              >
                <!-- Animated hamburger icon -->
                <div class="nav-toggle-icon relative h-[calc(var(--site-header-scroll-height)_/_4)] w-[calc(var(--site-header-scroll-height)_/_3)]">
                  <span class="absolute left-0 top-0 block h-[var(--nav-toggle-icon-stroke-width)] w-full rotate-0 rounded-sm bg-black transition-[left,_width,_top,_transform] duration-300 ease-in-out dark:bg-white"></span>
                  <span class="absolute left-0 top-[calc(50%_-_(var(--nav-toggle-icon-stroke-width)_/_2))] block h-[var(--nav-toggle-icon-stroke-width)] w-full rotate-0 rounded-sm bg-black transition-[left,_width,_top,_transform] duration-300 ease-in-out dark:bg-white"></span>
                  <span class="absolute left-0 top-[calc(50%_-_(var(--nav-toggle-icon-stroke-width)_/_2))] block h-[var(--nav-toggle-icon-stroke-width)] w-full rotate-0 rounded-sm bg-black transition-[left,_width,_top,_transform] duration-300 ease-in-out dark:bg-white"></span>
                  <span class="absolute left-0 top-[calc(100%_-_var(--nav-toggle-icon-stroke-width))] block h-[var(--nav-toggle-icon-stroke-width)] w-full rotate-0 rounded-sm bg-black transition-[left,_width,_top,_transform] duration-300 ease-in-out dark:bg-white"></span>
                </div>
              </label>
            </div>

            <!-- Main menu items -->
            <ul class="invisible absolute end-3 top-[var(--site-header-height)] flex max-h-[calc(100vh_-_var(--site-header-height)_-_0.75rem)] max-w-2xl flex-col overflow-y-auto bg-neutral-300 py-3 opacity-0 transition-[opacity,_visibility] duration-300 ease-in-out dark:bg-neutral-700 md:visible md:static md:max-h-none md:flex-row md:overflow-y-visible md:bg-transparent md:py-0 md:opacity-100">
              <?php foreach ($mainMenuItems as $menuItem): ?>
                <li class="<?= $menuItem["isActive"] ?> md:ms-6">
                  <a
                    href="<?= $menuItem["url"] ?>"
                    target="<?= $menuItem["target"] ?>"
                    class="block px-6 py-3 md:static md:px-0 md:py-0"
                    <?= $menuItem["target"] == "_blank"
                      ? "rel=\"noopener\""
                      : "" ?>
                  >
                    <?= $menuItem["title"] ?>
                  </a>
                </li>
              <?php endforeach; ?>
            </ul>
          </nav>
        </div>
      </div>
    </header>
 