<?php

namespace Baboot\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Cart
 */
class Cart extends Facade
{
    /**
     * @inheritdoc
     */
    protected static function getFacadeAccessor()
    {
        return 'cart';
    }
}