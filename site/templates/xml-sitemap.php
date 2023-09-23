<?php
/**
 * =============================================================================
 * Not Used Default Template for XML Sitemap Page
 * =============================================================================
 */

// Get the current URL
$currentURL = $page->url();

// Construct the .xml version of the current URL
$xmlURL = "{$currentURL}.xml";

// Redirect to the .xml version
go($xmlURL, 301);
