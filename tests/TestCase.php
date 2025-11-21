<?php

namespace Tests;

use Kirby\Cms\App;
use Kirby\Cms\Page;
use Kirby\Cms\Site;
use PHPUnit\Framework\TestCase as BaseTestCase;

/**
 * Base Test Case for all Kirby tests
 *
 * Provides helper methods and utilities for testing Kirby components.
 */
abstract class TestCase extends BaseTestCase
{
    protected App $kirby;

    /**
     * Set up the test environment
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->kirby = App::instance();
    }

    /**
     * Clean up after tests
     */
    protected function tearDown(): void
    {
        // Clear any caches
        $this->kirby->cache('pages')->flush();

        parent::tearDown();
    }

    /**
     * Get the Kirby instance
     */
    protected function kirby(): App
    {
        return $this->kirby;
    }

    /**
     * Get the site instance
     */
    protected function site(): Site
    {
        return $this->kirby->site();
    }

    /**
     * Create a test page
     *
     * @param array $props Page properties
     * @return Page
     */
    protected function createTestPage(array $props = []): Page
    {
        $defaults = [
            'slug'     => 'test-page',
            'template' => 'default',
            'content'  => [],
        ];

        $props = array_merge($defaults, $props);

        return new Page($props);
    }

    /**
     * Assert that a string contains valid HTML
     *
     * @param string $html
     * @param string $message
     */
    protected function assertValidHtml(string $html, string $message = ''): void
    {
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $result = $dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $this->assertTrue($result, $message ?: 'Expected valid HTML');
    }

    /**
     * Assert that a string contains a specific CSS class
     *
     * @param string $class
     * @param string $html
     * @param string $message
     */
    protected function assertHasClass(string $class, string $html, string $message = ''): void
    {
        $this->assertStringContainsString(
            'class="' . $class . '"',
            $html,
            $message ?: "Expected HTML to contain class: {$class}"
        );
    }

    /**
     * Assert that HTML contains a specific tag
     *
     * @param string $tag
     * @param string $html
     * @param string $message
     */
    protected function assertHasTag(string $tag, string $html, string $message = ''): void
    {
        $this->assertMatchesRegularExpression(
            '/<' . preg_quote($tag, '/') . '[>\s]/',
            $html,
            $message ?: "Expected HTML to contain tag: {$tag}"
        );
    }
}
