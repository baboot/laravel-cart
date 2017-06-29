# Cart package for laravel
## Installation
    
Add provider to `config/app.php` file.

```php
'providers' => [
    ...
    \Baboot\CartServiceProvider::class
];
```
Add alias to `config/app.php` file.
```php
'aliases' => [
    ...
    'Cart' => \Baboot\Facades\Cart::class
];
```

## Setup
Publishing config
```bash
php artisan vendor:publish
```
Import migrations
```bash
php artisan migrate
```

Specify model that beign used for adding to cart. For that, in model class insert trait and specify attributes. For example:
```php
<?php

class Product extends  Illuminate\Database\Eloquent\Model
{
    use App\Package\src\Mixins\Cartable;

    protected $cart_price_arrtibute = 'price';
    protected $cart_item_id = 'id';
    
    ...
}
```

In this case Cart will used price attribute for price and id from attribute id. If price is aggregatable attribute you may specify an [accessor](https://laravel.com/docs/5.4/eloquent-mutators#defining-an-accessor) of price attribute

## Usage
### init
First thing you need to do is init the cart:
```php
\Cart::init($key)
```
where **$key** is a key for a cart for current user. You need to specify them dependings on your application rules. **$key** is a primary key varchar(12)

### add
Adding item to cart
```php
\Cart::add($model)
```

### remove
Removing item to cart
```php
\Cart::remove($model)
```

### count
Count of all items in cart
```php
\Cart::totalQuantity()
```

### sub total
get total in cart without price modifacators(coupons)
```php
\Cart::subTotal()
```

### total
get total in cart with price modifacators(coupons)
```php
\Cart::total()
```

##Events

**cart.inited** 

**cart.action.added**

**cart.action.removed**

**cart.action.coupon.added**


## Storage
You can store cart in **DB** ot **Redis**.
Specify **CART_STORAGE** var in your .env file, or change it config (cart.php)
Also you can create your own driver(implementing **Baboot\Contracts\Storage** ) and add it in **storageDrivers** directive in config and use them. 