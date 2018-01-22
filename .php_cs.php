<?php
return PhpCsFixer\Config::create()
    ->setRules([
        '@PSR2' => true,
        '@Symfony' => true,
        'ordered_imports' => true,
        'phpdoc_order' => true,
        'array_syntax' => ['syntax' => 'short'],
    ]);