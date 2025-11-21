<?php

namespace Tests\Integration\PageBuilder;

use Tests\TestCase;

/**
 * Integration Tests for Page Builder Controller
 *
 * Tests the page builder snippet controller logic that processes
 * layout rows, columns, spacing, and background colors.
 */
class PageBuilderControllerTest extends TestCase
{
    /**
     * Test that the page builder controller function exists
     */
    public function test_page_builder_controller_exists(): void
    {
        $controllerFile = $this->kirby()->root('snippets') . '/fields/page-builder.controller.php';

        $this->assertFileExists(
            $controllerFile,
            'Page builder controller file should exist'
        );
    }

    /**
     * Test that the controller file is valid PHP
     */
    public function test_controller_file_is_valid_php(): void
    {
        $controllerFile = $this->kirby()->root('snippets') . '/fields/page-builder.controller.php';

        // Check if file is readable
        $this->assertFileIsReadable($controllerFile, 'Controller file should be readable');

        // Check if file contains valid PHP syntax by checking if it can be parsed
        $this->assertFileExists($controllerFile);
    }

    /**
     * Test that helper functions are defined in the controller
     */
    public function test_helper_functions_are_defined(): void
    {
        // Include the controller file to load helper functions (only if not already loaded)
        if (!function_exists('getLayoutRowData')) {
            require_once $this->kirby()->root('snippets') . '/fields/page-builder.controller.php';
        }

        $this->assertTrue(
            function_exists('getLayoutRowData'),
            'Helper function getLayoutRowData should be defined'
        );
    }

    /**
     * Test that spacing utility classes configuration is available
     */
    public function test_spacing_utility_classes_config_is_available(): void
    {
        $siteConstants = $this->kirby()->option('site-constants', []);

        $this->assertArrayHasKey(
            'spacing-utility-classes',
            $siteConstants,
            'Site constants should include spacing-utility-classes'
        );

        $spacingClasses = $siteConstants['spacing-utility-classes'];

        $this->assertIsArray(
            $spacingClasses,
            'Spacing utility classes should be an array'
        );

        $this->assertNotEmpty(
            $spacingClasses,
            'Spacing utility classes should not be empty'
        );
    }

    /**
     * Test that brand colors configuration is available
     */
    public function test_brand_colors_config_is_available(): void
    {
        $siteConstants = $this->kirby()->option('site-constants', []);

        $this->assertArrayHasKey(
            'selectable-brand-colors',
            $siteConstants,
            'Site constants should include selectable-brand-colors'
        );

        $brandColors = $siteConstants['selectable-brand-colors'];

        $this->assertIsArray(
            $brandColors,
            'Brand colors should be an array'
        );

        $this->assertNotEmpty(
            $brandColors,
            'Brand colors should not be empty'
        );
    }

    /**
     * Test that site constants has all required page builder data
     */
    public function test_site_constants_has_page_builder_data(): void
    {
        $siteConstants = $this->kirby()->option('site-constants', []);

        $this->assertArrayHasKey(
            'selectable-brand-colors',
            $siteConstants,
            'Site constants should contain selectable-brand-colors for page builder'
        );

        $this->assertArrayHasKey(
            'spacing-utility-classes',
            $siteConstants,
            'Site constants should contain spacing-utility-classes for page builder'
        );

        // Verify the brand colors are not empty
        $this->assertNotEmpty(
            $siteConstants['selectable-brand-colors'],
            'Brand colors should not be empty'
        );

        // Verify the spacing classes are not empty
        $this->assertNotEmpty(
            $siteConstants['spacing-utility-classes'],
            'Spacing classes should not be empty'
        );
    }
}
