<?php
/**
 * =============================================================================
 * Main Menu Snippet for All Pages
 * 
 * Uses “main-menu.controller.php” via the Kirby Snippet Controller plugin
 * Plugin details: https://github.com/lukaskleinschmidt/kirby-snippet-controller
 * 
 * Receives variables from snippet controller:
 * - $mainMenuItems
 * - $mainMenuOpenLabel
 * - $mainMenuCloseLabel
 * =============================================================================
 */
?>
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
