<?php

return [
    'key' => 'general',
    'label' => rlang('setting.developer'),
    'icon' => 'fa fa-laptop-code',
    'sort' => 10,
    'settings' => [
        [
            'key' => 'hook',
            'label' => rlang('setting.web_hook'),
            'type' => 'form',
            'settings' => [
                [
                    'key' => 'address',
                    'label' => rlang('setting.address'),
                    'type' => 'text',
                ],
                [
                    'key' => 'token',
                    'type' => 'text',
                    'label' => rlang('setting.access_key'),
                ],
            ],
            'value' => [
                'address' => '',
                'key' => 'pinoox_adasFJAGdnquin32e4893',
            ],
        ],
        [
            'key' => 'ftp',
            'label' => rlang('setting.ftp'),
            'type' => 'form',
            'settings' => [
                [
                    'key' => 'address',
                    'label' => rlang('setting.address'),
                    'type' => 'text',
                ],
                [
                    'key' => 'host',
                    'type' => 'text',
                    'label' => rlang('setting.ftp_host'),
                ],
                [
                    'key' => 'username',
                    'type' => 'text',
                    'label' => rlang('setting.username'),
                ],
                [
                    'key' => 'password',
                    'type' => 'text',
                    'label' => rlang('setting.password'),
                ],
            ],
            'value' => [
                'address' => '',
                'host' => '',
                'username' => '',
                'password' => '',
            ],
        ],
        [
            'key' => 'header_code',
            'label' => rlang('setting.header_code'),
            'type' => 'textarea',
            'attrs' => [
                'rows' => 8,
                'class' => 'ltr-text',
                'placeholder' => ' ',
            ],
        ],
        [
            'key' => 'footer_code',
            'label' => rlang('setting.footer_code'),
            'type' => 'textarea',
            'attrs' => [
                'rows' => 8,
                'class' => 'ltr-text',
                'placeholder' => ' ',
            ],
        ],
    ]
];