<?php
/**
 * =============================================================================
 * XML Sitemap Template
 *
 * Uses variables from controller:
 * - $filteredPages (Kirby pages collection)
 * =============================================================================
 */

header("Content-type: application/xml; charset=utf-8");
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php foreach ($filteredPages as $page): ?>
  <url>
    <loc><?= $page->url() ?></loc>
    <?php if ($page->modified()): ?>
    <lastmod><?= $page->modified("Y-m-d") ?></lastmod>
    <?php endif; ?>
  </url>
<?php endforeach; ?>
</urlset>
