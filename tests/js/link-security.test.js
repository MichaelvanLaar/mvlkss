/**
 * Tests for Link Security functionality
 *
 * Exercises src/js/main-partials/link-security.js against jsdom DOM fixtures.
 */

import { describe, it, expect, beforeEach } from "vitest";
import { secureExternalLinks } from "@js/main-partials/link-security.js";

describe("Link Security", () => {
  beforeEach(() => {
    document.body.innerHTML = "";
  });

  it('adds rel="noopener" to links with target="_blank" and no rel', () => {
    document.body.innerHTML = `
      <a href="https://example.com" target="_blank" id="test-link">External</a>
    `;

    secureExternalLinks();

    const link = document.getElementById("test-link");
    expect(link.getAttribute("rel")).toBe("noopener");
  });

  it('does not modify links without target="_blank"', () => {
    document.body.innerHTML = `
      <a href="/internal" id="test-link">Internal</a>
    `;

    secureExternalLinks();

    const link = document.getElementById("test-link");
    expect(link.getAttribute("rel")).toBeNull();
  });

  it('leaves links with rel="noopener" exactly untouched', () => {
    document.body.innerHTML = `
      <a href="https://example.com" target="_blank" rel="noopener" id="test-link">External</a>
    `;

    secureExternalLinks();

    const link = document.getElementById("test-link");
    expect(link.getAttribute("rel")).toBe("noopener");
  });

  it('overwrites any rel attribute that is not exactly "noopener"', () => {
    // Production selector is :not([rel='noopener']) — exact-match exemption.
    // A rel value containing extra tokens (e.g. "noopener noreferrer") is
    // treated as insecure and overwritten to just "noopener".
    document.body.innerHTML = `
      <a href="https://example.com" target="_blank" rel="noopener noreferrer" id="multi">Multi</a>
      <a href="https://example.com" target="_blank" rel="noreferrer" id="other">Other</a>
    `;

    secureExternalLinks();

    expect(document.getElementById("multi").getAttribute("rel")).toBe(
      "noopener",
    );
    expect(document.getElementById("other").getAttribute("rel")).toBe(
      "noopener",
    );
  });

  it("processes multiple links in a single pass", () => {
    document.body.innerHTML = `
      <a href="https://a.com" target="_blank" id="link1">A</a>
      <a href="https://b.com" target="_blank" id="link2">B</a>
      <a href="/internal" id="link3">Internal</a>
    `;

    secureExternalLinks();

    expect(document.getElementById("link1").getAttribute("rel")).toBe(
      "noopener",
    );
    expect(document.getElementById("link2").getAttribute("rel")).toBe(
      "noopener",
    );
    expect(document.getElementById("link3").getAttribute("rel")).toBeNull();
  });

  it("is scoped to the root parameter when provided", () => {
    document.body.innerHTML = `
      <div id="scoped">
        <a href="https://a.com" target="_blank" id="inside">Inside</a>
      </div>
      <a href="https://b.com" target="_blank" id="outside">Outside</a>
    `;

    secureExternalLinks(document.getElementById("scoped"));

    expect(document.getElementById("inside").getAttribute("rel")).toBe(
      "noopener",
    );
    expect(document.getElementById("outside").getAttribute("rel")).toBeNull();
  });
});
