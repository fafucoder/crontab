<?php
return PhpCsFixer\Config::create()
    ->setRules([
        '@PSR2' => true,
        'strict_param' => true,
        'array_syntax' => ['syntax' => 'short'],
        'blank_line_after_namespace' => true,
        'dir_constant' => false,
        'no_php4_constructor' => true,
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->exclude('src')
            ->in(__DIR__)
    )
;