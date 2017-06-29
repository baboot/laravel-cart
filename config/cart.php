<?php

/**
 * Config for cart
 */
return [
    'storage' => env('CART_STORAGE')?:'DB',
    'storageDrivers'=>[
        'DB'    => \App\Package\src\Storage\DB::class,
        'Redis' => \App\Package\src\Storage\Redis::class,

    ]
];