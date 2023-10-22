/**
 * =============================================================================
 * Add “rel='noopener'” to all links with “target='_blank'” for more security
 *
 * See https://mathiasbynens.github.io/rel-noopener/ for details
 * =============================================================================
 */

// Wrap the entire code inside an Immediately Invoked Function Expression
// (IIFE). This will prevent any variables or functions defined inside from
// polluting the global scope.
(() => {
  // Select all anchor tags (<a>) that have the attribute target="_blank" but do
  // not have the attribute rel="noopener".
  const insecureLinks = document.querySelectorAll(
    "a[target='_blank']:not([rel='noopener'])"
  );

  // Iterate over each insecure link
  for (let insecureLink of insecureLinks) {
    // Add the attribute rel="noopener" to each insecure link.
    insecureLink.setAttribute("rel", "noopener");
  }
})();
