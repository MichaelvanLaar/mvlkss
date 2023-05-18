<?php

return [
    "afbora.kirby-minify-html.enabled" => true,
    "cache" => [
        "pages" => [
            "active" => true,
        ],
    ],
    "debug" => false,
    "lukaskleinschmidt.resolve.cache" => true,
    "ready" => function ($kirby) {
        return [
            "isaactopo.xmlsitemap.ignore" => $kirby
                ->site()
                ->index()
                ->filterBy("seoIndex", "false")
                ->pluck("uri"),
            "isaactopo.xmlsitemap.includeImages" => true,
        ];
    },
];
