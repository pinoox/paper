<?php

return [
    'key' => 'general',
    'label' => rlang('setting.developer'),
    'icon' => 'fa fa-laptop-code',
    'sort' => 10,
    'settings' => [
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