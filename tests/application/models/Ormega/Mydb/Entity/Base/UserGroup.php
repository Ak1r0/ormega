<?php 
        
namespace Ormega\Mydb\Entity\Base;

class UserGroup implements \Ormega\EntityInterface {
            
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
     * @var int $user_id Null:NO Maxlenght:10 
     */
    protected $user_id;
        
    /**
     * @var int $group_id Null:NO Maxlenght:10 
     */
    protected $group_id;
        
    /**
     * @var int $enumrole_id Null:YES Maxlenght:10 
     */
    protected $enumrole_id;
        
    /**
     * @var string $dateinsert Null:NO Maxlenght:19 
     */
    protected $dateinsert;
        
    /**
     * @var \Ormega\Mydb\Entity\Group $group Null:NO 
     */
    protected $group;
        
    /**
     * @var \Ormega\Mydb\Entity\User $user Null:NO 
     */
    protected $user;
        
    /**
     * UserGroup constructor
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
     * @return int
     * @author Ormegagenerator_lib
     */
    public function getUserId()
    {
        return $this->user_id;
    }
    
    /**
     * @return int
     * @author Ormegagenerator_lib
     */
    public function getGroupId()
    {
        return $this->group_id;
    }
    
    /**
     * @return int
     * @author Ormegagenerator_lib
     */
    public function getEnumroleId()
    {
        return $this->enumrole_id;
    }
    
    /**
     * @return string
     * @author Ormegagenerator_lib
     */
    public function getDateinsert()
    {
        return $this->dateinsert;
    }
    
    /**
     * @return \Ormega\Mydb\Entity\Group
     * @author Ormegagenerator_lib
     */
    public function getGroup()
    {
        if( is_null($this->group) ){
            $this->group = \Ormega\Mydb\Query\Group::create()
                ->filterById($this->group_id)
                ->findOne();
        }
        
        return $this->group;
    }
    
    /**
     * @return \Ormega\Mydb\Entity\User
     * @author Ormegagenerator_lib
     */
    public function getUser()
    {
        if( is_null($this->user) ){
            $this->user = \Ormega\Mydb\Query\User::create()
                ->filterById($this->user_id)
                ->findOne();
        }
        
        return $this->user;
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
     * @param int $user_id Maxlenght:10
     * @throw \InvalidArgumentException
     * @author Ormegagenerator_lib
     */
    public function setUserId( $user_id )
    {
        if( !is_int( $user_id ) ) {
            throw new \InvalidArgumentException("Invalid parameter for \"".__METHOD__."\" : (int) expected ; \"$user_id\" (".gettype($user_id).") provided");
         }
            
        $this->user_id = $user_id;
        $this->modified(true);
        
        return $this;
    }
        
    /**
     * @param int $group_id Maxlenght:10
     * @throw \InvalidArgumentException
     * @author Ormegagenerator_lib
     */
    public function setGroupId( $group_id )
    {
        if( !is_int( $group_id ) ) {
            throw new \InvalidArgumentException("Invalid parameter for \"".__METHOD__."\" : (int) expected ; \"$group_id\" (".gettype($group_id).") provided");
         }
            
        $this->group_id = $group_id;
        $this->modified(true);
        
        return $this;
    }
        
    /**
     * @param int $enumrole_id Maxlenght:10
     * @throw \InvalidArgumentException
     * @author Ormegagenerator_lib
     */
    public function setEnumroleId( $enumrole_id = null )
    {
        if( !is_null( $enumrole_id ) && !is_int( $enumrole_id ) ) {
            throw new \InvalidArgumentException("Invalid parameter for \"".__METHOD__."\" : (int) expected ; \"$enumrole_id\" (".gettype($enumrole_id).") provided");
         }
            
        $this->enumrole_id = $enumrole_id;
        $this->modified(true);
        
        return $this;
    }
        
    /**
     * @param string $dateinsert Maxlenght:19
     * @throw \InvalidArgumentException
     * @author Ormegagenerator_lib
     */
    public function setDateinsert( $dateinsert )
    {
        if( !is_string( $dateinsert ) ) {
            throw new \InvalidArgumentException("Invalid parameter for \"".__METHOD__."\" : (string) expected ; \"$dateinsert\" (".gettype($dateinsert).") provided");
         }
            
        $this->dateinsert = $dateinsert;
        $this->modified(true);
        
        return $this;
    }
        
    /**
     * @param \Ormega\Mydb\Entity\Group $group
     * @author Ormegagenerator_lib
     */
    public function setGroup(\Ormega\Mydb\Entity\Group $group )
    {
        $this->group = $group;
        $this->group_id = $group->getId();
        $this->modified(true);
        
        return $this;
    }
        
    /**
     * @param \Ormega\Mydb\Entity\User $user
     * @author Ormegagenerator_lib
     */
    public function setUser(\Ormega\Mydb\Entity\User $user )
    {
        $this->user = $user;
        $this->user_id = $user->getId();
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
        
        $return = $return && (!$this->group || $this->group->save());
        
        $return = $return && (!$this->user || $this->user->save());
                
        if( $this->modified() && $return ){ 
        
            if( !is_null($this->id) ){
                \Ormega\Orm::driver(__CLASS__)->set("id", $this->id);
            }
            if( !is_null($this->user_id) ){
                \Ormega\Orm::driver(__CLASS__)->set("user_id", $this->user_id);
            }
            if( !is_null($this->group_id) ){
                \Ormega\Orm::driver(__CLASS__)->set("group_id", $this->group_id);
            }
            \Ormega\Orm::driver(__CLASS__)->set("enumrole_id", $this->enumrole_id);
            if( !is_null($this->dateinsert) ){
                \Ormega\Orm::driver(__CLASS__)->set("dateinsert", $this->dateinsert);
            } 
         
            if( !$this->_isLoadedFromDb ){ 
                $return = $return && \Ormega\Orm::driver(__CLASS__)->insert("user_group"); 
                $this->id = \Ormega\Orm::driver(__CLASS__)->insert_id();
                $this->_isLoadedFromDb = true;
            }
            else {
                \Ormega\Orm::driver(__CLASS__)->where("id = '".$this->id."'");
                $return = $return && \Ormega\Orm::driver(__CLASS__)->update("user_group");
            }
            
            if( $return ){ 
                $this->modified(false);
            }
        }
        
        return $return;
    }
        
}