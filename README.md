# laravel-memcached-plus

Integrating with cloud memcached providers such as [Memcachier](https://www.memcachier.com/) and [memcached cloud](https://redislabs.com/memcached-cloud) can require memcached features not available with the built-in [Laravel 5 Cache](http://laravel.com/docs/5.0/cache) memcached driver. These include:

* persistent connections
* SASL authentication 
* custom options

Through extra cache configuration items this package _extends the built-in Laravel 5 Cache memcached driver_.
If you don't use the extra configuration items, the built-in memcached driver will not be overridden.

## Requirements

* >= PHP 5.4 with [ext-memcached](http://php.net/manual/en/book.memcached.php)
* To use [SASL](http://docs.php.net/manual/en/memcached.setsaslauthdata.php) it must be compiled with SASL support. This is the default on [Heroku](https://devcenter.heroku.com/articles/php-support)

## Installation

Available to install via composer, all you need to do is:

`composer require b3it/laravel-memcached-plus`

If your local development environment does not meet the requirements you may do this instead:

`composer require b3it/laravel-memcached-plus --ignore-platform-reqs`

## Activation

In your laravel application `app/config.php` you need to replace the built-in `Illuminate\Cache\CacheServiceProvider` with `B3IT\MemcachedPlus\ServiceProvider` provided by this package, like so:

```
'providers' => [
    ...
    //'Illuminate\Cache\CacheServiceProvider',
    'B3IT\MemcachedPlus\ServiceProvider',
    ...
],
```

As of Laravel 5.0.14 this is on line 119.

## Configuration

Once installed and activated the following extra configuration items are available for use with a memcached store in `config/cache.php`:

* `persistent_id` - [`Memcached::__construct`] (http://php.net/manual/en/memcached.construct.php) explains how this is used
* `sasl` - used by [`Memcached::setSaslAuthData`](http://php.net/manual/en/memcached.setsaslauthdata.php)
* `options` - see [`Memcached::setOptions`](http://php.net/manual/en/memcached.setoptions.php)

These may be used in a store config like so:

```
'stores' => [
    'memcachedstorefoo' => [
        'driver'  => 'memcached',
        'persistent_id' => 'laravel',
        'sasl'       => [
            env('MEMCACHIER_USERNAME'),
            env('MEMCACHIER_PASSWORD')
        ],
        'options'    => [
            Memcached::OPT_NO_BLOCK         => true,
            Memcached::OPT_AUTO_EJECT_HOSTS => true,
            Memcached::OPT_CONNECT_TIMEOUT  => 2000,
            Memcached::OPT_POLL_TIMEOUT     => 2000,
            Memcached::OPT_RETRY_TIMEOUT    => 2,
        ],
        'servers' => [
            [
                'host' => '127.0.0.1', 'port' => 11211, 'weight' => 100
            ],
        ],
    ],
],
```

Note: as this package _extends_ the built-in Laravel 5 memcached Cache driver the driver string remains `memcached`.

## Support

Please do let me know if you have any comments or queries.

Thanks!