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
            'value' => '#682323',
        ],
        [
            'key' => 'posts',
            'label' => '{setting.select_post}',
            'type' => 'select:post',
        ],
    ]
];