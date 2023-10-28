/* =============================================================================
 * Image modal (i. e. lightbox)
 *
 * Basic details:
 * https://www.kindacode.com/article/tailwind-css-how-to-create-image-modals-image-lightboxes/
 *
 * This version of the code is enhanced with some improvements.
 * =============================================================================
 */

// Wrap the entire code inside an Immediately Invoked Function Expression
// (IIFE). This will prevent any variables or functions defined inside from
// polluting the global scope.
(() => {
  /**
   * ---------------------------------------------------------------------------
   * Configuration
   * ---------------------------------------------------------------------------
   */

  const modalId = "image-modal";
  const modalImgId = "image-modal-img";
  const modalLoaderId = "image-modal-loader";

  // Duration of the CSS transition in milliseconds.
  // Must match the transition duration set in the Kirby page footer snippet.
  const cssTransitionDuration = 300;

  /**
   * ---------------------------------------------------------------------------
   * Main script
   * ---------------------------------------------------------------------------
   */

  // Get elements
  const modal = document.getElementById(modalId);
  const modalImg = document.getElementById(modalImgId);
  const loader = document.getElementById(modalLoaderId);

  // When the DOM is ready, attach an event listener to all elements that should
  // trigger the modal when clicked.
  document.addEventListener("DOMContentLoaded", (event) => {
    // Select all elements that should trigger the modal
    const modalTriggers = document.querySelectorAll(
      "[data-image-modal-trigger]",
    );

    // Attach a click event listener to each element
    modalTriggers.forEach((trigger) => {
      trigger.addEventListener("click", (event) => {
        // Prevent the default action (if there is one)
        event.preventDefault();

        // Get the image URL from the “data-image-modal-image” attribute
        const imageUrl = trigger.getAttribute("data-image-modal-url");

        // Call the showModal function
        showModal(imageUrl);
      });
    });

    // Similarly, for your closeModal function,
    // select the close button(s) and attach an event listener.
    const closeButtons = document.querySelectorAll("[data-image-modal-close]");
    closeButtons.forEach((button) => {
      button.addEventListener("click", (event) => {
        closeModal();
      });
    });

    // Close the modal if the user clicks outside of the image (i. e. on the
    // modal background)
    modal.addEventListener("click", (event) => {
      // Check if the clicked target is the modal background itself and not the
      // image
      if (event.target === modal) {
        // If true, close the modal
        closeModal();
      }
    });
  });

  // Hide loader when image is loaded
  modalImg.addEventListener("load", () => {
    loader.classList.remove("flex");
    loader.classList.add("hidden");
  });

  /**
   * ---------------------------------------------------------------------------
   * Helper functions
   * ---------------------------------------------------------------------------
   */

  // Function to open the modal when a thumbnail (or a link) is clicked
  function showModal(src) {
    loader.classList.remove("hidden"); // Show loader
    loader.classList.add("flex"); // Show loader
    modal.classList.remove("hidden"); // Show the modal
    modal.classList.add("flex"); // Show the modal

    // Force a reflow to make sure the opacity transition works when unhiding
    void modal.offsetHeight;

    modal.classList.remove("opacity-0"); // Start the transition to opacity 1
    modalImg.src = src; // Set the image source
  }

  // Function to close the modal when the close button is clicked
  function closeModal() {
    // Start the transition to opacity 0
    modal.classList.add("opacity-0");

    // Wait for the transition to finish, then hide the modal completely
    setTimeout(() => {
      modal.classList.remove("flex");
      modal.classList.add("hidden");
      modalImg.src = ""; // Clear the image source
      loader.classList.remove("flex"); // Hide the loader
      loader.classList.add("hidden"); // Hide the loader
    }, cssTransitionDuration);
  }
})();
