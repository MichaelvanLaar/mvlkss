<?php

/**
 * PHPUnit Bootstrap File
 *
 * This file sets up the Kirby environment for testing.
 */

// Require Composer autoloader
require_once __DIR__ . '/../vendor/autoload.php';

// Set up environment variables for testing
define('DS', DIRECTORY_SEPARATOR);

// Ensure we're in test mode
putenv('KIRBY_MODE=test');
putenv('KIRBY_ENV=test');

// Initialize Kirby for testing
// This creates a minimal Kirby instance that can be used in tests
$kirby = new Kirby\Cms\App([
    'roots' => [
        'index'    => __DIR__ . '/../',
        'base'     => __DIR__ . '/../',
        'site'     => __DIR__ . '/../site',
        'storage'  => __DIR__ . '/../storage',
    ],
    'options' => [
        'cache' => [
            'pages' => [
                'active' => false
            ]
        ],
        'debug' => true,
    ]
]);

// Make Kirby instance globally available for tests
if (!function_exists('kirby')) {
    function kirby(): Kirby\Cms\App
    {
        return Kirby\Cms\App::instance();
    }
}
