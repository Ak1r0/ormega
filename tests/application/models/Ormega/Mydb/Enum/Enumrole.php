<?php 
        
namespace Ormega\Mydb\Enum;

class Enumrole implements \Ormega\EnumInterface {

    
    /**
     * @var int Admin
     */
    const ADMIN = 1;
    
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
     * Get an ID from a string constant
     * @param string $sConstant
     * @return int
     * @author Ormegagenerator_lib
     */
    public static function getId( $sConstant )
    {
        switch( strtoupper($sConstant) ){
            case "ADMIN":
                return self::ADMIN;
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
    
    /**
     * Get all the constants in a array form
     * @return array
     * @author Ormegagenerator_lib
     */
    public static function getArray()
    {
        return array(
            "ADMIN" => array("id"=>"1", "label"=>"Admin", "constant"=>"ADMIN"),"VIP" => array("id"=>"2", "label"=>"V.I.P.", "constant"=>"VIP"),"USER" => array("id"=>"3", "label"=>"Utilisateur", "constant"=>"USER"),"_55TEST" => array("id"=>"4", "label"=>"test", "constant"=>"_55TEST"),
        );
    }    
    
    /**
     * The label (description) associated with one ID
     * @param int $nId Constant ID
     * @return string
     * @author Ormegagenerator_lib
     */
    public static function getLabel( $nId ){
        
        $aLabels = array(
            1 => "Admin",
            2 => "V.I.P.",
            3 => "Utilisateur",
            4 => "test",
        );
        
        return isset($aLabels[ $nId ])? $aLabels[ $nId ] : "";
    }
    
    /**
     * The constant string associated with one ID
     * @param int $nId Constant ID
     * @return string
     * @author Ormegagenerator_lib
     */
    public static function getConstant( $nId ){
        
        $aConstants = array(
            1 => "ADMIN",
            2 => "VIP",
            3 => "USER",
            4 => "_55TEST",
        );
        
        return isset($aConstants[ $nId ])? $aConstants[ $nId ] : "";
    }
}