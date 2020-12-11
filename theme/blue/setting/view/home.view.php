<?php

return [
    'key' => 'home',
    'label' => '{setting.home}',
    'icon' => 'fa fa-home',
    'sort' => 0,
    'settings' => [
        [
            'key' => 'color',
            'label' => 'انتخاب رنگ',
            'type' => 'color-picker',
            'value' => '#454545',
        ],
        [
            'key' => 'menu',
            'label' => 'لیست',
            'type' => 'list',
            'settings' => [
                [
                    'key' => 'color',
                    'label' => 'انتخاب رنگ',
                    'type' => 'color-picker',
                    'value' => '#454545',
                ],
                [
                    'key' => 'link',
                    'label' => 'لینک',
                    'type' => 'text',
                    'value' => 'link url',
                ],
            ]
        ],
        [
            'key' => 'posts',
            'label' => '{setting.select_post}',
            'type' => 'select:post',
            'attrs' => [
                'limit' => 5,
            ]
        ],
    ]
];