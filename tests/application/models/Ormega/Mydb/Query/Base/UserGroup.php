<?php 
        
namespace Ormega\Mydb\Query\Base;

class UserGroup implements \Ormega\QueryInterface {
    
    /**
     * Get an instance of this class to chain methods 
     *      without have to use $var = new UserGroup()
     *
     * @return \Ormega\Mydb\Query\UserGroup
     *
     * @author Ormegagenerator_lib
     */
    public static function create(){
        return new static();
    }
    
    /**
     * Add a limit to the nb result row for the next find() request
     
     * @param int $limit
     * @param int $start
     *
     * @return \Ormega\Mydb\Query\UserGroup
     *
     * @author Ormegagenerator_lib
     */
    public function limit( $limit, $start = 0 ){
        \Ormega\Orm::driver(__CLASS__)->limit( $limit, $start );
        return $this;
    }
    
    
    /**
     * Add a special where to next find() call
     * @param mixed $value The filter value
     * @param string $operator Can be \Ormega\Orm::OPERATOR_* constant (">", "<=", "=", ...)
     * @return \Ormega\Mydb\Query\UserGroup
     * @author Ormegagenerator_lib
     */
    public function filterById( $value, $operator = \Ormega\Orm::OPERATOR_EQUALS )
    {
        if( $operator == \Ormega\Orm::OPERATOR_IN || $operator == \Ormega\Orm::OPERATOR_NOTIN ){
            if( !is_array($value) ) {
                $value = explode(",", $value);
            }
        }
        
        switch( $operator ){
            case \Ormega\Orm::OPERATOR_IN:
                \Ormega\Orm::driver(__CLASS__)
                    ->where_in("ormegatest.user_group.id", $value);
                break;
            case \Ormega\Orm::OPERATOR_NOTIN:
                \Ormega\Orm::driver(__CLASS__)
                    ->where_not_in("ormegatest.user_group.id", $value);
                break;
            case \Ormega\Orm::OPERATOR_LIKE_PC:
                \Ormega\Orm::driver(__CLASS__)
                    ->like("ormegatest.user_group.id", $value, "after");
                break;
            case \Ormega\Orm::OPERATOR_PC_LIKE:
                \Ormega\Orm::driver(__CLASS__)
                    ->like("ormegatest.user_group.id", $value, "before");
                break;
            case \Ormega\Orm::OPERATOR_PC_LIKE_PC:
                \Ormega\Orm::driver(__CLASS__)
                    ->like("ormegatest.user_group.id", $value, "both");
                break;
            case \Ormega\Orm::OPERATOR_ISNULL:
                \Ormega\Orm::driver(__CLASS__)
                    ->where("ormegatest.user_group.id IS NULL");
                break;
            case \Ormega\Orm::OPERATOR_ISNOTNULL:
                \Ormega\Orm::driver(__CLASS__)
                    ->where("ormegatest.user_group.id IS NOT NULL");
                break;
            default:
                \Ormega\Orm::driver(__CLASS__)
                    ->where( "ormegatest.user_group.id ".$operator, $value );
        }
        
        return $this;
    }
    
    /**
     * Add a special where to next find() call
     * @param mixed $value The filter value
     * @param string $operator Can be \Ormega\Orm::OPERATOR_* constant (">", "<=", "=", ...)
     * @return \Ormega\Mydb\Query\UserGroup
     * @author Ormegagenerator_lib
     */
    public function filterByUserId( $value, $operator = \Ormega\Orm::OPERATOR_EQUALS )
    {
        if( $operator == \Ormega\Orm::OPERATOR_IN || $operator == \Ormega\Orm::OPERATOR_NOTIN ){
            if( !is_array($value) ) {
                $value = explode(",", $value);
            }
        }
        
        switch( $operator ){
            case \Ormega\Orm::OPERATOR_IN:
                \Ormega\Orm::driver(__CLASS__)
                    ->where_in("ormegatest.user_group.user_id", $value);
                break;
            case \Ormega\Orm::OPERATOR_NOTIN:
                \Ormega\Orm::driver(__CLASS__)
                    ->where_not_in("ormegatest.user_group.user_id", $value);
                break;
            case \Ormega\Orm::OPERATOR_LIKE_PC:
                \Ormega\Orm::driver(__CLASS__)
                    ->like("ormegatest.user_group.user_id", $value, "after");
                break;
            case \Ormega\Orm::OPERATOR_PC_LIKE:
                \Ormega\Orm::driver(__CLASS__)
                    ->like("ormegatest.user_group.user_id", $value, "before");
                break;
            case \Ormega\Orm::OPERATOR_PC_LIKE_PC:
                \Ormega\Orm::driver(__CLASS__)
                    ->like("ormegatest.user_group.user_id", $value, "both");
                break;
            case \Ormega\Orm::OPERATOR_ISNULL:
                \Ormega\Orm::driver(__CLASS__)
                    ->where("ormegatest.user_group.user_id IS NULL");
                break;
            case \Ormega\Orm::OPERATOR_ISNOTNULL:
                \Ormega\Orm::driver(__CLASS__)
                    ->where("ormegatest.user_group.user_id IS NOT NULL");
                break;
            default:
                \Ormega\Orm::driver(__CLASS__)
                    ->where( "ormegatest.user_group.user_id ".$operator, $value );
        }
        
        return $this;
    }
    
