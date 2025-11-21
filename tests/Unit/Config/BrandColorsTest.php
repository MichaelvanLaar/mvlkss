<?php

namespace Tests\Unit\Config;

use Tests\TestCase;

/**
 * Tests for Brand Colors Configuration
 *
 * Ensures that the brand color configuration in site/config/config.php
 * is valid and contains all required keys with proper Tailwind CSS classes.
 */
class BrandColorsTest extends TestCase
{
    private array $brandColors;

    protected function setUp(): void
    {
        parent::setUp();

        // Load the brand colors from the config
        $siteConstants = $this->kirby()->option('site-constants', []);
        $this->brandColors = $siteConstants['selectable-brand-colors'] ?? [];
    }

    /**
     * Test that brand colors configuration exists
     */
    public function test_brand_colors_configuration_exists(): void
    {
        $this->assertNotEmpty(
            $this->brandColors,
            'Brand colors configuration should not be empty'
        );
    }

    /**
     * Test that each brand color has all required keys
     */
    public function test_each_brand_color_has_required_keys(): void
    {
        $requiredKeys = [
            'label',
            'light-tailwindcss-bg-class',
            'light-tailwindcss-border-class',
            'light-tailwindcss-text-class',
            'light-contrast-tailwindcss-prose-class',
            'light-contrast-tailwindcss-text-class',
            'dark-tailwindcss-bg-class',
            'dark-tailwindcss-border-class',
            'dark-tailwindcss-text-class',
            'dark-contrast-tailwindcss-prose-class',
            'dark-contrast-tailwindcss-text-class',
        ];

        foreach ($this->brandColors as $colorKey => $colorConfig) {
            foreach ($requiredKeys as $key) {
                $this->assertArrayHasKey(
                    $key,
                    $colorConfig,
                    "Brand color '{$colorKey}' is missing required key: {$key}"
                );

                $this->assertNotEmpty(
                    $colorConfig[$key],
                    "Brand color '{$colorKey}' has empty value for key: {$key}"
                );
            }
        }
    }

    /**
     * Test that Tailwind CSS classes have correct prefixes
     */
    public function test_tailwind_classes_have_correct_prefixes(): void
    {
        $classPrefixes = [
            'light-tailwindcss-bg-class' => 'bg-',
            'light-tailwindcss-border-class' => 'border-',
            'light-tailwindcss-text-class' => 'text-',
            'light-contrast-tailwindcss-prose-class' => 'prose-',
            'light-contrast-tailwindcss-text-class' => 'text-',
            'dark-tailwindcss-bg-class' => 'dark:bg-',
            'dark-tailwindcss-border-class' => 'dark:border-',
            'dark-tailwindcss-text-class' => 'dark:text-',
            'dark-contrast-tailwindcss-prose-class' => 'dark:prose-',
            'dark-contrast-tailwindcss-text-class' => 'dark:text-',
        ];

        foreach ($this->brandColors as $colorKey => $colorConfig) {
            foreach ($classPrefixes as $configKey => $expectedPrefix) {
                $class = $colorConfig[$configKey] ?? '';

                $this->assertStringStartsWith(
                    $expectedPrefix,
                    $class,
                    "Brand color '{$colorKey}' has invalid prefix for '{$configKey}'. " .
                    "Expected to start with '{$expectedPrefix}', got: {$class}"
                );
            }
        }
    }

    /**
     * Test that color keys follow naming convention
     */
    public function test_color_keys_follow_naming_convention(): void
    {
        foreach (array_keys($this->brandColors) as $colorKey) {
            $this->assertMatchesRegularExpression(
                '/^[a-z0-9\-]+$/',
                $colorKey,
                "Brand color key '{$colorKey}' should only contain lowercase letters, numbers, and hyphens"
            );
        }
    }

    /**
     * Test that labels are human-readable
     */
    public function test_labels_are_human_readable(): void
    {
        foreach ($this->brandColors as $colorKey => $colorConfig) {
            $label = $colorConfig['label'] ?? '';

            $this->assertGreaterThan(
                2,
                strlen($label),
                "Brand color '{$colorKey}' label should be at least 3 characters long"
            );

            $this->assertMatchesRegularExpression(
                '/^[A-Z]/',
                $label,
                "Brand color '{$colorKey}' label should start with a capital letter"
            );
        }
    }

    /**
     * Test that there's at least one brand color defined
     */
    public function test_at_least_one_brand_color_exists(): void
    {
        $this->assertGreaterThanOrEqual(
            1,
            count($this->brandColors),
            'At least one brand color should be defined'
        );
    }
}
