/**
 * Tests for Image Modal (Lightbox) functionality
 *
 * Exercises src/js/main-partials/image-modal.js against jsdom DOM fixtures.
 */

import { describe, it, expect, beforeEach, afterEach, vi } from "vitest";
import {
  showModal,
  closeModal,
  initImageModal,
} from "@js/main-partials/image-modal.js";

function setupModalDom() {
  document.body.innerHTML = `
    <div id="image-modal" class="hidden opacity-0">
      <img id="image-modal-img" src="" alt="" />
      <div id="image-modal-loader" class="hidden">Loading...</div>
      <button data-image-modal-close>Close</button>
    </div>
    <a href="#" data-image-modal-trigger data-image-modal-url="/test-image.jpg">Open</a>
  `;

  return {
    modal: document.getElementById("image-modal"),
    modalImg: document.getElementById("image-modal-img"),
    loader: document.getElementById("image-modal-loader"),
  };
}

describe("Image Modal — showModal", () => {
  it("unhides the modal and sets the image source", () => {
    const { modal, modalImg, loader } = setupModalDom();

    showModal(modal, modalImg, loader, "/test-image.jpg");

    expect(modal.classList.contains("hidden")).toBe(false);
    expect(modal.classList.contains("flex")).toBe(true);
    expect(modal.classList.contains("opacity-0")).toBe(false);
    expect(modalImg.src).toContain("/test-image.jpg");
  });

  it("displays the loader while the image is loading", () => {
    const { modal, modalImg, loader } = setupModalDom();

    showModal(modal, modalImg, loader, "/test-image.jpg");

    expect(loader.classList.contains("hidden")).toBe(false);
    expect(loader.classList.contains("flex")).toBe(true);
  });
});

describe("Image Modal — closeModal", () => {
  beforeEach(() => {
    vi.useFakeTimers();
  });

  afterEach(() => {
    vi.useRealTimers();
  });

  it("applies opacity-0 immediately and hides the modal after the transition", () => {
    const { modal, modalImg, loader } = setupModalDom();
    showModal(modal, modalImg, loader, "/test-image.jpg");

    closeModal(modal, modalImg, loader, 300);

    expect(modal.classList.contains("opacity-0")).toBe(true);
    // Hidden state should not apply before the timer elapses
    expect(modal.classList.contains("hidden")).toBe(false);

    vi.advanceTimersByTime(300);

    expect(modal.classList.contains("flex")).toBe(false);
    expect(modal.classList.contains("hidden")).toBe(true);
    expect(modalImg.getAttribute("src")).toBe("");
    expect(loader.classList.contains("hidden")).toBe(true);
  });
});

describe("Image Modal — initImageModal", () => {
  it("does nothing when the modal elements are absent", () => {
    document.body.innerHTML = "<p>no modal here</p>";

    // Should not throw
    expect(() => initImageModal()).not.toThrow();
  });

  it("opens the modal when a trigger is clicked", () => {
    const { modal, modalImg } = setupModalDom();
    initImageModal();

    document.dispatchEvent(new Event("DOMContentLoaded"));

    const trigger = document.querySelector("[data-image-modal-trigger]");
    trigger.click();

    expect(modal.classList.contains("flex")).toBe(true);
    expect(modal.classList.contains("hidden")).toBe(false);
    expect(modalImg.src).toContain("/test-image.jpg");
  });

  it("closes the modal when the close button is clicked", () => {
    vi.useFakeTimers();
    try {
      const { modal } = setupModalDom();
      initImageModal();
      document.dispatchEvent(new Event("DOMContentLoaded"));

      document.querySelector("[data-image-modal-trigger]").click();
      expect(modal.classList.contains("flex")).toBe(true);

      document.querySelector("[data-image-modal-close]").click();
      vi.advanceTimersByTime(300);

      expect(modal.classList.contains("hidden")).toBe(true);
    } finally {
      vi.useRealTimers();
    }
  });
});
