<?php

return [
    'key' => 'general',
    'label' => rlang('setting.general'),
    'icon' => 'fa fa-cog',
    'sort' => 0,
    'settings' => [
        [
            'key' => 'site_title',
            'value' => 'paper',
            'label' => rlang('setting.site_title'),
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
        [
            'key' => 'lang',
            'value' => 'en',
            'label' => rlang('setting.select_lang'),
            'type' => 'select',
            'options' => rlang('setting.lang'),
            'attrs' => [
                'clearable' => false,
            ],
        ],
    ]
];