/**
 * =============================================================================
 * Main menu toggle improvement (for screenreader version)
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

  const MENU_STATE_SELECTOR = "#main-menu-state";
  const MENU_OPEN_SELECTOR = "a.main-menu-open";
  const MENU_CLOSE_SELECTOR = "a.main-menu-close";

  // Get the main menu state input element (checkbox) using its ID
  const mainMenuState = document.querySelector(MENU_STATE_SELECTOR);

  // Check if mainMenuState exists in the DOM, otherwise return.
  if (!mainMenuState) {
    return;
  }

  /**
   * ---------------------------------------------------------------------------
   * Helper functions
   * ---------------------------------------------------------------------------
   */

  // Define the function that will handle the event listener callback.
  const toggleMenuState = (event, state = true) => {
    // Prevent the default behavior of the click event.
    event.preventDefault();
    // Set the “checked” state of the checkbox to the given state.
    // This will either check (state = true) or uncheck (state = false) the
    // checkbox which is used to toggle the main menu dropdown on mobile.
    mainMenuState.checked = state;
  };

  // Function to add event listeners to elements if they exist.
  const addEventListenerIfElementExists = (selector, eventType, callback) => {
    const element = document.querySelector(selector);
    if (element) {
      element.addEventListener(eventType, callback);
    }
  };

  /**
   * ---------------------------------------------------------------------------
   * Main script
   * ---------------------------------------------------------------------------
   */

  // Add event listener for opening the menu.
  addEventListenerIfElementExists(MENU_OPEN_SELECTOR, "click", (e) =>
    toggleMenuState(e, true),
  );

  // Add event listener for closing the menu.
  addEventListenerIfElementExists(MENU_CLOSE_SELECTOR, "click", (e) =>
    toggleMenuState(e, false),
  );
})();
