<?php

return [
    'cache'        => [
        'enabled'   => true,
        'directory' => __DIR__ . '/../cache'
    ],
    'namespaces'   => [
        'default' => [__DIR__ . '/../fixtures/default'],
        'other'   => [__DIR__ . '/../fixtures/other'],
    ],
    'dependencies' => [],
    'engines'      => []
];