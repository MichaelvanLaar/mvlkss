/* =============================================================================
 * Image modal (i. e. lightbox)
 *
 * Basic details:
 * https://www.kindacode.com/article/tailwind-css-how-to-create-image-modals-image-lightboxes/
 *
 * This version of the code is enhanced with some improvements.
 * =============================================================================
 */

// Must match the transition duration set in the Kirby page footer snippet.
const CSS_TRANSITION_DURATION = 300;

const MODAL_ID = "image-modal";
const MODAL_IMG_ID = "image-modal-img";
const MODAL_LOADER_ID = "image-modal-loader";

/**
 * Opens the modal and starts the opacity transition.
 */
export function showModal(modal, modalImg, loader, src) {
  loader.classList.remove("hidden");
  loader.classList.add("flex");
  modal.classList.remove("hidden");
  modal.classList.add("flex");

  // Force a reflow so the opacity transition applies when unhiding
  void modal.offsetHeight;

  modal.classList.remove("opacity-0");
  modalImg.src = src;
}

/**
 * Starts the close transition; completes the hide after the transition ends.
 */
export function closeModal(
  modal,
  modalImg,
  loader,
  duration = CSS_TRANSITION_DURATION,
) {
  modal.classList.add("opacity-0");

  setTimeout(() => {
    modal.classList.remove("flex");
    modal.classList.add("hidden");
    modalImg.src = "";
    loader.classList.remove("flex");
    loader.classList.add("hidden");
  }, duration);
}

/**
 * Wires up trigger/close/background-click handlers. No-op if the modal
 * elements are absent from the document.
 */
export function initImageModal(doc = document) {
  const modal = doc.getElementById(MODAL_ID);
  const modalImg = doc.getElementById(MODAL_IMG_ID);
  const loader = doc.getElementById(MODAL_LOADER_ID);

  if (!modal || !modalImg || !loader) {
    return;
  }

  doc.addEventListener("DOMContentLoaded", () => {
    const modalTriggers = doc.querySelectorAll("[data-image-modal-trigger]");
    modalTriggers.forEach((trigger) => {
      trigger.addEventListener("click", (event) => {
        event.preventDefault();
        const imageUrl = trigger.getAttribute("data-image-modal-url");
        showModal(modal, modalImg, loader, imageUrl);
      });
    });

    const closeButtons = doc.querySelectorAll("[data-image-modal-close]");
    closeButtons.forEach((button) => {
      button.addEventListener("click", () => {
        closeModal(modal, modalImg, loader);
      });
    });

    modal.addEventListener("click", (event) => {
      if (event.target === modal) {
        closeModal(modal, modalImg, loader);
      }
    });
  });

  modalImg.addEventListener("load", () => {
    loader.classList.remove("flex");
    loader.classList.add("hidden");
  });
}

if (typeof document !== "undefined") {
  initImageModal();
}
