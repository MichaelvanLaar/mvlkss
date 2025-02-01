/**
 * =============================================================================
 * Prefill the Kirby Page Cache
 *
 * This script fetches URLs from XML sitemaps and visits each URL using
 * Playwright with different viewports. This results in the visited pages being
 * added to Kirby’s page cache, as well as any server-side generated images
 * being generated in different sizes depending on the viewports (due to a
 * responsive image implementation).
 *
 * The following configuration options need to be set:
 * - websiteRoot
 * - sitemapPaths
 * - viewports (because of responsive images – usually no changes are required)
 * =============================================================================
 */

const { chromium } = require("playwright");
const axios = require("axios");
const xml2js = require("xml2js");
const fs = require("fs").promises;

/**
 * -----------------------------------------------------------------------------
 * Configuration
 * -----------------------------------------------------------------------------
 */

/**
 * The root address of the website, including the protocol.
 * (E.g. https://mywebsite.com/ or https://mydomain.com/myprojectsite/)
 * The URL may not end with a file name and should have a TRAILING SLASH.
 * @type {string}
 */
const websiteRoot = "https://mywebsite.com/";

/**
 * Array of sitemap Paths WITHOUT LEADING SLASHES.
 * @type {string[]}
 */
const sitemapPaths = ["de/sitemap.xml", "en/sitemap.xml"];

/**
 * Array of objects representing different viewports.
 * @type {Array<{ width: number, height: number }>}
 */
const viewports = [
  { width: 360, height: 760 },
  { width: 1024, height: 700 },
  { width: 1280, height: 650 },
  { width: 1920, height: 850 },
];

/**
 * -----------------------------------------------------------------------------
 * Helper functions
 * -----------------------------------------------------------------------------
 */

/**
 * Fetches the URLs from one or more sitemaps using the provided sitemap URLs.
 *
 * @param {string} sitemapUrl - The URL of the sitemap.
 * @returns {Promise<Array<string>>} - A promise that resolves to an array of URLs from the sitemap.
 */
async function fetchSitemapUrls(sitemapUrl) {
  try {
    // Fetch the sitemap using axios
    const response = await axios.get(sitemapUrl);
    const content = response.data;

    const parser = new xml2js.Parser({
      explicitArray: false,
      tagNameProcessors: [xml2js.processors.stripPrefix],
    });

    const result = await parser.parseStringPromise(content);

    if (!result.urlset || !result.urlset.url) {
      console.error(
        `Sitemap at ${sitemapUrl} does not contain expected urlset.url structure.`,
      );
      return [];
    }

    // Ensure that we always have an array of URLs
    const urls = Array.isArray(result.urlset.url)
      ? result.urlset.url
      : [result.urlset.url];
    return urls.map((urlEntry) => urlEntry.loc);
  } catch (error) {
    console.error(
      `Error fetching or parsing sitemap ${sitemapUrl}:`,
      error.message,
    );
    return [];
  }
}

/**
 * Delays the execution for a random amount of time between min and max (in
 * milliseconds).
 *
 * @param {number} min - The minimum delay time.
 * @param {number} max - The maximum delay time.
 * @returns {Promise<void>} - A promise that resolves after the delay.
 */
function delay(min, max) {
  // Calculate a random delay between min and max (in milliseconds)
  const time = Math.random() * (max - min) + min;
  return new Promise((resolve) => setTimeout(resolve, time));
}

/**
 * Crawls the given URLs using Playwright, with the specified viewport.
 *
 * @param {string[]} urls - The URLs to crawl.
 * @param {Object} viewport - The viewport size.
 * @returns {Promise<void>} - A promise that resolves when the crawling is complete.
 */
async function crawlUrls(urls, viewport) {
  const browser = await chromium.launch({ headless: true });
  const context = await browser.newContext({
    javaScriptEnabled: false,
  });

  const page = await context.newPage();
  await page.setViewportSize(viewport);

  for (const url of urls) {
    try {
      console.log(
        `Visiting ${url} with viewport ${viewport.width}x${viewport.height}`,
      );
      await page.goto(url, { waitUntil: "load", timeout: 30000 });
      // Random delay between 1.1 and 4.3 seconds
      await delay(1100, 4300);
    } catch (error) {
      console.error(`Error visiting ${url}:`, error.message);
    }
  }

  await browser.close();
}

/**
 * -----------------------------------------------------------------------------
 * Main function
 * -----------------------------------------------------------------------------
 */

/**
 * Main function that performs the caching prefill operation.
 *
 * @returns {Promise<void>} A promise that resolves when the operation is completed.
 */
async function main() {
  try {
    const path = require("path");

    // Add a slash to the end of the website root address in case it is missing.
    if (!websiteRoot.endsWith("/")) {
      websiteRoot += "/";
    }

    // Check all sitemap paths for leading slashes and delete them if present.
    for (let i = 0; i < sitemapPaths.length; i++) {
      if (sitemapPaths[i].startsWith("/")) {
        sitemapPaths[i] = sitemapPaths[i].substring(1);
      }
    }

    // Construct sitemap URLs
    const sitemapUrls = sitemapPaths.map((path) => `${websiteRoot}${path}`);

    let allUrls = [];
    for (const sitemap of sitemapUrls) {
      const urls = await fetchSitemapUrls(sitemap);
      allUrls = allUrls.concat(urls);
    }

    for (const viewport of viewports) {
      await crawlUrls(allUrls, viewport);
    }
  } catch (error) {
    console.error("An error occurred in the main function:", error);
    process.exit(1); // Exit with a non-zero status code to indicate failure
  }
}

main();
