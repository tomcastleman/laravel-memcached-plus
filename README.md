# laravel-memcached-plus

_Update_: I have submitted 2 PRs to [laravel/framework](https://github.com/laravel/framework) to integrate this package in to Laravel:
* Memcached persistent connections, SASL authentication and custom options: [#7987](https://github.com/laravel/framework/pull/7987) and
* Memcached Session store configuration [#7988](https://github.com/laravel/framework/pull/7988)

## Summary

Integrating with cloud memcached services such as [MemCachier](https://www.memcachier.com/) and
[memcached cloud](https://redislabs.com/memcached-cloud) can require memcached features not available
with the built-in [Laravel 5 Cache](http://laravel.com/docs/5.0/cache) memcached driver.

These include:

* persistent connections
* SASL authentication 
* custom options

Adding 3 new configuration items, this package _enhances_ the built-in Laravel 5 Cache memcached driver.
Optionally, this package also allows these extra configuration items to be used for memcached
Sessions.

Read on for detailed instructions - you may find it useful to reference the
[demo app](https://github.com/b3it/laravel-memcached-plus-app) at the same time.

## Requirements

* >= PHP 5.4 with [ext-memcached](http://php.net/manual/en/book.memcached.php)
* To use [SASL](http://docs.php.net/manual/en/memcached.setsaslauthdata.php) it must be compiled with
SASL support. This is the default on [Heroku](https://devcenter.heroku.com/articles/php-support)

## Installation

Available to install as a Composer package on
[Packagist](https://packagist.org/packages/b3it/laravel-memcached-plus), all you need to do is:

`composer require b3it/laravel-memcached-plus`

If your local environment does not meet the requirements you may need to append the
`ignore-platform-reqs` option:

`composer require b3it/laravel-memcached-plus --ignore-platform-reqs`

## Configuration

Once installed you can use this package to enhance the Laravel
[Cache](http://laravel.com/docs/5.0/cache) and [Session](http://laravel.com/docs/5.0/session)
services.

### Providers

This section discusses the Laravel application configuration file `app/config.php`.

In the `providers` array you need to replace following built-in Service Providers:

 * `Illuminate\Cache\CacheServiceProvider` and (optionally)
 * `Illuminate\Session\SessionServiceProvider`

A recommended approach is to comment out the built-in providers and append the
Service Providers from this package:

```
'providers' => [
    ...
    //'Illuminate\Cache\CacheServiceProvider',
    ...
    //'Illuminate\Session\SessionServiceProvider',
    ...

    /*
     * Application Service Providers...
     */
     ...

    'B3IT\MemcachedPlus\CacheServiceProvider',
    'B3IT\MemcachedPlus\SessionServiceProvider',
],
```

On a fresh install of Laravel 5.0.13 the providers array is on line 111 of `app/config.php`.

The `B3IT\MemcachedPlus\SessionServiceProvider` is optional. You only need to add this if:

* You want to specify the memcached store to use for sessions, or
* You want to use the memcached features provided by this package for sessions

### Cache

This section discusses the Laravel cache configuration file `config/cache.php`.

This package makes the following extra configuration items are available for use with a memcached store:

* `persistent_id` - [`Memcached::__construct`] (http://php.net/manual/en/memcached.construct.php)
explains how this is used
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
            'OPT_NO_BLOCK'         => true,
            'OPT_AUTO_EJECT_HOSTS' => true,
            'OPT_CONNECT_TIMEOUT'  => 2000,
            'OPT_POLL_TIMEOUT'     => 2000,
            'OPT_RETRY_TIMEOUT'    => 2,
        ],
        'servers' => [
            [
                'host' => '127.0.0.1', 'port' => 11211, 'weight' => 100
            ],
        ],
    ],
],
```

When defining `options` you should set the config key to the `Memcached` constant name as a string.
This avoids any issues with local environments missing ext-memcached and throwing a warning about
undefined constants. The config keys are automatically resolved into `Memcached` constants by the
`MemcachedPlus\MemcachedConnector` which throws a `RuntimeException` if the constant is invalid.

Note that as this package _enhances_ the built-in Laravel 5 memcached Cache driver the driver string
remains `memcached`.

In case you are unfamiliar with how to use multiple cache stores in Laravel, you would access
this store from your application code like so:

```
$value = Cache::store('memcachedstorefoo')->get('key');
```

### Session

This section discusses the Laravel session configuration file `config/session.php`.

If you are using memcached sessions you will have set the `driver` configuration item to 'memcached'.

If you have added the `B3IT\MemcachedPlus\SessionServiceProvider` as discussed above, the
`memcached_store` configuration item is available. This is explained in the following new snippet
you can paste into your session configuration file:

```
    /*
    |--------------------------------------------------------------------------
    | Session Cache Store
    |--------------------------------------------------------------------------
    |
    | When using the "memcached" session driver, you may specify a cache store
    | that should be used for these sessions. This should correspond to a
    | store in your cache configuration options which uses the memcached
    | driver.
    |
    */

    'memcached_store' => 'memcachier',
```

## laravel-memcached-plus in action

I created a [demo app](https://github.com/b3it/laravel-memcached-plus-app) for you to see
how this package integrates with Laravel 5 and how you could run it on Heroku.

## Support

Please do let me know if you have any comments or queries.

Thanks!
