<?php

namespace Baboot;

use Baboot\Cart\CartCollection;
use Baboot\Cart\Coupon;
use Baboot\Cart\CouponsCollection;
use Baboot\Cart\Item;
use Baboot\Contracts\Storage;
use Baboot\Exception\CartIsNotInitedException;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

/**
 * Class Cart
 */
class Cart implements Arrayable, Jsonable
{
    /**
     * Config described in file
     *
     * @var array
     */
    protected $config;

    /**
     * @var Storage
     */
    protected $storage;

    /**
     * @var CartCollection
     */
    protected $collection;

    /**
     * @var Dispatcher
     */
    private $eventsDispathcer;

    /**
     * @var
     */
    private $key;

    /**
     * @var CouponsCollection
     */
    private $coupons;

    /**
     * Cart constructor.
     * @param Repository $config
     */
    public function __construct(Repository $config, Storage $storage, Dispatcher $dispatcher)
    {
        $this->config  = $config->get('cart');
        $this->storage = $storage;
        $this->eventsDispathcer  = $dispatcher;
    }

    /**
     * @param $key
     */
    public function init($key){
        $this->key = $key;
        $data = $this->storage->get($key);

        $this->collection = new CartCollection(
            (is_object($data) && property_exists(@$data, 'items')) ? @$data->items : []
        );

        $this->coupons = new CouponsCollection(
            (is_object($data) && property_exists(@$data, 'items')) ? @$data->coupons : []
        );

        $this->triggerEvent('inited');
    }

    /**
     * @return array
     */
    public function items()
    {
        return $this->getCollection();
    }


    private function coupons()
    {
        return $this->coupons;
    }

    /**
     * @param $value
     * @param $type
     */
    public function applyCoupon($value, $type){
        $this->coupons()->push(new Coupon([
            'type'  => $type,
            'value' => $value
        ]));
    }

    /**
     * @param $model
     */
    public function add($model)
    {
        $item  = new Item($model);

        if ($this->getCollection()->has($item->getId())) {
            $item = $this->getCollection()->get($item->getId());
            $item->incrementQuantity();
        }

        $this->collection->putItem($item);

        $this->triggerEvent('action.added', $item);
    }

    /**
     * Remove item from cart
     *
     * @param $model
     */
    public function remove($model)
    {
        $item = new Item($model);
        if ($this->getCollection()->has($item->getId())) {
            $this->getCollection()->forget($item->getId());
        }
        $this->triggerEvent('action.removed', $item);
    }

    /**
     * Total quantity of allItems in cart
     *
     * @return mixed
     */
    public function totalQuantity()
    {
        return $this->getCollection()->sum(function(Item $el){
            return $el->getQuantity();
        });
    }

    public function subTotal()
    {
        return $this->getCollection()->sum(function(Item $el){
            return $el->getQuantity() * $el->getPrice();
        });
    }

    /**
     * Reset
     */
    public function flush(){
        $this->collection = new CartCollection();
        $this->coupons    = new CouponsCollection();
        $this->triggerEvent('action.flush');
    }

    /**
     * Get total amount. Applying all coupons on a subtotal
     *
     * @return mixed
     */
    public function total()
    {
        $total = $this->subTotal();
        foreach ($this->coupons() as $coupon) $total = $coupon->applyTo($total);
        return $total;
    }

    /**
     * Getter collection.
     *
     * @return CartCollection
     * @throws CartIsNotInitedException
     */
    private function getCollection()
    {
        if (is_null($this->collection) ) throw new CartIsNotInitedException();
        return $this->collection;
    }

    /**
     * Trigger event on laravel event bus
     *
     * @param $name
     * @param $data
     */
    public function triggerEvent($name, $data = null){
        $this->eventsDispathcer->fire('cart.' . $name, $data);
    }

    /**
     * Getter for a cart primary key
     *
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'items' => $this->items(),
            'coupons' => $this->coupons()
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