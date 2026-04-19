<?php

/**
 * PHPUnit Bootstrap File
 *
 * Sets up the Kirby environment for testing. Failures here are promoted to
 * RuntimeException so they surface as a clear bootstrap error instead of
 * cascading into confusing individual test failures.
 */

require_once __DIR__ . "/../vendor/autoload.php";

// Kirby defines DS itself; guard against a redefinition PHP warning.
if (!defined("DS")) {
    define("DS", DIRECTORY_SEPARATOR);
}

putenv("KIRBY_MODE=test");
putenv("KIRBY_ENV=test");

// Some Kirby plugins (e.g. code-highlighter) `include_once` their own
// vendor/autoload.php. In the main project those dependencies resolve via the
// top-level vendor, so the plugin's inner vendor dir never gets created — and
// Kirby's Whoops handler promotes the resulting include warning to a fatal
// exception during App construction. Write a harmless stub so the include
// succeeds. The real classes are already loaded by the top-level autoloader.
// Guard: only write the stub if the plugin directory itself exists (it is
// committed to the repo); never create plugin directories that are absent.
$pluginVendorStubs = [
    __DIR__ . "/../site/plugins/code-highlighter/vendor/autoload.php",
];
foreach ($pluginVendorStubs as $stubPath) {
    $pluginDir = dirname(dirname($stubPath)); // site/plugins/<name>/
    if (is_dir($pluginDir) && !is_file($stubPath)) {
        $vendorDir = dirname($stubPath);
        if (!is_dir($vendorDir)) {
            mkdir($vendorDir, 0755, true);
        }
        file_put_contents(
            $stubPath,
            "<?php\n// Test-only stub; real classes load via project vendor/.\n",
        );
    }
}

$kirby = new Kirby\Cms\App([
    "roots" => [
        "index" => __DIR__ . "/../",
        "base" => __DIR__ . "/../",
        "site" => __DIR__ . "/../site",
        "storage" => __DIR__ . "/../storage",
    ],
    "options" => [
        "cache" => [
            "pages" => [
                "active" => false,
            ],
        ],
        // debug is false so plugin warnings (e.g. missing plugin vendor/autoload.php)
        // do not get promoted to Whoops exceptions during test bootstrap.
        "debug" => false,
    ],
]);

if (Kirby\Cms\App::instance() !== $kirby) {
    throw new RuntimeException(
        "Test bootstrap failed: Kirby singleton did not register the bootstrapped instance.",
    );
}

$siteConstants = $kirby->option("site-constants");
if (!is_array($siteConstants) || empty($siteConstants)) {
    throw new RuntimeException(
        "Test bootstrap failed: site-constants option is not loaded. " .
            "Check that site/config/config.php is accessible from " .
            __DIR__ .
            "/../",
    );
}

if (!function_exists("kirby")) {
    function kirby(): Kirby\Cms\App {
        return Kirby\Cms\App::instance();
    }
}
