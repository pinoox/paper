<?php

return [
    'key' => 'contact',
    'label' => rlang('setting.contact'),
    'icon' => 'fa fa-headset',
    'sort' => 4,
    'settings' => [
        [
            'key' => 'tel',
            'label' => rlang('setting.tel'),
            'type' => 'tel',
        ],
        [
            'key' => 'email',
            'label' => rlang('setting.email'),
            'type' => 'email',
        ],
        [
            'key' => 'socials',
            'label' => rlang('setting.socials'),
            'type' => 'list',
            'attrs' => [
                'depth' => 1,
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
                    'label' => 'telegram',
                    'link' => '#',
                    'icon' => 'fab fa-telegram-plane',
                    'children' => []
                ],
                [
                    'label' => 'twitter',
                    'link' => '#',
                    'icon' => 'fab fa-twitter',
                    'children' => []
                ],
                [
                    'label' => 'instagram',
                    'link' => '#',
                    'icon' => 'fab fa-instagram',
                    'children' => []
                ],
            ],
        ],
        [
            'key' => 'address',
            'label' => rlang('setting.address'),
            'type' => 'textarea',
            'attrs' => [
                'rows' => 5,
            ],
        ],
    ]
];