<?php

/**
 * Kirby registers a runtime `class_alias('Kirby\Cms\App', 'Kirby')` via
 * kirby/config/setup.php's spl_autoload_register callback. PHPStan's static
 * reflection can't see aliases created that way, so this stub declares the
 * same alias as a real class purely for static analysis (never loaded at
 * runtime — see phpstan.neon's `scanFiles`).
 */
class Kirby extends Kirby\Cms\App {}
