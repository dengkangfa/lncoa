<?php

return [

    //默认头像
    'default_avatar' => env('DEFAULT_AVATAR') ?: '/images/default.png',

    'default_icon' => env('DEFAULT_ICON') ?: '/images/favicon.ico',

    'k780' => [
        'appkey' => env('K780_APPKEY') ?: 'k780-appkey',
        'sign'  => env('K780_SIGN') ?: 'k780-ign',
        'weaid' => env('K780_WEAID') ?: '清远'
    ],
];
