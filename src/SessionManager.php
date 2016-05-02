<?php

namespace B3IT\MemcachedPlus;

use Illuminate\Session\CacheBasedSessionHandler;
use Illuminate\Session\SessionManager as IlluminateSessionManager;

class SessionManager extends IlluminateSessionManager
{
    /**
     * Create the cache based session handler instance.
     *
     * @param  string  $driver
     * @return \Illuminate\Session\CacheBasedSessionHandler
     */
    protected function createCacheHandler($driver)
    {
        $store = array_get($this->app['config'], 'session.store', $driver);
        $minutes = $this->app['config']['session.lifetime'];

        return new CacheBasedSessionHandler(clone $this->app['cache']->store($store), $minutes);
    }
}
