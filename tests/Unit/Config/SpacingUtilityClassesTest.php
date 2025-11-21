<?php

namespace Tests\Unit\Config;

use Tests\TestCase;

/**
 * Tests for Spacing Utility Classes Configuration
 *
 * Ensures that the spacing utility classes configuration in site/config/config.php
 * is valid and contains all required spacing types with proper Tailwind CSS classes.
 */
class SpacingUtilityClassesTest extends TestCase
{
    private array $spacingClasses;

    protected function setUp(): void
    {
        parent::setUp();

        // Load the spacing classes from the config
        $siteConstants = $this->kirby()->option('site-constants', []);
        $this->spacingClasses = $siteConstants['spacing-utility-classes'] ?? [];
    }

    /**
     * Test that spacing configuration exists
     */
    public function test_spacing_configuration_exists(): void
    {
        $this->assertNotEmpty(
            $this->spacingClasses,
            'Spacing utility classes configuration should not be empty'
        );
    }

    /**
     * Test that all expected spacing types are defined
     */
    public function test_all_spacing_types_are_defined(): void
    {
        $expectedTypes = [
            'margin-top',
            'margin-bottom',
            'margin-start',
            'margin-end',
            'padding-top',
            'padding-bottom',
            'padding-start',
            'padding-end',
            'gap',
        ];

        foreach ($expectedTypes as $type) {
            $this->assertArrayHasKey(
                $type,
                $this->spacingClasses,
                "Spacing configuration is missing type: {$type}"
            );
        }
    }

    /**
     * Test that each spacing type has all size options
     */
    public function test_each_spacing_type_has_all_sizes(): void
    {
        $expectedSizes = ['none', 'small', 'medium', 'large', 'xlarge'];

        foreach ($this->spacingClasses as $type => $sizes) {
            foreach ($expectedSizes as $size) {
                $this->assertArrayHasKey(
                    $size,
                    $sizes,
                    "Spacing type '{$type}' is missing size: {$size}"
                );

                $this->assertNotEmpty(
                    $sizes[$size],
                    "Spacing type '{$type}' has empty value for size: {$size}"
                );
            }
        }
    }

    /**
     * Test that margin classes have correct prefixes
     */
    public function test_margin_classes_have_correct_prefixes(): void
    {
        $marginPrefixes = [
            'margin-top' => 'mt-',
            'margin-bottom' => 'mb-',
            'margin-start' => 'ms-',
            'margin-end' => 'me-',
        ];

        foreach ($marginPrefixes as $type => $expectedPrefix) {
            if (!isset($this->spacingClasses[$type])) {
                continue;
            }

            foreach ($this->spacingClasses[$type] as $size => $class) {
                $this->assertStringStartsWith(
                    $expectedPrefix,
                    $class,
                    "Spacing type '{$type}' size '{$size}' should start with '{$expectedPrefix}', got: {$class}"
                );
            }
        }
    }

    /**
     * Test that padding classes have correct prefixes
     */
    public function test_padding_classes_have_correct_prefixes(): void
    {
        $paddingPrefixes = [
            'padding-top' => 'pt-',
            'padding-bottom' => 'pb-',
            'padding-start' => 'ps-',
            'padding-end' => 'pe-',
        ];

        foreach ($paddingPrefixes as $type => $expectedPrefix) {
            if (!isset($this->spacingClasses[$type])) {
                continue;
            }

            foreach ($this->spacingClasses[$type] as $size => $class) {
                $this->assertStringStartsWith(
                    $expectedPrefix,
                    $class,
                    "Spacing type '{$type}' size '{$size}' should start with '{$expectedPrefix}', got: {$class}"
                );
            }
        }
    }

    /**
     * Test that gap classes have correct prefix
     */
    public function test_gap_classes_have_correct_prefix(): void
    {
        if (!isset($this->spacingClasses['gap'])) {
            $this->markTestSkipped('Gap spacing not configured');
        }

        foreach ($this->spacingClasses['gap'] as $size => $class) {
            $this->assertStringStartsWith(
                'gap-',
                $class,
                "Gap size '{$size}' should start with 'gap-', got: {$class}"
            );
        }
    }

    /**
     * Test that 'none' size always maps to *-0 classes
     */
    public function test_none_size_maps_to_zero_classes(): void
    {
        foreach ($this->spacingClasses as $type => $sizes) {
            if (isset($sizes['none'])) {
                $this->assertStringEndsWith(
                    '-0',
                    $sizes['none'],
                    "Spacing type '{$type}' 'none' size should end with '-0', got: {$sizes['none']}"
                );
            }
        }
    }

    /**
     * Test that spacing classes are valid Tailwind CSS format
     */
    public function test_spacing_classes_are_valid_tailwind_format(): void
    {
        foreach ($this->spacingClasses as $type => $sizes) {
            foreach ($sizes as $size => $class) {
                $this->assertMatchesRegularExpression(
                    '/^[a-z][a-z\-0-9]+$/',
                    $class,
                    "Spacing class '{$class}' for type '{$type}' size '{$size}' has invalid format"
                );
            }
        }
    }
}
