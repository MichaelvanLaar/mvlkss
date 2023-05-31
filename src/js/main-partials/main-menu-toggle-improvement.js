/**
 * =============================================================================
 * Main menu toggle improvement (for screenreader version)
 * =============================================================================
 */

// Wrap the entire code inside an Immediately Invoked Function Expression
// (IIFE). This will prevent any variables or functions defined inside from
// polluting the global scope.
(function () {
  // Get the main menu state input element (checkbox) using its ID
  const mainMenuState = document.querySelector("#main-menu-state");

  // Define a function that will handle the event listener callback.
  function toggleMenuState(event, state) {
    // Prevent the default behavior of the click event.
    event.preventDefault();
    // Set the “bchecked” state of the checkbox to the given state.
    // This will either check (state = true) or uncheck (state = false) the
    // checkbox which is used to toggle the main menu dropdown on mobile.
    mainMenuState.checked = state;
  }

  // Select the element that opens the main menu and add an event listener.
  // The event listener will call the toggleMenuState function with true as the
  // state when the element is clicked.
  document
    .querySelector("a.main-menu-open")
    .addEventListener("click", (e) => toggleMenuState(e, true));

  // Select the element that closes the main menu and add an event listener.
  // The event listener will call the toggleMenuState function with false as the
  // state when the element is clicked.
  document
    .querySelector("a.main-menu-close")
    .addEventListener("click", (e) => toggleMenuState(e, false));
})();
