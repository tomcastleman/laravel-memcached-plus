<?php

namespace B3IT\MemcachedPlus;

use Illuminate\Cache\MemcachedStore;
use Illuminate\Cache\CacheManager as IlluminateCacheManager;

class CacheManager extends IlluminateCacheManager
{
    /**
     * Create an instance of the Memcached cache driver.
     *
     * @param array $config
     *
     * @return \Illuminate\Cache\MemcachedStore
     */
    protected function createMemcachedDriver(array $config)
    {
        $prefix = $this->getPrefix($config);

        // Extract Plus features from config
        $persistentConnectionId = array_get($config, 'persistent_id');
        $customOptions = array_get($config, 'options', []);
        $saslCredentials = array_filter(array_get($config, 'sasl', []));

        $memcached = $this->app['memcached.connector']->connect(
            $config['servers'],
            $persistentConnectionId,
            $customOptions,
            $saslCredentials
        );

        return $this->repository(new MemcachedStore($memcached, $prefix));
    }
}