    /**
     * Add a special where to next find() call
     * @param mixed $value The filter value
     * @param string $operator Can be \Ormega\Orm::OPERATOR_* constant (">", "<=", "=", ...)
     * @return \Ormega\Mydb\Query\UserGroup
     * @author Ormegagenerator_lib
     */
    public function filterByGroupId( $value, $operator = \Ormega\Orm::OPERATOR_EQUALS )
    {
        if( $operator == \Ormega\Orm::OPERATOR_IN || $operator == \Ormega\Orm::OPERATOR_NOTIN ){
            if( !is_array($value) ) {
                $value = explode(",", $value);
            }
        }
        
        switch( $operator ){
            case \Ormega\Orm::OPERATOR_IN:
                \Ormega\Orm::driver(__CLASS__)
                    ->where_in("ormegatest.user_group.group_id", $value);
                break;
            case \Ormega\Orm::OPERATOR_NOTIN:
                \Ormega\Orm::driver(__CLASS__)
                    ->where_not_in("ormegatest.user_group.group_id", $value);
                break;
            case \Ormega\Orm::OPERATOR_LIKE_PC:
                \Ormega\Orm::driver(__CLASS__)
                    ->like("ormegatest.user_group.group_id", $value, "after");
                break;
            case \Ormega\Orm::OPERATOR_PC_LIKE:
                \Ormega\Orm::driver(__CLASS__)
                    ->like("ormegatest.user_group.group_id", $value, "before");
                break;
            case \Ormega\Orm::OPERATOR_PC_LIKE_PC:
                \Ormega\Orm::driver(__CLASS__)
                    ->like("ormegatest.user_group.group_id", $value, "both");
                break;
            case \Ormega\Orm::OPERATOR_ISNULL:
                \Ormega\Orm::driver(__CLASS__)
                    ->where("ormegatest.user_group.group_id IS NULL");
                break;
            case \Ormega\Orm::OPERATOR_ISNOTNULL:
                \Ormega\Orm::driver(__CLASS__)
                    ->where("ormegatest.user_group.group_id IS NOT NULL");
                break;
            default:
                \Ormega\Orm::driver(__CLASS__)
                    ->where( "ormegatest.user_group.group_id ".$operator, $value );
        }
        
        return $this;
    }
    
    /**
     * Add a special where to next find() call
     * @param mixed $value The filter value
     * @param string $operator Can be \Ormega\Orm::OPERATOR_* constant (">", "<=", "=", ...)
     * @return \Ormega\Mydb\Query\UserGroup
     * @author Ormegagenerator_lib
     */
    public function filterByEnumroleId( $value, $operator = \Ormega\Orm::OPERATOR_EQUALS )
    {
        if( $operator == \Ormega\Orm::OPERATOR_IN || $operator == \Ormega\Orm::OPERATOR_NOTIN ){
            if( !is_array($value) ) {
                $value = explode(",", $value);
            }
        }
        
        switch( $operator ){
            case \Ormega\Orm::OPERATOR_IN:
                \Ormega\Orm::driver(__CLASS__)
                    ->where_in("ormegatest.user_group.enumrole_id", $value);
                break;
            case \Ormega\Orm::OPERATOR_NOTIN:
                \Ormega\Orm::driver(__CLASS__)
                    ->where_not_in("ormegatest.user_group.enumrole_id", $value);
                break;
            case \Ormega\Orm::OPERATOR_LIKE_PC:
                \Ormega\Orm::driver(__CLASS__)
                    ->like("ormegatest.user_group.enumrole_id", $value, "after");
                break;
            case \Ormega\Orm::OPERATOR_PC_LIKE:
                \Ormega\Orm::driver(__CLASS__)
                    ->like("ormegatest.user_group.enumrole_id", $value, "before");
                break;
            case \Ormega\Orm::OPERATOR_PC_LIKE_PC:
                \Ormega\Orm::driver(__CLASS__)
                    ->like("ormegatest.user_group.enumrole_id", $value, "both");
                break;
            case \Ormega\Orm::OPERATOR_ISNULL:
                \Ormega\Orm::driver(__CLASS__)
                    ->where("ormegatest.user_group.enumrole_id IS NULL");
                break;
            case \Ormega\Orm::OPERATOR_ISNOTNULL:
                \Ormega\Orm::driver(__CLASS__)
                    ->where("ormegatest.user_group.enumrole_id IS NOT NULL");
                break;
            default:
                \Ormega\Orm::driver(__CLASS__)
                    ->where( "ormegatest.user_group.enumrole_id ".$operator, $value );
        }
        
        return $this;
    }
    
