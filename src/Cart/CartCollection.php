<?php

namespace Baboot\Cart;

use Illuminate\Support\Collection;

/**
 * Class CartCollection
 * @package Baboot\Cart
 */
class CartCollection extends Collection{

    public function __construct($items)
    {
        parent::__construct();
        foreach ($items as $item)
            $this->putItem($item, $item->quantity);
    }

    /**
     * @param $item
     * @param int $q
     * @return $this
     */
    public function putItem($item, $q = 1){
        if( ! ($item instanceof Item) ){
            $item = new Item($item, $q);
        }

        return $this->put($item->getId(), $item);
    }

}