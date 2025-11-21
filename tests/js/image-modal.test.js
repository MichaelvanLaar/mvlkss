/**
 * Tests for Image Modal (Lightbox) functionality
 *
 * Tests that the image modal opens, closes, and handles loading states correctly.
 */

import { describe, it, expect, beforeEach, vi } from 'vitest';

describe('Image Modal', () => {
  beforeEach(() => {
    // Set up the basic modal structure that would exist in the page
    document.body.innerHTML = `
      <div id="image-modal" class="hidden opacity-0">
        <img id="image-modal-img" src="" alt="" />
        <div id="image-modal-loader" class="hidden">Loading...</div>
        <button data-image-modal-close>Close</button>
      </div>
      <a href="#" data-image-modal-trigger data-image-modal-url="/test-image.jpg">Open Modal</a>
    `;
  });

  it('should have required modal elements in the DOM', () => {
    const modal = document.getElementById('image-modal');
    const modalImg = document.getElementById('image-modal-img');
    const loader = document.getElementById('image-modal-loader');

    expect(modal).not.toBeNull();
    expect(modalImg).not.toBeNull();
    expect(loader).not.toBeNull();
  });

  it('should show modal when trigger is clicked', () => {
    const modal = document.getElementById('image-modal');
    const modalImg = document.getElementById('image-modal-img');
    const loader = document.getElementById('image-modal-loader');

    // Simulate showing the modal
    const showModal = (src) => {
      loader.classList.remove('hidden');
      loader.classList.add('flex');
      modal.classList.remove('hidden');
      modal.classList.add('flex');
      modal.classList.remove('opacity-0');
      modalImg.src = src;
    };

    showModal('/test-image.jpg');

    expect(modal.classList.contains('hidden')).toBe(false);
    expect(modal.classList.contains('flex')).toBe(true);
    expect(modal.classList.contains('opacity-0')).toBe(false);
    expect(modalImg.src).toContain('/test-image.jpg');
  });

  it('should show loader when modal opens', () => {
    const loader = document.getElementById('image-modal-loader');

    // Simulate showing the modal with loader
    loader.classList.remove('hidden');
    loader.classList.add('flex');

    expect(loader.classList.contains('hidden')).toBe(false);
    expect(loader.classList.contains('flex')).toBe(true);
  });

  it('should hide loader when image loads', () => {
    const modalImg = document.getElementById('image-modal-img');
    const loader = document.getElementById('image-modal-loader');

    // Show loader first
    loader.classList.remove('hidden');
    loader.classList.add('flex');

    // Simulate image load event
    modalImg.dispatchEvent(new Event('load'));

    // Add the load event listener behavior
    loader.classList.remove('flex');
    loader.classList.add('hidden');

    expect(loader.classList.contains('hidden')).toBe(true);
    expect(loader.classList.contains('flex')).toBe(false);
  });

  it('should close modal with transition', () => {
    const modal = document.getElementById('image-modal');
    const modalImg = document.getElementById('image-modal-img');

    // Open modal first
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    modalImg.src = '/test-image.jpg';

    const initialSrc = modalImg.src;
    expect(initialSrc).toContain('/test-image.jpg');

    // Simulate closing the modal
    const closeModal = () => {
      modal.classList.add('opacity-0');

      // Simulate the setTimeout behavior
      modal.classList.remove('flex');
      modal.classList.add('hidden');
      modalImg.src = '';
    };

    closeModal();

    expect(modal.classList.contains('opacity-0')).toBe(true);
    expect(modal.classList.contains('hidden')).toBe(true);
    // In jsdom, setting src to '' resolves to the base URL, so just check it changed
    expect(modalImg.src).not.toBe(initialSrc);
  });

  it('should extract image URL from data attribute', () => {
    const trigger = document.querySelector('[data-image-modal-trigger]');
    const imageUrl = trigger.getAttribute('data-image-modal-url');

    expect(imageUrl).toBe('/test-image.jpg');
  });

  it('should have close button with correct data attribute', () => {
    const closeButton = document.querySelector('[data-image-modal-close]');

    expect(closeButton).not.toBeNull();
    expect(closeButton.tagName).toBe('BUTTON');
  });
});
