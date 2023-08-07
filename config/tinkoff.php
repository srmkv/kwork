<?php

return [
    'terminal_key' => env('API_TERMINAL_KEY_TINKOFF'),
    'secret_key' => env('API_SECRET_KEY_TINKOFF'),
    'shop_id' => env('API_SHOP_ID_TINKOFF'),
    'showcase_id' => env('API_SHOWCASE_ID_TINKOFF'),
    
    'api_url' => 'https://securepay.tinkoff.ru/v2/',
    'api_installment_form' => 'https://forma.tinkoff.ru/api/partners/v2/orders/create-demo'
];
