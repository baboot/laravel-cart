<?php

namespace Baboot\Mixins;

/**
 * Class Cartable
 */
trait Cartable
{
    /**
     * @return mixed
     */
    public function cartItemPrice(){
        return $this->{$this->cart_price_arrtibute};
    }


    /**
     * @return mixed
     */
    public function cartItemId(){
        return $this->{$this->cart_item_id};
    }
}