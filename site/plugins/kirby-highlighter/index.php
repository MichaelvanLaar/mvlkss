<?php

@include_once __DIR__ . '/vendor/autoload.php';

\Kirby\Cms\App::plugin('johannschopplich/highlighter', [
    'hooks' => require __DIR__ . '/extensions/hooks.php',
    'snippets' => [
        'blocks/code' => __DIR__ . '/snippets/blocks/code.php'
    ]
]);
