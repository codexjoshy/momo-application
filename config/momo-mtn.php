<?php

    return [

        'base_url' => env('MOMO_BASE_URL', 'https://sandbox.momodeveloper.mtn.com'),

        'user_id' => env('MOMO_USER_ID'),

        'disbursement_api_key' => env('MOMO_DISBURSEMENT_API_KEY'),

        'disbursement_api_secret' => env('MOMO_DISBURSEMENT_API_SECRET'),

        'callback_url' => env('MOMO_CALLBACK_URL', 'http://120.0.0.1:8001'),

    ];

?>
