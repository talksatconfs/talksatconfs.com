<?php

use PhpCsFixer\Finder;

$finder = Finder::create()
    ->notPath('vendor')
    ->in('app')
    ->in('domain')
    ->in('config')
    ->in('tests')
    ->name('*.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return Codeat3\styles($finder);
