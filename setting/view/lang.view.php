<?php

return [
    'key' => 'lang',
    'label' => rlang('setting.language'),
    'icon' => 'fa fa-language',
    'sort' => 2,
    'settings' => [
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