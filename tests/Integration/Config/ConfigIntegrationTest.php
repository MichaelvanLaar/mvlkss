<?php

namespace Tests\Integration\Config;

use Tests\TestCase;

class ConfigIntegrationTest extends TestCase {
    private function thumbConfigFunctionsLoaded(): void {
        $thumbConfigFile =
            $this->kirby()->root("site") . "/config/thumb-config.php";

        if (!function_exists("getThumbConfig")) {
            require_once $thumbConfigFile;
        }
    }

    public function test_im_driver_includes_avif_srcset(): void {
        $this->thumbConfigFunctionsLoaded();

        $config = getThumbConfig("im");

        $this->assertArrayHasKey("jpg->avif", $config["thumbSrcsets"]);
    }

    public function test_gd_driver_excludes_avif_srcset(): void {
        $this->thumbConfigFunctionsLoaded();

        $config = getThumbConfig("gd");

        $this->assertArrayNotHasKey("jpg->avif", $config["thumbSrcsets"]);
    }

    public function test_im_driver_selector_maps_jpg_to_avif(): void {
        $this->thumbConfigFunctionsLoaded();

        $selector = getThumbConfig("im")["thumbSrcsetsSelector"];

        $this->assertArrayHasKey("jpg", $selector);
        $this->assertContains("jpg->avif", $selector["jpg"]);
    }

    public function test_gd_driver_selector_does_not_map_jpg_to_avif(): void {
        $this->thumbConfigFunctionsLoaded();

        $selector = getThumbConfig("gd")["thumbSrcsetsSelector"];

        $this->assertArrayHasKey("jpg", $selector);
        $this->assertNotContains("jpg->avif", $selector["jpg"]);
    }

    public function test_thumb_widths_are_positive_integers(): void {
        $this->thumbConfigFunctionsLoaded();

        $widths = getThumbConfig("im")["thumbWidths"];

        $this->assertIsArray($widths);
        $this->assertNotEmpty($widths);

        foreach ($widths as $width) {
            $this->assertIsInt($width);
            $this->assertGreaterThan(0, $width);
        }
    }

    public function test_kirby_thumbs_srcsets_matches_site_constants(): void {
        $siteConstants = $this->kirby()->option("site-constants", []);
        $thumbsSrcsets = $this->kirby()->option("thumbs.srcsets", null);

        $this->assertArrayHasKey(
            "thumb-srcsets",
            $siteConstants,
            "site-constants must contain thumb-srcsets",
        );
        $this->assertNotNull(
            $thumbsSrcsets,
            "thumbs.srcsets option must be set",
        );
        $this->assertSame(
            $siteConstants["thumb-srcsets"],
            $thumbsSrcsets,
            "thumbs.srcsets must equal site-constants.thumb-srcsets",
        );
    }
}
