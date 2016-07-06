<?php

namespace Ormega;

/**
 * Interface CacheInterface
 *
 * @package Ormega
 */
interface CacheInterface
{
    /**
     * Get stored data
     *
     * @param  string $sKey Unique cache ID
     *
     * @return mixed           Data stored
     */
    public function get( $sKey );

    /**
     * Store data into cache
     *
     * @param  string $sKey  Unique cache ID
     * @param  mixed  $mData Data to store
     * @param  int    $nTime Stored data lifetime (seconds)
     *
     * @return boolean       True if data successfully stored
     */
    public function save( $sKey, $mData, $nTime );
        
    /**
     * Delete stored data
     *
     * @param  string $sKey  Unique cache ID
     *
     * @return boolean       True if data successfully stored
     */
    public function delete( $sKey );
}
