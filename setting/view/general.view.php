<?php

return [
    'key' => 'general',
    'label' => rlang('setting.general'),
    'icon' => 'fa fa-cog',
    'sort' => 0,
    'settings' => [
        [
            'key' => 'site_logo',
            'value' => 'icon.png',
            'label' => rlang('setting.site_logo'),
            'type' => 'image',
        ],
        [
            'key' => 'menu',
            'label' => rlang('setting.main_menu'),
            'type' => 'list',
            'attrs' => [
                'depth' => 3,
            ],
            'settings' => [
                [
                    'key' => 'link',
                    'label' => rlang('setting.link'),
                    'type' => 'text',
                ],
                [
                    'key' => 'icon',
                    'type' => 'text',
                    'label' => rlang('setting.font_icon'),
                ],
                [
                    'key' => 'image',
                    'label' => rlang('setting.image'),
                    'type' => 'image',
                ],
            ],
            'value' => [
                [
                    'label' => rlang('setting.home_menu'),
                    'link' => '',
                    'icon' => 'fa fa-home',
                    'children' => []
                ],
                [
                    'label' => rlang('setting.about_menu'),
                    'link' => 'about',
                    'icon' => 'fa fa-info',
                    'children' => []
                ],
                [
                    'label' => rlang('setting.contact_menu'),
                    'link' => 'contact',
                    'icon' => 'fa fa-phone',
                    'children' => []
                ],
            ],
        ],
        [
            'key' => 'site_title',
            'value' => rlang('setting.paper_title'),
            'label' => rlang('setting.site_title'),
            'type' => 'text',
        ],
        [
            'key' => 'site_subtitle',
            'value' => '',
            'label' => rlang('setting.site_subtitle'),
            'type' => 'text',
        ],
        [
            'key' => 'site_description',
            'value' => 'blog app',
            'label' => rlang('setting.site_description'),
            'type' => 'textarea',
            'attrs' => [
                'rows' => 5,
            ],
        ],
    ]
];