<?php

namespace B3IT\MemcachedPlus;

use Illuminate\Cache\MemcachedStore;
use Illuminate\Session\CacheBasedSessionHandler;
use Illuminate\Session\SessionManager as IlluminateSessionManager;
use RuntimeException;

class SessionManager extends IlluminateSessionManager
{
    /**
     * Create an instance of the Memcached session driver.
     *
     * @return \Illuminate\Session\Store
     */
    protected function createMemcachedDriver()
    {
        $store = $this->app['config']['session.memcached_store'];

        // Verify store uses the memcached driver
        $repository = $this->app['cache']->store($store);
        if (!($repository->getStore() instanceof MemcachedStore)) {
            throw new RuntimeException("session.memcached_store [{$store}] is not a memcached store.");
        }

        $minutes = $this->app['config']['session.lifetime'];

        return $this->buildSession(new CacheBasedSessionHandler($repository, $minutes));
    }
}
