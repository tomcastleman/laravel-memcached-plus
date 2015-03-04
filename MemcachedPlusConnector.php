<?php namespace B3IT\MemcachedPlus;

use Memcached;
use RuntimeException;

class MemcachedPlusConnector
{

    /**
     * Create a new Memcached connection.
     *
     * @param  array $servers
     * @param  string $persistentConnectionId
     * @param  array $customOptions
     * @param  array $saslCredentials
     * @return \Memcached
     *
     * @throws \RuntimeException
     */
    public function connect(array $servers, $persistentConnectionId, array $customOptions, array $saslCredentials)
    {
        $memcached = $this->getMemcached($persistentConnectionId);

        // Set custom options
        if (count($customOptions)) {
            $memcached->setOptions($customOptions);
        }

        // Set SASL auth data
        if (count($saslCredentials) == 2) {
            list($username, $password) = $saslCredentials;
            $memcached->setOption(Memcached::OPT_BINARY_PROTOCOL, true);
            $memcached->setSaslAuthData($username, $password);
        }

        // For each server in the array, we'll just extract the configuration and add
        // the server to the Memcached connection. Once we have added all of these
        // servers we'll verify the connection is successful and return it back.
        foreach ($servers as $server) {
            $memcached->addServer(
                $server['host'], $server['port'], $server['weight']
            );
        }

        $memcachedStatus = $memcached->getVersion();
        if (!is_array($memcachedStatus)) {
            throw new RuntimeException("No Memcached servers added.");
        }
        if (in_array('255.255.255', $memcachedStatus) && count(array_unique($memcachedStatus)) === 1) {
            throw new RuntimeException("Could not establish Memcached connection.");
        }

        return $memcached;
    }

    /**
     * Get a new Memcached instance.
     *
     * @return \Memcached
     */
    protected function getMemcached($persistentConnectionId)
    {
        if ($persistentConnectionId !== false) {
            return new Memcached($persistentConnectionId);
        }

        return new Memcached;
    }

}
