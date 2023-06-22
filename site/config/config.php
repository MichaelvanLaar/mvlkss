<?php

return [
    "afbora.kirby-minify-html.enabled" => true,
    "cache" => [
        "pages" => [
            "active" => true,
        ],
    ],
    "debug" => false,
    "distantnative.retour.logs" => false,
    "languages" => true,
    "lukaskleinschmidt.resolve.cache" => true,
    "ready" => function ($kirby) {
        return [
            "bnomei.robots-txt.content" =>
                "# https://www.robotstxt.org/\n\n# Allow crawling of all content\nUser-agent: *\nDisallow:\n\nSitemap: " .
                $kirby->site()->url() .
                "/sitemap.xml",
            "bnomei.robots-txt.groups" => null,
            "bnomei.robots-txt.sitemap" => null,
            "isaactopo.xmlsitemap.ignore" => $kirby
                ->site()
                ->index()
                ->filterBy("seoIndex", "false")
                ->pluck("uri"),
            "isaactopo.xmlsitemap.includeImages" => true,
        ];
    },
    "thumbs" => [
        "driver" => "im",
        "interlace" => true,
    ],
];