    /**
     * Add a special where to next find() call
     * @param mixed $value The filter value
     * @param string $operator Can be \Ormega\Orm::OPERATOR_* constant (">", "<=", "=", ...)
     * @return \Ormega\Mydb\Query\UserGroup
     * @author Ormegagenerator_lib
     */
    public function filterByDateinsert( $value, $operator = \Ormega\Orm::OPERATOR_EQUALS )
    {
        if( $operator == \Ormega\Orm::OPERATOR_IN || $operator == \Ormega\Orm::OPERATOR_NOTIN ){
            if( !is_array($value) ) {
                $value = explode(",", $value);
            }
        }
        
        switch( $operator ){
            case \Ormega\Orm::OPERATOR_IN:
                \Ormega\Orm::driver(__CLASS__)
                    ->where_in("ormegatest.user_group.dateinsert", $value);
                break;
            case \Ormega\Orm::OPERATOR_NOTIN:
                \Ormega\Orm::driver(__CLASS__)
                    ->where_not_in("ormegatest.user_group.dateinsert", $value);
                break;
            case \Ormega\Orm::OPERATOR_LIKE_PC:
                \Ormega\Orm::driver(__CLASS__)
                    ->like("ormegatest.user_group.dateinsert", $value, "after");
                break;
            case \Ormega\Orm::OPERATOR_PC_LIKE:
                \Ormega\Orm::driver(__CLASS__)
                    ->like("ormegatest.user_group.dateinsert", $value, "before");
                break;
            case \Ormega\Orm::OPERATOR_PC_LIKE_PC:
                \Ormega\Orm::driver(__CLASS__)
                    ->like("ormegatest.user_group.dateinsert", $value, "both");
                break;
            case \Ormega\Orm::OPERATOR_ISNULL:
                \Ormega\Orm::driver(__CLASS__)
                    ->where("ormegatest.user_group.dateinsert IS NULL");
                break;
            case \Ormega\Orm::OPERATOR_ISNOTNULL:
                \Ormega\Orm::driver(__CLASS__)
                    ->where("ormegatest.user_group.dateinsert IS NOT NULL");
                break;
            default:
                \Ormega\Orm::driver(__CLASS__)
                    ->where( "ormegatest.user_group.dateinsert ".$operator, $value );
        }
        
        return $this;
    }
    
    /**
     * Add a special groupby to next find() call 
     * @return \Ormega\Mydb\Query\UserGroup
     * @author Ormegagenerator_lib
     */
    public function groupById()
    {
        \Ormega\Orm::driver(__CLASS__)
            ->group_by("ormegatest.user_group.id");
        return $this;
    }
    
    /**
     * Add a special groupby to next find() call 
     * @return \Ormega\Mydb\Query\UserGroup
     * @author Ormegagenerator_lib
     */
    public function groupByUserId()
    {
        \Ormega\Orm::driver(__CLASS__)
            ->group_by("ormegatest.user_group.user_id");
        return $this;
    }
    
    /**
     * Add a special groupby to next find() call 
     * @return \Ormega\Mydb\Query\UserGroup
     * @author Ormegagenerator_lib
     */
    public function groupByGroupId()
    {
        \Ormega\Orm::driver(__CLASS__)
            ->group_by("ormegatest.user_group.group_id");
        return $this;
    }
    
    /**
     * Add a special groupby to next find() call 
     * @return \Ormega\Mydb\Query\UserGroup
     * @author Ormegagenerator_lib
     */
    public function groupByEnumroleId()
    {
        \Ormega\Orm::driver(__CLASS__)
            ->group_by("ormegatest.user_group.enumrole_id");
        return $this;
    }
    
    /**
     * Add a special groupby to next find() call 
     * @return \Ormega\Mydb\Query\UserGroup
     * @author Ormegagenerator_lib
     */
    public function groupByDateinsert()
    {
        \Ormega\Orm::driver(__CLASS__)
            ->group_by("ormegatest.user_group.dateinsert");
        return $this;
    }
        
