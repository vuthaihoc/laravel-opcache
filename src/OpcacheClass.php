<?php

namespace HocVT\LaravelOpcache;

class OpcacheClass
{
    /**
     * Clear OPcache.
     */
    public function clear()
    {
        if (function_exists('opcache_reset')) {
            return opcache_reset();
        }

        return null;
    }

    /**
     * Get status info.
     */
    public function getStatus()
    {
        if (function_exists('opcache_get_status')) {
            return opcache_get_status(false);
        }

        return ['opcache_installed' => 'NO'];
    }
}
