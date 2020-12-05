<?php

return [
    'key' => 'home',
    'label' => '{setting.home}',
    'icon' => 'fa fa-cog',
    'sort' => 0,
    'settings' => [
        [
            'key' => 'posts',
            'value' => 'en',
            'label' => rlang('setting.site_title'),
            'type' => 'select:post',
            'options' => rlang('setting.lang'),
        ],
    ]
];