    /**
     * Add a special order to next find() call
     * @param string $order Can be \Ormega\Orm::ORDER_* constant ("ASC", "DESC")
     * @return \Ormega\Mydb\Query\UserGroup
     * @author Ormegagenerator_lib
     */
    public function orderById( $order = \Ormega\Orm::ORDER_ASC )
    {
        \Ormega\Orm::driver(__CLASS__)
            ->order_by("ormegatest.user_group.id", $order);
        return $this;
    }
        
    /**
     * Add a special order to next find() call
     * @param string $order Can be \Ormega\Orm::ORDER_* constant ("ASC", "DESC")
     * @return \Ormega\Mydb\Query\UserGroup
     * @author Ormegagenerator_lib
     */
    public function orderByUserId( $order = \Ormega\Orm::ORDER_ASC )
    {
        \Ormega\Orm::driver(__CLASS__)
            ->order_by("ormegatest.user_group.user_id", $order);
        return $this;
    }
        
    /**
     * Add a special order to next find() call
     * @param string $order Can be \Ormega\Orm::ORDER_* constant ("ASC", "DESC")
     * @return \Ormega\Mydb\Query\UserGroup
     * @author Ormegagenerator_lib
     */
    public function orderByGroupId( $order = \Ormega\Orm::ORDER_ASC )
    {
        \Ormega\Orm::driver(__CLASS__)
            ->order_by("ormegatest.user_group.group_id", $order);
        return $this;
    }
        
    /**
     * Add a special order to next find() call
     * @param string $order Can be \Ormega\Orm::ORDER_* constant ("ASC", "DESC")
     * @return \Ormega\Mydb\Query\UserGroup
     * @author Ormegagenerator_lib
     */
    public function orderByEnumroleId( $order = \Ormega\Orm::ORDER_ASC )
    {
        \Ormega\Orm::driver(__CLASS__)
            ->order_by("ormegatest.user_group.enumrole_id", $order);
        return $this;
    }
        
    /**
     * Add a special order to next find() call
     * @param string $order Can be \Ormega\Orm::ORDER_* constant ("ASC", "DESC")
     * @return \Ormega\Mydb\Query\UserGroup
     * @author Ormegagenerator_lib
     */
    public function orderByDateinsert( $order = \Ormega\Orm::ORDER_ASC )
    {
        \Ormega\Orm::driver(__CLASS__)
            ->order_by("ormegatest.user_group.dateinsert", $order);
        return $this;
    }
            
    /**
     * Start the select request composed with any filter, groupby,... set before
     * Return an array of 
     *      \Ormega\Mydb\Entity\UserGroup object 
     * @return \Ormega\EntitiesCollection
     * @author Ormegagenerator_lib
     */
    public function find()
    {
        $aReturn = new \Ormega\EntitiesCollection();
            
        \Ormega\Orm::driver(__CLASS__)
            ->select("ormegatest.user_group.id,ormegatest.user_group.user_id,ormegatest.user_group.group_id,ormegatest.user_group.enumrole_id,ormegatest.user_group.dateinsert")
            ->from("ormegatest.user_group");
           
        $sQueryCacheId = md5( \Ormega\Orm::driver(__CLASS__)->get_compiled_select(null, false) );
        
        if( $aCacheRef = \Ormega\Orm::cache()->get($sQueryCacheId) AND is_array($aCacheRef) ){    
            foreach( $aCacheRef as $sCacheRef ){
                $obj = \Ormega\Orm::cache()->get($sCacheRef);
                $aReturn[ $obj->getPkId() ] = $obj;
            }       
        }
        else {
            $aCacheRef = array();
            $query = \Ormega\Orm::driver(__CLASS__)->get();            
            foreach( $query->result() as $row ){
                
                $obj = new \Ormega\Mydb\Entity\UserGroup();
                
                $obj->setId((int) $row->id);
                $obj->setUserId((int) $row->user_id);
                $obj->setGroupId((int) $row->group_id);
                if( !is_null($row->enumrole_id) ){
                    $obj->setEnumroleId((int) $row->enumrole_id);
                }
                $obj->setDateinsert((string) $row->dateinsert);
                $obj->addCacheRef($sQueryCacheId);
                $obj->loaded(true);
                $obj->modified(false);
                
                $aReturn[ $obj->getPkId() ] = $obj;
                \Ormega\Orm::cache()->save($obj->cacheKey(), $obj, 3600);
                $aCacheRef[] = $obj->cacheKey();
            }            
            \Ormega\Orm::cache()->save($sQueryCacheId, $aCacheRef, 3600);
        }
            
        \Ormega\Orm::driver(__CLASS__)->reset_query();

        return $aReturn;
    }
    
    /**
     * Get the first result from the find() request 
     *
     * @return \Ormega\Mydb\Entity\UserGroup
     *
     * @author Ormegagenerator_lib
     */
    public function findOne(){

        $this->limit(1);
        return $this->find()->rewind();
    }
        
}