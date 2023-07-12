<?php

return [
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
        'account_number' => env('SBER_ACCOUNT_NUMBER'),
        'auth_host' => 'https://edupir.testsbi.sberbank.ru:9443',
        'api_host' => 'https://edupirfintech.sberbank.ru:9443',
        'cert_path' => env('SBER_CERT_PATH'),
        'cert_pass' => env('SBER_CERT_PASS'),
    ],

    'stream-telecom' => [
        'login' => env('STREAM_TELECOM_LOGIN'),
        'password' => env('STREAM_TELECOM_PASSWORD'),
        'sadr' => env('STREAM_TELECOM_SADR', 'SMS Info'),
    ],

	'tochka' => [
		'token' => env('TOCHKA_BANK_TOKEN'),
		'mode' => env('TOCHKA_BANK_WORK_MODE'),
	],

	'planfact' => [
		'key' => env('PLANFACT_API_KEY'),
		'account_id' => env('PLANFACT_ACCOUNT_ID'),
		'income_category' => env('PLANFACT_INCOME_CATEGORY_ID'),
		'outcome_category' => env('PLANFACT_OUTCOME_CATEGORY_ID'),
	],
];
