<?php 
        
namespace Ormega\Mydb\Enum;

class Enumrole implements \Ormega\EnumInterface {
    
    /** 
     * @var int Admin"
     */
    const ADFMIN = 1;
    
    /** 
     * @var int V.I.P.
     */
    const VIP = 2;
    
    /** 
     * @var int Utilisateur
     */
    const USER = 3;
    
    /** 
     * @var int test
     */
    const _55TEST = 4;
    
    /**
     * Get the "Label" associated to an ID
     * @param int $nId
     * @return string
     * @author Ormegagenerator_lib
     */
    public static function getLabel( $nId )
    {
        $aValues = array(
            1 => "Admin\"",
            2 => "V.I.P.",
            3 => "Utilisateur",
            4 => "test",
        );
        
        return isset($aValues[ $nId ])? $aValues[ $nId ] : null;
    }
            
    
    /**
     * Get the "Constant" associated to an ID
     * @param int $nId
     * @return string
     * @author Ormegagenerator_lib
     */
    public static function getConstant( $nId )
    {
        $aValues = array(
            1 => "ADFMIN",
            2 => "VIP",
            3 => "USER",
            4 => "_55TEST",
        );
        
        return isset($aValues[ $nId ])? $aValues[ $nId ] : null;
    }
            
    
    /**
     * Get the "Test" associated to an ID
     * @param int $nId
     * @return string
     * @author Ormegagenerator_lib
     */
    public static function getTest( $nId )
    {
        $aValues = array(
            1 => "",
            2 => "sdfsf",
            3 => "",
            4 => "r515",
        );
        
        return isset($aValues[ $nId ])? $aValues[ $nId ] : null;
    }
            
        
    /**
     * Get all the constants in a array form
     * @return array
     * @author Ormegagenerator_lib
     */
    public static function getArray()
    {
        return array(
            "ADFMIN" => array(
                "id" => "1",
                "label" => "Admin\"",
                "constant" => "ADFMIN",
                "test" => "",
            ),
            "VIP" => array(
                "id" => "2",
                "label" => "V.I.P.",
                "constant" => "VIP",
                "test" => "sdfsf",
            ),
            "USER" => array(
                "id" => "3",
                "label" => "Utilisateur",
                "constant" => "USER",
                "test" => "",
            ),
            "_55TEST" => array(
                "id" => "4",
                "label" => "test",
                "constant" => "_55TEST",
                "test" => "r515",
            ),
        );
    }    
    
    /**
     * Get an ID from a string constant
     * @param string $sConstant
     * @return int
     * @author Ormegagenerator_lib
     */
    public static function getId( $sConstant )
    {
        switch( strtoupper($sConstant) ){
            case "ADFMIN":
                return self::ADFMIN;
                break;
            case "VIP":
                return self::VIP;
                break;
            case "USER":
                return self::USER;
                break;
            case "_55TEST":
                return self::_55TEST;
                break;
            default:
                return 0;
        }
    }
}