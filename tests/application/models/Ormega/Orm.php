<?php
            
namespace Ormega;
        
class Orm {

    const OPERATOR_GREATER_THAN = ">";
    const OPERATOR_LOWER_THAN = "<";
    const OPERATOR_GREATEREQUALS_THAN = ">=";
    const OPERATOR_LOWEREQUALS_THAN = "<=";
    const OPERATOR_EQUALS = "=";
    const OPERATOR_DIFF = "<>";
    const OPERATOR_IN = "IN";
    const OPERATOR_NOTIN = "NOT IN";
    const OPERATOR_PC_LIKE_PC = "%LIKE%";
    const OPERATOR_PC_LIKE = "%LIKE";
    const OPERATOR_LIKE_PC = "LIKE%";
    const OPERATOR_ISNULL = "IS NULL";
    const OPERATOR_ISNOTNULL = "IS NOT NULL";
    
    const ORDER_ASC = "ASC";
    const ORDER_DESC = "DESC";
    
    /**
     * @var array $aDb array of CI_DB_driver
     */
    protected static $aDb;
    
    /**
     * @var \Ormega\CacheInterface Cache driver
     */
    protected static $oCache;

    /**
     * Get the database driver set in the init() method
     *
     * @param string $sClassName Classname of the calling class, used to determine which connection use in case of multiple database connections
     *
     * @return CI_DB_driver
     *
     * @author Ormegagenerator_lib
     */
    public static function driver( $sClassName )
    {
        $aClass = explode("\\", $sClassName);

        if( isset($aClass[1]) && isset( self::$aDb[ $aClass[1] ] ) )
            return self::$aDb[ $aClass[1] ];
        else
            return reset(self::$aDb);
    }
    
    /**
     * Get the cache driver set in the init() method
     * 
     * @return \Ormega\CacheInterface
     */
    public static function cache()
    {
        return self::$oCache;
    }
    
    /**
     * Initiate the orm with a database connection (can be adapted to any driver
     *      as long as it implement the \Ormega\DbInterface interface).
     * Define an autoload for all Ormega generated classes
     *
     * @param array $aDb Array of CI_DB_driver objects
     * @return void
     *
     * @author Ormegagenerator_lib
     */
    public static function init(array $aDb, \Ormega\CacheInterface $oCache = null)
    {

        /* --------------------------------------------------------
         * DATABASE
         * --------------------------------------------------------
         */
        $aDb = self::setDatabase($aDb);

        /* --------------------------------------------------------
         * AUTOLOADER
         * --------------------------------------------------------
         */
        spl_autoload_register(function($class) use ($aDb){
            $aPaths = explode("\\", $class);

            if( isset($aPaths[0]) && $aPaths[0] == __NAMESPACE__ ) {

                $basepath = __DIR__."/";
                if( isset($aPaths[1]) && isset( $aDb[ $aPaths[1] ] ) ) {
                    $basepath = $basepath.$aPaths[1]."/";

                    if( isset($aPaths[2]) && is_dir($basepath.$aPaths[2]) ){
                        $basepath = $basepath.$aPaths[2]."/";
    
                        if( isset($aPaths[3]) && is_dir($basepath.$aPaths[3]) ){
                            $basepath = $basepath.$aPaths[3]."/";
                        }
                    }
                }
                
                if( is_file($basepath.end($aPaths).".php") ){
                    require_once $basepath.end($aPaths).".php";
                }
            }
        });

        /* --------------------------------------------------------
         * CACHE
         * --------------------------------------------------------
         */
        self::setCacheDriver($oCache);
    }

    /**
     * Initiate database driver
     *
     * @param array $aDb Array of CI_DB_driver objects
     * @return void
     *
     * @author Ormegagenerator_lib
     */
    protected static function setDatabase(array $aDb)
    {
        self::$aDb = array();
        foreach ( $aDb as $sDatabase => $aDbDriver ) {
            if ( !is_a($aDbDriver, "CI_DB_driver") ) {
                throw new \InvalidArgumentException("Array of CI_DB_driver objects expected for " . __METHOD__);
            }
            self::$aDb[ ucfirst($sDatabase) ] = $aDbDriver;
        }

        return self::$aDb;
    }

    /**
     * Initiate cache driver
     *
     * @param \Ormega\CacheInterface|null $oCache
     *
     * @author Ormegagenerator_lib
     */
    protected static function setCacheDriver(\Ormega\CacheInterface $oCache = null)
    {
        if( !is_null($oCache) ) {
            self::$oCache = $oCache;
        } else {
            self::$oCache = new Simucache();
        }
    }
}
