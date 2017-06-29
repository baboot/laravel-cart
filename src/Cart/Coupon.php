<?php

namespace Baboot\Cart;

use Baboot\Exceptions\CartCouponValidationException;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

class Coupon implements Arrayable, Jsonable
{
    const TYPE_FIX = 'f';
    const TYPE_PERCENT = 'p';

    /**
     * @var String
     */
    protected $type;

    /**
     * @var Int|Float
     */
    protected $value;

    public function __construct($data)
    {
        if ( ! $this->validate($data) ) throw new CartCouponValidationException();
        $this->setType($data['type']);
        $this->setValue($data['value']);
    }

    /**
     * @param $data
     * @return bool
     */
    private function validate($data)
    {
        return (
            isset($data['type']) &&
            in_array($data['type'], [self::TYPE_FIX, self::TYPE_PERCENT])
        ) && (
            isset($data['value']) &&
            is_numeric($data['value'])
        );
    }

    /**
     * @return String
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param String $type
     */
    protected function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return Float|Int
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param Float|Int $value
     */
    protected function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @param $v
     * @return int
     */
    public function applyTo($v){
        $a =  ($this->type == self::TYPE_FIX)
            ? $v - $this->getValue()
            : $v * ($this->value / 100);
        return ($a > 0) ? $a : 0;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'value' => $this->value,
            'type'  => $this->type
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