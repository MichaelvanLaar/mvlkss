# Testing Guide

This document provides comprehensive information about the automated testing system for this Kirby CMS project.

## Table of Contents

- [Overview](#overview)
- [Quick Start](#quick-start)
- [Test Types](#test-types)
- [Running Tests](#running-tests)
- [Writing Tests](#writing-tests)
- [Continuous Integration](#continuous-integration)
- [Code Coverage](#code-coverage)
- [Troubleshooting](#troubleshooting)

## Overview

This project uses a comprehensive testing system to ensure code quality and prevent regressions:

- **PHP Testing**: PHPUnit for unit and integration tests, PHPStan for static analysis
- **JavaScript Testing**: Vitest for unit tests, ESLint for linting
- **CSS Linting**: Stylelint for CSS/Tailwind CSS code quality
- **YAML Validation**: yaml-lint for blueprint validation
- **CI/CD**: GitHub Actions for automated testing on push/PR

## Quick Start

### Install Dependencies

```bash
# Install Composer dependencies (PHP)
composer install

# Install npm dependencies (JavaScript, CSS, etc.)
npm install
```

### Run All Tests

```bash
# Run all tests and linters
npm test
```

### Run Specific Test Suites

```bash
# PHP tests only
npm run test:php

# JavaScript tests only
npm run test:js

# Run linters only
npm run lint
```

## Test Types

### 1. PHP Unit Tests (PHPUnit)

**Location**: `tests/Unit/` and `tests/Integration/`

**What they test**:
- Configuration validity (brand colors, spacing classes)
- Snippet controller logic
- Template rendering
- Helper functions

**Example**:
```php
namespace Tests\Unit\Config;

use Tests\TestCase;

class BrandColorsTest extends TestCase
{
    public function test_brand_colors_configuration_exists(): void
    {
        $brandColors = $this->kirby()->option('selectableBrandColors', []);
        $this->assertNotEmpty($brandColors);
    }
}
```

### 2. PHP Static Analysis (PHPStan)

**Configuration**: `phpstan.neon`

**What it checks**:
- Type safety
- Undefined variables
- Incorrect method calls
- Dead code

**Run**:
```bash
npm run test:phpstan
# or
vendor/bin/phpstan analyse
```

### 3. JavaScript Unit Tests (Vitest)

**Location**: `tests/js/` and inline `*.test.js` files

**What they test**:
- DOM manipulation
- Event handling
- JavaScript utility functions
- Browser interactions

**Example**:
```javascript
import { describe, it, expect } from 'vitest';

describe('Link Security', () => {
  it('should add rel="noopener" to external links', () => {
    // Test implementation
  });
});
```

### 4. JavaScript Linting (ESLint)

**Configuration**: `eslint.config.js`

**What it checks**:
- Code style consistency
- Best practices
- Potential bugs
- Browser compatibility

**Run**:
```bash
npm run lint:js

# Auto-fix issues
npm run lint:js:fix
```

### 5. CSS Linting (Stylelint)

**Configuration**: `.stylelintrc.json`

**What it checks**:
- CSS syntax errors
- Tailwind CSS best practices
- Code style consistency

**Run**:
```bash
npm run lint:css
```

### 6. YAML Validation

**Configuration**: `.yamllint.json`

**What it checks**:
- Blueprint YAML syntax
- Valid YAML structure

**Run**:
```bash
npm run lint:yaml
```

## Running Tests

### All Tests

Run the complete test suite (linting + unit tests):

```bash
npm test
```

### PHP Tests

```bash
# Run all PHP tests
npm run test:php

# Run with coverage
npm run test:php:coverage

# Run PHPStan only
npm run test:phpstan
```

### JavaScript Tests

```bash
# Run all JS tests
npm run test:js

# Run with coverage
npm run test:js:coverage

# Run in watch mode (for development)
npm run test:js:watch

# Open Vitest UI
npm run test:js:ui
```

### Linting

```bash
# Run all linters
npm run lint

# Individual linters
npm run lint:php      # PHPStan
npm run lint:js       # ESLint
npm run lint:css      # Stylelint
npm run lint:yaml     # YAML lint

# Auto-fix JavaScript issues
npm run lint:js:fix
```

### Coverage Reports

```bash
# Generate coverage for both PHP and JavaScript
npm run test:coverage

# PHP coverage only
npm run test:php:coverage

# JavaScript coverage only
npm run test:js:coverage
```

Coverage reports are generated in:
- PHP: `.coverage/html/` (open `index.html` in browser)
- JavaScript: `.coverage/js/` (open `index.html` in browser)

## Writing Tests

### PHP Unit Tests

1. **Create a test file** in `tests/Unit/` or `tests/Integration/`
2. **Extend the base TestCase**:

```php
<?php

namespace Tests\Unit\YourCategory;

use Tests\TestCase;

class YourTest extends TestCase
{
    public function test_something(): void
    {
        $this->assertTrue(true);
    }
}
```

3. **Use helper methods** from `tests/TestCase.php`:
   - `$this->kirby()` - Get Kirby instance
   - `$this->site()` - Get site instance
   - `$this->createTestPage()` - Create test page
   - `$this->assertValidHtml()` - Assert valid HTML
   - `$this->assertHasClass()` - Assert CSS class presence

### JavaScript Unit Tests

1. **Create a test file** with `.test.js` or `.spec.js` extension
2. **Import Vitest functions**:

```javascript
import { describe, it, expect, beforeEach } from 'vitest';

describe('Your Feature', () => {
  beforeEach(() => {
    // Setup before each test
  });

  it('should do something', () => {
    expect(true).toBe(true);
  });
});
```

3. **Test DOM interactions**:

```javascript
import { describe, it, expect } from 'vitest';

describe('Modal', () => {
  it('should open when triggered', () => {
    document.body.innerHTML = '<div id="modal"></div>';
    const modal = document.getElementById('modal');
    expect(modal).not.toBeNull();
  });
});
```

## Continuous Integration

### GitHub Actions

Tests run automatically on:
- Push to `main` or `develop` branches
- Pull requests to `main` or `develop`

**Workflow**: `.github/workflows/tests.yml`

The CI pipeline runs:
1. PHP tests (PHP 8.1, 8.2, 8.3)
2. PHPStan static analysis
3. JavaScript tests (Vitest)
4. All linters (ESLint, Stylelint, YAML)
5. Build test (ensures assets compile)

### Viewing CI Results

- Check the "Actions" tab in GitHub
- Green checkmark = all tests passed
- Red X = tests failed (click for details)

## Code Coverage

### PHP Coverage (Requires Xdebug)

```bash
# Generate HTML coverage report
npm run test:php:coverage

# Open .coverage/html/index.html in your browser
```

### JavaScript Coverage

```bash
# Generate HTML coverage report
npm run test:js:coverage

# Open .coverage/js/index.html in your browser
```

### Coverage Goals

Aim for:
- **80%+ coverage** for critical components
- **100% coverage** for configuration files
- **60%+ coverage** overall

## Troubleshooting

### PHPUnit: "Cannot find Kirby"

**Solution**: Ensure `vendor/autoload.php` exists:
```bash
composer install
```

### Vitest: "Cannot find module"

**Solution**: Install npm dependencies:
```bash
npm install
```

### PHPStan: Memory limit errors

**Solution**: Increase memory limit in `package.json` (already set to 1G):
```json
"test:phpstan": "vendor/bin/phpstan analyse --memory-limit=1G"
```

### ESLint: "Parsing error"

**Solution**: Check `eslint.config.js` for syntax errors. Ensure you're using the flat config format.

### YAML Lint: "Cannot find yamllint"

**Solution**: Install YAML lint globally or use npm script:
```bash
npm install --save-dev yaml-lint
```

### Tests pass locally but fail in CI

**Common causes**:
1. **Environment differences**: Check PHP version, Node version
2. **Missing dependencies**: Ensure `composer.lock` and `package-lock.json` are committed
3. **File permissions**: CI runs in a clean environment
4. **Caching issues**: Clear GitHub Actions cache in repository settings

## Best Practices

### General

1. **Write tests first** (TDD approach) when adding new features
2. **Run tests before committing** to catch issues early
3. **Keep tests simple** - one assertion per test when possible
4. **Use descriptive names** - `test_user_can_login()` not `test1()`
5. **Don't test framework code** - test your logic, not Kirby/Tailwind

### PHP Tests

1. **Isolate tests** - don't depend on execution order
2. **Clean up after tests** - use `tearDown()` method
3. **Mock external dependencies** - don't make real API calls
4. **Test edge cases** - empty strings, null values, large inputs

### JavaScript Tests

1. **Clear DOM between tests** - use `beforeEach()` to reset
2. **Test user interactions** - not implementation details
3. **Avoid timing dependencies** - use `waitFor()` for async
4. **Mock browser APIs** - localStorage, fetch, etc.

## File Structure

```
/tests/
  ├── bootstrap.php              # PHPUnit bootstrap
  ├── TestCase.php               # Base test class
  ├── Unit/                      # PHP unit tests
  │   ├── Config/
  │   │   ├── BrandColorsTest.php
  │   │   └── SpacingUtilityClassesTest.php
  │   └── ...
  ├── Integration/               # PHP integration tests
  │   └── PageBuilder/
  │       └── PageBuilderControllerTest.php
  └── js/                        # JavaScript tests
      ├── setup.js               # Vitest setup
      ├── link-security.test.js
      └── image-modal.test.js
```

## Additional Resources

- [PHPUnit Documentation](https://phpunit.de/documentation.html)
- [Vitest Documentation](https://vitest.dev/)
- [ESLint Rules](https://eslint.org/docs/rules/)
- [Stylelint Rules](https://stylelint.io/user-guide/rules/)
- [Kirby Testing](https://getkirby.com/docs/guide/plugins/testing)

## Getting Help

If you encounter issues:

1. Check this documentation
2. Review test output carefully
3. Check CI logs in GitHub Actions
4. Search existing issues in the repository
5. Ask in team chat or create an issue

---

**Happy Testing! 🧪**
