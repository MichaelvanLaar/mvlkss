<?php
/**
 * =============================================================================
 * XML Sitemap Template
 * =============================================================================
 */

// Set the appropriate content type
header("Content-type: application/xml; charset=utf-8");

// Echo out the XML declaration
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";

/**
 * -----------------------------------------------------------------------------
 * Filter pages for the sitemap
 * -----------------------------------------------------------------------------
 */
function filterPagesForSitemap($site) {
  $pages = $site->pages()->filter(function ($page) use ($site) {
    // Always include the homepage
    if ($page->isHomePage()) {
      return true;
    }

    // Exclude unlisted pages, but not the homepage
    if (!$page->isListed()) {
      return false;
    }

    // Exclude pages with template "custom-menu-item"
    if ($page->intendedTemplate() == "custom-menu-item") {
      return false;
    }

    // Exclude pages with the "seoIndex" field set to "false"
    if ($page->seoIndex()->exists() && $page->seoIndex()->toBool() === false) {
      return false;
    }

    return true;
  });

  return $pages;
}
?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php
$filteredPages = filterPagesForSitemap($site);

foreach ($filteredPages as $page): ?>
  <url>
    <loc><?= $page->url() ?></loc>
    <lastmod><?= $page->modified("c") ?></lastmod>
  </url>
<?php endforeach;
?>
</urlset>
