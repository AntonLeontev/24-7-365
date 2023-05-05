<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'yandex' => [
        'client_id' => env('YANDEX_CLIENT_ID'),
        'client_secret' => env('YANDEX_CLIENT_SECRET'),
        'redirect' => env('YANDEX_REDIRECT_URI'),
    ],

    'vkontakte' => [
        'client_id' => env('VKONTAKTE_CLIENT_ID'),
        'client_secret' => env('VKONTAKTE_CLIENT_SECRET'),
        'redirect' => env('VKONTAKTE_REDIRECT_URI'),
        'lang' => 'ru',
    ],
    
    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI')
    ],

    'sber' => [
        'scope' => env('SBER_SCOPE'),
        'client_id' => env('SBER_CLIENT_ID'),
        'client_secret' => env('SBER_CLIENT_SECRET'),
        'auth_host' => 'https://edupir.testsbi.sberbank.ru:9443',
        'api_host' => 'https://edupirfintech.sberbank.ru:9443',
		'cert_path' => env('SBER_CERT_PATH'),
		'cert_pass' => env('SBER_CERT_PASS'),
    ],

];
