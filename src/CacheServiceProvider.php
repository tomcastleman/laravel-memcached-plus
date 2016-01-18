<?php namespace B3IT\MemcachedPlus;

use Illuminate\Cache\CacheServiceProvider as IlluminateCacheServiceProvider;

class CacheServiceProvider extends IlluminateCacheServiceProvider
{

    /**
     * Replace \Illuminate\Cache\CacheManager with B3IT\CacheManager
     *
     * @return void
     */
    public function register()
    {
        parent::register();

        $this->app->singleton('cache', function ($app) {
            return new CacheManager($app);
        });

        $this->app->singleton('memcached.connector', function () {
            return new MemcachedConnector;
        });
    }

}
