<?php 
        
namespace Ormega\Mydb\Entity\Base;

class Group implements \Ormega\EntityInterface {
            
    /**
     * @var bool $_isLoadedFromDb for intern usage : let know the class data comes from db or not 
     */
    protected $_isLoadedFromDb;
    
    /**
     * @var bool $_isModified for intern usage : let know the class if data changed from last save
     */
    protected $_isModified;
    
    protected $_aCacheReference = array();

    /**
     * @var int $id Null:NO Maxlenght:10 auto_increment
     */
    protected $id;
        
    /**
     * @var string $label Null:NO Maxlenght:50 
     */
    protected $label;
        
    /**
     * Group constructor
     * @return void
     * @author Ormegagenerator_lib
     */
    public function __construct()
    {       
        $this->_isLoadedFromDb  = false;
        $this->modified(false);
    }
        
    /**
     * Check if the model is loaded from bdd
     * @param boolean $bLoaded
     * @return boolean
     * @author Ormegagenerator_lib
     */
    public function loaded( $bLoaded = null )
    {
        if (!is_null($bLoaded) && is_bool($bLoaded)) {
            $this->_isLoadedFromDb = $bLoaded;
        }

        return $this->_isLoadedFromDb;
    }
    
    /**
     * Check if the object has been modified since the load 
     * @param boolean $bModified
     * @return boolean
     * @author Ormegagenerator_lib
     */
    public function modified( $bModified = null )
    {
        if (!is_null($bModified) && is_bool($bModified)) {
            $this->_isModified = $bModified;
            if( $bModified ){
                foreach( $this->_aCacheReference as $sCacheKey ){
                    \Ormega\Orm::cache()->delete($sCacheKey);
                }
            }
        }

        return $this->_isModified;
    }
    
    public function addCacheRef( $sCacheRef )
    {
        $this->_aCacheReference[ $sCacheRef ] = true; 
    }
    
    public function cacheKey()
    {
        return __CLASS__.$this->getPkId();
    }
    
    /**
     * Get a unique identifier composed by all primary keys 
     * with "-" separator
     *
     * @return string
     * 
     * @abstracted \Ormega\EntityInterface
     * @author Ormegagenerator_lib
     */
    public function getPkId()
    {
        return (string)$this->getId();
    }
    
    
    
    /**
     * @return int
     * @author Ormegagenerator_lib
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * @return string
     * @author Ormegagenerator_lib
     */
    public function getLabel()
    {
        return $this->label;
    }
        
    /**
     * @param int $id Maxlenght:10
     * @throw \InvalidArgumentException
     * @author Ormegagenerator_lib
     */
    public function setId( $id )
    {
        if( !is_int( $id ) ) {
            throw new \InvalidArgumentException("Invalid parameter for \"".__METHOD__."\" : (int) expected ; \"$id\" (".gettype($id).") provided");
         }
            
        $this->id = $id;
        $this->modified(true);
        
        return $this;
    }
        
    /**
     * @param string $label Maxlenght:50
     * @throw \InvalidArgumentException
     * @author Ormegagenerator_lib
     */
    public function setLabel( $label )
    {
        if( !is_string( $label ) ) {
            throw new \InvalidArgumentException("Invalid parameter for \"".__METHOD__."\" : (string) expected ; \"$label\" (".gettype($label).") provided");
         }
            
        $this->label = $label;
        $this->modified(true);
        
        return $this;
    }
        
    /**
     * Save the object into the database
     * @return bool false if an error occurred ; true otherwise
     * @author Ormegagenerator_lib
     */
    public function save(){

        $return = true;
                
        if( $this->modified() && $return ){ 
        
            if( !is_null($this->id) ){
                \Ormega\Orm::driver(__CLASS__)->set("id", $this->id);
            }
            if( !is_null($this->label) ){
                \Ormega\Orm::driver(__CLASS__)->set("label", $this->label);
            } 
         
            if( !$this->_isLoadedFromDb ){ 
                $return = $return && \Ormega\Orm::driver(__CLASS__)->insert("group"); 
                $this->id = \Ormega\Orm::driver(__CLASS__)->insert_id();
                $this->_isLoadedFromDb = true;
            }
            else {
                \Ormega\Orm::driver(__CLASS__)->where("id = '".$this->id."'");
                $return = $return && \Ormega\Orm::driver(__CLASS__)->update("group");
            }
            
            if( $return ){ 
                $this->modified(false);
            }
        }
        
        return $return;
    }
        
}