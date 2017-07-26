<?php

namespace Baboot\Cart;

use Baboot\Exception\CartIsNotAddableItemException;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Item
 */
class Item implements Arrayable, Jsonable
{
    /**
     * @var integer|float
     */
    protected $price;

    /**
     * @var int
     */
    protected $quantity;


    /**
     * @var integer
     */
    private $id;

    /**
     * Item constructor.
     * @param $model
     * @param int $q
     */
    public function __construct($model, $q = 1)
    {
        if ( ! $this->isCartable($model)) throw new CartIsNotAddableItemException();
        $this->id    = method_exists($model, 'cartItemId') ? $model->cartItemId() : $model->id;
        $this->price = method_exists($model, 'cartItemPrice') ? $model->cartItemPrice() : $model->price;
        $this->quantity = $q;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }


    /**
     * @param $model
     * @return bool
     */
    private function isCartable($model)
    {
        return is_object($model)
            && (method_exists($model, 'cartItemPrice') || property_exists($model, 'price'))
            && (method_exists($model, 'cartItemId') || property_exists($model, 'id'));
    }

    /**
     * @return float|int
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float|int $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    public function incrementQuantity(){
        $this->quantity++;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'price' => $this->getPrice(),
            'quantity' => $this->getQuantity()
        ];
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray());
    }
}