<?php

declare(strict_types=1);

return [
    'cache' => [
        'enabled' => false,
        'directory' => __DIR__ . '/../runtime/cache',
    ],
    'namespaces' => [
        'default' => [__DIR__ . '/../views/default'],
        'other' => [__DIR__ . '/../views/other'],
        'extensions' => [__DIR__ . '/../views/other/extensions'],
    ],
    'dependencies' => [],
    'engines' => [],
];
