/**
 * =============================================================================
 * Add “rel='noopener'” to all links with “target='_blank'” for more security
 *
 * See https://mathiasbynens.github.io/rel-noopener/ for details
 * =============================================================================
 */

/**
 * Adds rel="noopener" to every <a target="_blank"> within the given root that
 * does not already have it.
 *
 * @param {ParentNode} [root=document] — scope in which to scan for links.
 */
export function secureExternalLinks(root = document) {
  const insecureLinks = root.querySelectorAll(
    "a[target='_blank']:not([rel='noopener'])",
  );

  for (const insecureLink of insecureLinks) {
    insecureLink.setAttribute("rel", "noopener");
  }
}

if (typeof document !== "undefined") {
  secureExternalLinks();
}
