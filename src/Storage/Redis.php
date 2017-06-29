<?php

namespace Baboot\Storage;

use Baboot\Contracts\Storage;

class Redis implements Storage
{

    /**
     * @return string
     */
    private function prefix(){
        return 'cart.';
    }

    /**
     * @param $key string
     * @return mixed
     */
    public function get($key)
    {
        return \Redis::get($this->prefix() . $key);
    }

    /**
     * @param $key
     * @param $data
     * @return mixed
     */
    public function set($key, $data)
    {
        \Redis::set($this->prefix() . $key, $data);
    }
}