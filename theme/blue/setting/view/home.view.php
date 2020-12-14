<?php

return [
    'key' => 'home',
    'label' => '{setting.home}',
    'icon' => 'fa fa-home',
    'sort' => 0,
    'settings' => [
        [
            'key' => 'posts',
            'label' => '{setting.select_post}',
            'type' => 'select:post',
            'attrs' => [
                'limit' => 8,
            ]
        ],
    ]
];