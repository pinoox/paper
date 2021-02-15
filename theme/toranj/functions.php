<?php

use pinoox\component\Dir;
use pinoox\component\File;
use pinoox\component\HelperString;

function setting_json($key)
{
    return HelperString::encodeJson(setting($key), true);
}

function chunkhash()
{
    $file = Dir::theme('dist/pinoox.js');
    return File::size($file);
}