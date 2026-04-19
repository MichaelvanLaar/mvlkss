/**
 * Vitest Setup File
 *
 * Runs before all tests. Asserts that the jsdom environment is active — a
 * config drift to `node` would otherwise silently produce TypeErrors on DOM
 * access rather than a clear "wrong environment" message.
 */

if (typeof window === "undefined" || typeof document === "undefined") {
  throw new Error(
    "Test setup error: jsdom environment is not active. " +
      'Ensure vitest.config.js sets environment: "jsdom".',
  );
}
