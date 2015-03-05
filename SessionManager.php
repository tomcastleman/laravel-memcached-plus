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
        dd('here');
        return $this->createCacheBased('memcached');
    }
}
