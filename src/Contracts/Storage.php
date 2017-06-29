<?php

namespace Baboot\Contracts;
use Baboot\Cart\CartCollection;

/**
 * Interface Storage
 * @package Baboot\Contracrs
 */
interface Storage
{
    /**
     * @param $key string
     * @return mixed
     */
    public function get($key);

    /**
     * @param $key
     * @param $data
     * @return mixed
     */
    public function set($key, $data);
}