<?php

Use pinoox\component\Lang;

return [
    'key' => 'lang',
    'label' => rlang('setting.language'),
    'icon' => 'fa fa-language',
    'sort' => 2,
    'settings' => [
        [
            'key' => 'lang',
            'value' => Lang::current(),
            'label' => rlang('setting.select_lang'),
            'type' => 'select',
            'options' => rlang('setting.lang'),
            'attrs' => [
                'clearable' => false,
            ],
        ],
    ]
];