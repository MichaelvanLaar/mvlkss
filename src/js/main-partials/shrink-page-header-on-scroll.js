/**
 * =============================================================================
 * Shrink page header on scroll
 * =============================================================================
 */

(function () {
  /**
   * ---------------------------------------------------------------------------
   * Configuration
   * ---------------------------------------------------------------------------
   */

  // All custom CSS properties must be defined in an inline style attribute of
  // the body element:
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

  const body = document.body;

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

  window.addEventListener("scroll", () => {
    const scrollPosition = window.pageYOffset;

    let headerHeight = initialHeaderHeight;
    let headerPaddingY = initialHeaderPaddingY;

    if (scrollPosition > scrollThreshold) {
      const scrollDistance = Math.min(
        scrollPosition - scrollThreshold,
        maxScrollDistance
      );

      const scrollRatio = scrollDistance / maxScrollDistance;

      headerHeight =
        initialHeaderHeight -
        (initialHeaderHeight - scrollHeaderHeight) * scrollRatio;
      headerPaddingY =
        initialHeaderPaddingY -
        (initialHeaderPaddingY - scrollHeaderPaddingY) * scrollRatio;
    }

    body.style.setProperty("--site-header-height", `${headerHeight}rem`);
    body.style.setProperty("--site-header-padding-y", `${headerPaddingY}rem`);
  });
})();
