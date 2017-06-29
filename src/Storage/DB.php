<?php

namespace Baboot\Storage;

use Baboot\Cart\CartCollection;
use Baboot\Contracts\Storage;
use Baboot\Storage\Model\DB\CartModel;

/**
 * Class DB
 * @package Baboot\Storage
 */
class DB implements Storage
{

    /**
     * @param $key string
     * @return mixed
     */
    public function get($key)
    {
        $model = CartModel::find($key);
        return ! is_null($model) ? json_decode($model->data) : (object)[];
    }

    /**
     * @param $key
     * @param $data
     * @return mixed
     */
    public function set($key, $data)
    {
        $model = CartModel::firstOrNew([
            'key'=>$key,
        ]);

        $model->data = $data;
        $model->save();
    }

}