<?php

namespace Baboot;

use Baboot\Exception\CartStorageDriverNotFoundException;
use Illuminate\Support\ServiceProvider;

class CartServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

        $this->publishes([
            realpath(__DIR__ ) . DIRECTORY_SEPARATOR . '../config/cart.php' => config_path('cart.php')
        ]);

        $this->loadMigrationsFrom(realpath(__DIR__ ) . DIRECTORY_SEPARATOR  . '../migrations');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('cart', function($app)
        {
            return $this->app->make(Cart::class);
        });

        $this->mergeConfigFrom(realpath(__DIR__ ) . DIRECTORY_SEPARATOR . '../config/cart.php', 'cart');

        $this->bindStorage();

        $this->app->make('events')->listen('cart.action.*', function($name, $data){
            $this->app->make('Baboot\Contracts\Storage')->set(\Cart::getKey(), \Cart::toJson());
        });
    }

    /**
     * Binding Storage Contract
     *
     * @throws CartStorageDriverNotFoundException
     */
    private function bindStorage()
    {
        $config = $this->app->make('config')->get('cart');

        $sd = $config['storage'];
        $driverClass = @$config['storageDrivers'][$sd];

        if( ! class_exists($driverClass) ){
            throw new CartStorageDriverNotFoundException();
        }

        $this->app->bind('Baboot\Contracts\Storage', $driverClass);
    }
}
