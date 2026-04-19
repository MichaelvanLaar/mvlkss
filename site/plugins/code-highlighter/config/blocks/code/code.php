<?php

/** @var \Kirby\Cms\Block $block */

use Phiki\Exceptions\UnrecognisedGrammarException;
use Phiki\Exceptions\UnrecognisedThemeException;

$highlighter = new \Phiki\Phiki();

// Extract block values and options (cast to string — Field objects coerce via __toString but strict_types would break)
$code   = (string)$block->code()->fromBase64();
$lang   = (string)$block->language()->or(option('bogdancondorachi.code-highlighter.language'));
$theme  = (string)$block->theme()->or(option('bogdancondorachi.code-highlighter.theme'));
$themes = option('bogdancondorachi.code-highlighter.themes');
$gutter = option('bogdancondorachi.code-highlighter.gutter');

// Validate themes format — degrade gracefully instead of throwing from inside a snippet
if (!is_null($themes) && (!is_array($themes) || array_values($themes) === $themes)) {
  error_log('code-highlighter: bogdancondorachi.code-highlighter.themes must be an associative array — ignoring');
  $themes = null;
}

// Determine the effective theme
$effectiveTheme = $themes && is_array($themes)
  ? ($block->theme()->isNotEmpty() ? $theme : $themes)
  : $theme;

// Generate highlighted code — fall back to plain escaped output on unknown language or theme
try {
  $highlightedCode = $highlighter->codeToHtml($code, $lang, $effectiveTheme, $gutter);
} catch (UnrecognisedGrammarException $e) {
  error_log('code-highlighter: unrecognised language "' . $lang . '" — ' . $e->getMessage());
  $highlightedCode = '<pre class="phiki"><code>' . htmlspecialchars($code) . '</code></pre>';
} catch (UnrecognisedThemeException $e) {
  error_log('code-highlighter: unrecognised theme "' . $effectiveTheme . '" — ' . $e->getMessage());
  $highlightedCode = '<pre class="phiki"><code>' . htmlspecialchars($code) . '</code></pre>';
}

?>

<?= $highlightedCode ?>
