<?php

return [
    'key' => 'write',
    'label' => rlang('setting.write'),
    'icon' => 'fa fa-keyboard',
    'sort' => 1,
    'settings' => [
        [
            'key' => 'autosave',
            'value' => true,
            'label' => rlang('setting.auto_save'),
            'type' => 'toggle',
            'help' => rlang('setting.help_auto_save'),
        ],
        [
            'key' => 'autosave_time',
            'value' => 5,
            'hiddenBy' => 'autosave',
            'label' => rlang('setting.auto_save_time'),
            'type' => 'number',
            'help' => rlang('setting.help_auto_save_time'),
            'attrs' => [
                'placeholder' => rlang('setting.enter_auto_save_time'),
            ]
        ],
    ]
];