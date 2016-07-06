<?php

namespace Ormega;

/**
 * Simulate a cache system if no one provided
 *
 * @package  Ormega
 */
class Simucache implements \Ormega\CacheInterface {

    /**
     * @var array Data store
     */
    protected $aData = array();

    /**
     * Get stored data
     *
     * @param  string $sKey Unique cache ID
     *
     * @return mixed           Data stored
     */
    public function get($sKey)
    {
        return isset( $this->aData[$sKey] )? $this->aData[$sKey] : false;
    }

    /**
     * Store data into cache
     *
     * @param  string $sKey  Unique cache ID
     * @param  mixed  $mData Data to store
     * @param  int    $nTime Stored data lifetime (seconds)
     *
     * @return boolean       True if data successfully stored
     */
    public function save( $sKey, $mData, $nTime )
    {
        $this->aData[ $sKey ] = $mData;
        return true;
    }    
    
    /**
     * Delete stored data
     *
     * @param  string $sKey  Unique cache ID
     *
     * @return boolean       True if data successfully stored
     */
    public function delete( $sKey )
    {
        if( isset($this->aData[ $sKey ]) ){
            unset( $this->aData[ $sKey ] );
        }
    }
}
