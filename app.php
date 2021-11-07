<?php
//pinoox app file, generated at "2020-12-07 15:04"

return array(
    'package-name' => 'com_pinoox_paper',
    'name' => 'paper',
    'description' => 'paper is an application for launching magazine, blog, and content websites',
    'icon' => 'icon.png',
    'version-code' => 12,
    'version-name' => '2.6.0',
    'developer' => 'pinoox',
    'enable' => true,
    'theme' => 'blue',
    'theme-panel' => 'papyrus',
    'lang' => 'fa',
    'service' => [
        'user',
        'permission',
        'schedule',
    ],
    'loader' => [
        '@func' => 'func.php',
    ]
);

//end of app