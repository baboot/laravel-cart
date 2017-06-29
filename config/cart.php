<?php

/**
 * Config for cart
 */
return [
    'storage' => env('CART_STORAGE')?:'DB',
    'storageDrivers'=>[
        'DB'    => \Baboot\Storage\DB::class,
        'Redis' => \Baboot\\Storage\Redis::class,

    ]
];