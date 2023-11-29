/**
 * =============================================================================
 * Add offset to anchor scroll
 *
 * This is required to prevent the fixed header from overlapping the target
 * element.
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

  const pageHeaderSelectorId = "page-header";
  const offsetHeightFactor = 1;

  /**
   * ---------------------------------------------------------------------------
   * Main script
   * ---------------------------------------------------------------------------
   */

  /**
   * Scrolls the window directly to the specified element.
   * @param {HTMLElement} element - The element to scroll to.
   */
  function directScrollTo(element) {
    let header = document.getElementById(pageHeaderSelectorId);
    let additionalOffset = header.offsetHeight * offsetHeightFactor;
    let targetPosition = Math.max(element.offsetTop - additionalOffset, 0);

    // Directly set the scroll position without animation
    window.scrollTo(0, targetPosition);
  }

  /**
   * Smoothly scrolls to the specified element.
   *
   * @param {HTMLElement} element - The element to scroll to.
   * @param {string} href - The href value to update the URL in the address bar.
   */
  function smoothScrollTo(element, href) {
    let start = window.scrollY || document.documentElement.scrollTop;
    let target = element.offsetTop;
    let header = document.getElementById(pageHeaderSelectorId);
    let duration = 500; // Duration of the scroll in milliseconds
    let startTime = null;

    // Easing function for ease-in-out effect
    function easeInOutQuad(t) {
      return t < 0.5 ? 2 * t * t : -1 + (4 - 2 * t) * t;
    }

    function scrollStep(timestamp) {
      if (!startTime) startTime = timestamp;
      let progress = timestamp - startTime;
      let additionalOffset = header.offsetHeight * offsetHeightFactor;
      let targetPosition = Math.max(target - additionalOffset, 0);
      let distance = targetPosition - start;

      // Apply the easing function to the scroll calculation
      let scrollY = start + distance * easeInOutQuad(progress / duration);

      window.scrollTo(0, scrollY);

      if (progress < duration) {
        window.requestAnimationFrame(scrollStep);
      } else {
        window.scrollTo(0, targetPosition); // Ensure it ends exactly at the target position
      }
    }

    window.requestAnimationFrame(scrollStep);

    // After scrolling, update the hash history
    history.pushState({}, "", href); // Update the URL in the address bar
  }

  /**
   * Checks if the current page is a direct load with a hash in the URL.
   * @returns {boolean} True if the page is a direct load with a hash, false otherwise.
   */
  function isDirectLoadWithHash() {
    return (
      window.location.hash.length > 0 &&
      document.referrer.split("/")[2] !== window.location.hostname
    );
  }

  // Apply appropriate scrolling method based on context
  if (isDirectLoadWithHash()) {
    let targetElement = document.querySelector(window.location.hash);
    if (targetElement) {
      window.onload = () => directScrollTo(targetElement);
    }
  } else {
    // Intercept all clicks on links starting with “#”.
    const allAnchorLinks = document.querySelectorAll("a[href^='#']");
    allAnchorLinks.forEach((anchorLink) => {
      anchorLink.addEventListener("click", (event) => {
        event.preventDefault();
        let href = anchorLink.getAttribute("href");
        // Remove the '#' from the href for the name selector
        let name = href.substring(1);
        // Try selecting by ID first, then by name
        let targetElement =
          document.querySelector(href) ||
          document.querySelector(`a[name='${name}']`);
        if (targetElement) {
          smoothScrollTo(targetElement, href);
        }
      });
    });
  }
})();
