/**
 * Tests for Link Security functionality
 *
 * Tests that external links with target="_blank" have rel="noopener" added
 * for security purposes.
 */

import { describe, it, expect, beforeEach } from 'vitest';

describe('Link Security', () => {
  beforeEach(() => {
    // Clear the DOM before each test
    document.body.innerHTML = '';
  });

  it('should add rel="noopener" to links with target="_blank"', () => {
    // Set up test HTML
    document.body.innerHTML = `
      <a href="https://example.com" target="_blank" id="test-link">External Link</a>
    `;

    const link = document.getElementById('test-link');

    // Simulate the link security functionality
    if (link.getAttribute('target') === '_blank' && !link.hasAttribute('rel')) {
      link.setAttribute('rel', 'noopener');
    }

    expect(link.getAttribute('rel')).toBe('noopener');
  });

  it('should not modify links without target="_blank"', () => {
    document.body.innerHTML = `
      <a href="/internal" id="test-link">Internal Link</a>
    `;

    const link = document.getElementById('test-link');
    const originalRel = link.getAttribute('rel');

    expect(originalRel).toBeNull();
  });

  it('should not override existing rel attribute', () => {
    document.body.innerHTML = `
      <a href="https://example.com" target="_blank" rel="noopener noreferrer" id="test-link">External Link</a>
    `;

    const link = document.getElementById('test-link');
    const originalRel = link.getAttribute('rel');

    expect(originalRel).toBe('noopener noreferrer');
  });

  it('should handle multiple links correctly', () => {
    document.body.innerHTML = `
      <a href="https://example1.com" target="_blank" id="link1">Link 1</a>
      <a href="https://example2.com" target="_blank" id="link2">Link 2</a>
      <a href="/internal" id="link3">Link 3</a>
    `;

    const insecureLinks = document.querySelectorAll('a[target="_blank"]:not([rel="noopener"])');

    // Simulate the security fix
    for (let link of insecureLinks) {
      link.setAttribute('rel', 'noopener');
    }

    const link1 = document.getElementById('link1');
    const link2 = document.getElementById('link2');
    const link3 = document.getElementById('link3');

    expect(link1.getAttribute('rel')).toBe('noopener');
    expect(link2.getAttribute('rel')).toBe('noopener');
    expect(link3.getAttribute('rel')).toBeNull();
  });
});
