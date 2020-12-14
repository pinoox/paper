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