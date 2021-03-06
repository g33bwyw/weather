<?php

/*
 * This file is part of the wyw/weather.
 *
 * (c) wangyawei <wangyw@boqii.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

$header = <<<EOF
This file is part of the wyw/weather.

(c) wangyawei <wangyw@boqii.com>

This source file is subject to the MIT license that is bundled.
EOF;

return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        'header_comment' => ['header' => $header],
        'array_syntax' => ['syntax' => 'short'],
        'ordered_imports' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'php_unit_construct' => true,
        'php_unit_strict' => true,
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->exclude('vendor')
            ->in(__DIR__)
    )
;
