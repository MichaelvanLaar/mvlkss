/**
 * Vitest Setup File
 *
 * This file runs before all tests and sets up the testing environment.
 */

// Mock browser APIs that might be used in the code
global.window = global.window || {};
global.document = global.document || {};

// Add any global test utilities here
// For example, custom matchers, global mocks, etc.
