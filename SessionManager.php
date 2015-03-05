<?php namespace B3IT\MemcachedPlus;

use Illuminate\Session\SessionManager as IlluminateSessionManager;

class SessionManager extends IlluminateSessionManager
{
    /**
     * Create an instance of the Memcached session driver.
     *
     * @return \Illuminate\Session\Store
     */
    protected function createMemcachedDriver()
    {
        $store = $this->app['config']['session.store'];

        return $this->buildSession($this->createMemcachedHandler($store));
    }

    /**
     * Create the memcache based session handler instance.
     *
     * @param  string  $store
     * @return \Illuminate\Session\CacheBasedSessionHandler
     */
    protected function createMemcachedHandler($store)
    {
        $minutes = $this->app['config']['session.lifetime'];

        return new CacheBasedSessionHandler($this->app['cache']->store($store), $minutes);
    }

}
