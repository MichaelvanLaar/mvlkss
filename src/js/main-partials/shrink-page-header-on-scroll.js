/**
 * =============================================================================
 * Shrink page header on scroll
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

  // All custom CSS properties must be defined in an inline style attribute of
  // the body element using “rem” values:
  // --site-header-initial-height
  // --site-header-scroll-height
  // --site-header-initial-padding-y
  // --site-header-scroll-padding-y

  const scrollThreshold = 50; // Start shrinking header after scrolling 50px
  const maxScrollDistance = 200; // Header reaches minimal size after scrolling 200px

  /**
   * ---------------------------------------------------------------------------
   * Get variables from CSS
   * ---------------------------------------------------------------------------
   */

  // Select the body element
  const body = document.body;

  // Retrieve the custom CSS property values from the body element’s computed
  // style.
  const initialHeaderHeight = parseFloat(
    getComputedStyle(body).getPropertyValue("--site-header-initial-height")
  );
  const scrollHeaderHeight = parseFloat(
    getComputedStyle(body).getPropertyValue("--site-header-scroll-height")
  );
  const initialHeaderPaddingY = parseFloat(
    getComputedStyle(body).getPropertyValue("--site-header-initial-padding-y")
  );
  const scrollHeaderPaddingY = parseFloat(
    getComputedStyle(body).getPropertyValue("--site-header-scroll-padding-y")
  );

  /**
   * ---------------------------------------------------------------------------
   * Event listener
   * ---------------------------------------------------------------------------
   */

  // Add a scroll event listener to the window. This function is called every
  // time the user scrolls.
  window.addEventListener("scroll", () => {
    // Get the current scroll position.
    const scrollPosition = window.scrollY;

    // Initialize the header’s height and vertical padding to their initial
    // values.
    let headerHeight = initialHeaderHeight;
    let headerPaddingY = initialHeaderPaddingY;

    // If the scroll position is greater than the scroll threshold, start
    // modifying the header’s height and vertical padding.
    if (scrollPosition > scrollThreshold) {
      // Calculate how far the user scrolled since passing the scroll threshold,
      // but don’t exceed the maximum scroll distance.
      const scrollDistance = Math.min(
        scrollPosition - scrollThreshold,
        maxScrollDistance
      );

      // Calculate the ratio of the scroll distance to the maximum scroll
      // distance.
      const scrollRatio = scrollDistance / maxScrollDistance;

      // Calculate the new values for the header’s height and vertical padding
      // by interpolating between their initial and scroll values based on the
      // scroll ratio.
      headerHeight =
        initialHeaderHeight -
        (initialHeaderHeight - scrollHeaderHeight) * scrollRatio;
      headerPaddingY =
        initialHeaderPaddingY -
        (initialHeaderPaddingY - scrollHeaderPaddingY) * scrollRatio;
    }

    // Update the custom CSS properties on the body element with the new values.
    body.style.setProperty("--site-header-height", `${headerHeight}rem`);
    body.style.setProperty("--site-header-padding-y", `${headerPaddingY}rem`);
  });
})();
