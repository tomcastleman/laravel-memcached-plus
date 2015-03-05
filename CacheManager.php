<?php namespace B3IT\MemcachedPlus;

use Illuminate\Cache\MemcachedStore;
use Illuminate\Cache\CacheManager as IlluminateCacheManager;

class CacheManager extends IlluminateCacheManager
{

    /**
     * Create an instance of the Memcached cache driver.
     *
     * If $config contains a 'Plus' feature use MemcachedPlusConnector
     * otherwise use Laravel default $this->app['memcached.connector']
     *
     * @param  array $config
     * @return \Illuminate\Cache\MemcachedStore
     */
    protected function createMemcachedDriver(array $config)
    {
        $prefix = $this->getPrefix($config);

        // Extract Plus features from config
        $persistentConnectionId = array_get($config, 'persistent_id', false);
        $customOptions = array_get($config, 'options', []);
        $saslCredentials = array_filter(array_get($config, 'sasl', []));

        $useMemcachedPlus = ($persistentConnectionId || $customOptions || $saslCredentials);
        if ($useMemcachedPlus) {
            $memcached = (new MemcachedConnector())
                ->connect($config['servers'], $persistentConnectionId, $customOptions, $saslCredentials);
        } else {
            $memcached = $this->app['memcached.connector']->connect($config['servers']);
        }

        return $this->repository(new MemcachedStore($memcached, $prefix));
    }
}
