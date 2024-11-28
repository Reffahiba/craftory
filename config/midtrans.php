<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Midtrans Server Key
    |--------------------------------------------------------------------------
    |
    | This is your server key from Midtrans. You need to set this for payment
    | processing to work. You can get it from your Midtrans account dashboard.
    |
    */
    'server_key' => env('MIDTRANS_SERVER_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Midtrans Client Key
    |--------------------------------------------------------------------------
    |
    | This is your client key from Midtrans, which is used to initialize the
    | payment process on the front-end.
    |
    */
    'client_key' => env('MIDTRANS_CLIENT_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Production Environment
    |--------------------------------------------------------------------------
    |
    | Set this to true when you are ready to go live. For testing purposes,
    | this should be false.
    |
    */
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),

    /*
    |--------------------------------------------------------------------------
    | Enable or disable 3D Secure
    |--------------------------------------------------------------------------
    |
    | Set to true if you want to use 3D Secure for credit card payments.
    |
    */
    'is_3ds' => true,

    /*
    |--------------------------------------------------------------------------
    | Payment Type Configuration
    |--------------------------------------------------------------------------
    |
    | Here, you can specify which payment types you want to accept.
    |
    */
    'payment_type' => 'credit_card',  // Can be credit_card, bank_transfer, etc.
];
