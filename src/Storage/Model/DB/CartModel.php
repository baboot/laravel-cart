<?php

namespace Baboot\Storage\Model\DB;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CartModel
 */
class CartModel extends Model
{

    /**
     * @inheritdoc
     */
    protected $primaryKey = 'key';

    /**
     * @inheritdoc
     */
    public $table = 'cart';

    /**
     * @inheritdoc
     */
    public $fillable = ['key', 'data'];

    /**
     * @inheritdoc
     */
    public $timestamps = false;
}