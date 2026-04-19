<?php

use Kirby\Cms\Block;
use Kirby\Toolkit\Dom;

return [
    "kirbytext:after" => function (string|null $text) {
        if ($text === null || $text === "") {
            return $text;
        }

        // Create a new DOM object from the input text
        $dom = new Dom($text);

        // Get all <pre> elements from the DOM
        $preNodes = $dom->document()->getElementsByTagName("pre");

        // If no <pre> elements are found, return the original text
        if ($preNodes->length === 0) {
            return $text;
        }

        $modified = false;

        // Iterate over each <pre> element
        foreach ($preNodes as $preNode) {
            // Skip <pre> elements that either are already highlighted or have more than one child node
            if (
                $preNode->childNodes->length !== 1 ||
                $preNode->hasAttribute("data-language")
            ) {
                continue;
            }

            // Get the <code> node inside the <pre> element
            $codeNode = $preNode->firstChild;

            // Skip bare <pre> nodes whose firstChild is not an element (e.g. DOMText) — getAttribute() would be fatal
            if (!($codeNode instanceof \DOMElement)) {
                continue;
            }

            // Get the raw code content from the <code> node
            $code = $codeNode->nodeValue;

            // Extract the language from the class attribute of the <code> node (e.g., 'language-php' -> 'php')
            $lang = $codeNode->getAttribute("class");
            $lang = str_starts_with($lang, "language-")
                ? substr($lang, 9)
                : "text";

            // Create a new Block object with the code content and language
            $block = new Block([
                "type" => "code",
                "content" => [
                    "code" => $code,
                    "language" => $lang,
                ],
            ]);

            // Render the block using the 'blocks/code' snippet
            $codeBlock = snippet("blocks/code", ["block" => $block], true);

            // Create a new DocumentFragment to hold the rendered code block
            $newNode = $dom->document()->createDocumentFragment();
            $result = $newNode->appendXML($codeBlock);

            // If appendXML failed, leave the original <pre> intact rather than replacing with an empty fragment
            if ($result === false) {
                error_log(
                    'code-highlighter: appendXML failed for language "' .
                        $lang .
                        '" — leaving original pre node',
                );
                continue;
            }

            // Replace the original <pre> node with the new code block in the DOM
            $preNode->parentNode->replaceChild($newNode, $preNode);
            $modified = true;
        }

        // Only serialize back if the DOM was actually changed
        if (!$modified) {
            return $text;
        }

        return $dom->toString();
    },
];
