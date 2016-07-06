<?php 
        
namespace Ormega\Mydb\Query\Base;

class User implements \Ormega\QueryInterface {
    
    /**
     * Get an instance of this class to chain methods 
     *      without have to use $var = new User()
     *
     * @return \Ormega\Mydb\Query\User
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
     * @return \Ormega\Mydb\Query\User
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
     * @return \Ormega\Mydb\Query\User
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
                    ->where_in("ormegatest.user.id", $value);
                break;
            case \Ormega\Orm::OPERATOR_NOTIN:
                \Ormega\Orm::driver(__CLASS__)
                    ->where_not_in("ormegatest.user.id", $value);
                break;
            case \Ormega\Orm::OPERATOR_LIKE_PC:
                \Ormega\Orm::driver(__CLASS__)
                    ->like("ormegatest.user.id", $value, "after");
                break;
            case \Ormega\Orm::OPERATOR_PC_LIKE:
                \Ormega\Orm::driver(__CLASS__)
                    ->like("ormegatest.user.id", $value, "before");
                break;
            case \Ormega\Orm::OPERATOR_PC_LIKE_PC:
                \Ormega\Orm::driver(__CLASS__)
                    ->like("ormegatest.user.id", $value, "both");
                break;
            case \Ormega\Orm::OPERATOR_ISNULL:
                \Ormega\Orm::driver(__CLASS__)
                    ->where("ormegatest.user.id IS NULL");
                break;
            case \Ormega\Orm::OPERATOR_ISNOTNULL:
                \Ormega\Orm::driver(__CLASS__)
                    ->where("ormegatest.user.id IS NOT NULL");
                break;
            default:
                \Ormega\Orm::driver(__CLASS__)
                    ->where( "ormegatest.user.id ".$operator, $value );
        }
        
        return $this;
    }
    
    /**
     * Add a special where to next find() call
     * @param mixed $value The filter value
     * @param string $operator Can be \Ormega\Orm::OPERATOR_* constant (">", "<=", "=", ...)
     * @return \Ormega\Mydb\Query\User
     * @author Ormegagenerator_lib
     */
    public function filterByLogin( $value, $operator = \Ormega\Orm::OPERATOR_EQUALS )
    {
        if( $operator == \Ormega\Orm::OPERATOR_IN || $operator == \Ormega\Orm::OPERATOR_NOTIN ){
            if( !is_array($value) ) {
                $value = explode(",", $value);
            }
        }
        
        switch( $operator ){
            case \Ormega\Orm::OPERATOR_IN:
                \Ormega\Orm::driver(__CLASS__)
                    ->where_in("ormegatest.user.login", $value);
                break;
            case \Ormega\Orm::OPERATOR_NOTIN:
                \Ormega\Orm::driver(__CLASS__)
                    ->where_not_in("ormegatest.user.login", $value);
                break;
            case \Ormega\Orm::OPERATOR_LIKE_PC:
                \Ormega\Orm::driver(__CLASS__)
                    ->like("ormegatest.user.login", $value, "after");
                break;
            case \Ormega\Orm::OPERATOR_PC_LIKE:
                \Ormega\Orm::driver(__CLASS__)
                    ->like("ormegatest.user.login", $value, "before");
                break;
            case \Ormega\Orm::OPERATOR_PC_LIKE_PC:
                \Ormega\Orm::driver(__CLASS__)
                    ->like("ormegatest.user.login", $value, "both");
                break;
            case \Ormega\Orm::OPERATOR_ISNULL:
                \Ormega\Orm::driver(__CLASS__)
                    ->where("ormegatest.user.login IS NULL");
                break;
            case \Ormega\Orm::OPERATOR_ISNOTNULL:
                \Ormega\Orm::driver(__CLASS__)
                    ->where("ormegatest.user.login IS NOT NULL");
                break;
            default:
                \Ormega\Orm::driver(__CLASS__)
                    ->where( "ormegatest.user.login ".$operator, $value );
        }
        
        return $this;
    }
    
    /**
     * Add a special where to next find() call
     * @param mixed $value The filter value
     * @param string $operator Can be \Ormega\Orm::OPERATOR_* constant (">", "<=", "=", ...)
     * @return \Ormega\Mydb\Query\User
     * @author Ormegagenerator_lib
     */
    public function filterByPassword( $value, $operator = \Ormega\Orm::OPERATOR_EQUALS )
    {
        if( $operator == \Ormega\Orm::OPERATOR_IN || $operator == \Ormega\Orm::OPERATOR_NOTIN ){
            if( !is_array($value) ) {
                $value = explode(",", $value);
            }
        }
        
        switch( $operator ){
            case \Ormega\Orm::OPERATOR_IN:
                \Ormega\Orm::driver(__CLASS__)
                    ->where_in("ormegatest.user.password", $value);
                break;
            case \Ormega\Orm::OPERATOR_NOTIN:
                \Ormega\Orm::driver(__CLASS__)
                    ->where_not_in("ormegatest.user.password", $value);
                break;
            case \Ormega\Orm::OPERATOR_LIKE_PC:
                \Ormega\Orm::driver(__CLASS__)
                    ->like("ormegatest.user.password", $value, "after");
                break;
            case \Ormega\Orm::OPERATOR_PC_LIKE:
                \Ormega\Orm::driver(__CLASS__)
                    ->like("ormegatest.user.password", $value, "before");
                break;
            case \Ormega\Orm::OPERATOR_PC_LIKE_PC:
                \Ormega\Orm::driver(__CLASS__)
                    ->like("ormegatest.user.password", $value, "both");
                break;
            case \Ormega\Orm::OPERATOR_ISNULL:
                \Ormega\Orm::driver(__CLASS__)
                    ->where("ormegatest.user.password IS NULL");
                break;
            case \Ormega\Orm::OPERATOR_ISNOTNULL:
                \Ormega\Orm::driver(__CLASS__)
                    ->where("ormegatest.user.password IS NOT NULL");
                break;
            default:
                \Ormega\Orm::driver(__CLASS__)
                    ->where( "ormegatest.user.password ".$operator, $value );
        }
        
        return $this;
    }
    
    /**
     * Add a special groupby to next find() call 
     * @return \Ormega\Mydb\Query\User
     * @author Ormegagenerator_lib
     */
    public function groupById()
    {
        \Ormega\Orm::driver(__CLASS__)
            ->group_by("ormegatest.user.id");
        return $this;
    }
    
    /**
     * Add a special groupby to next find() call 
     * @return \Ormega\Mydb\Query\User
     * @author Ormegagenerator_lib
     */
    public function groupByLogin()
    {
        \Ormega\Orm::driver(__CLASS__)
            ->group_by("ormegatest.user.login");
        return $this;
    }
    
    /**
     * Add a special groupby to next find() call 
     * @return \Ormega\Mydb\Query\User
     * @author Ormegagenerator_lib
     */
    public function groupByPassword()
    {
        \Ormega\Orm::driver(__CLASS__)
            ->group_by("ormegatest.user.password");
        return $this;
    }
        
    /**
     * Add a special order to next find() call
     * @param string $order Can be \Ormega\Orm::ORDER_* constant ("ASC", "DESC")
     * @return \Ormega\Mydb\Query\User
     * @author Ormegagenerator_lib
     */
    public function orderById( $order = \Ormega\Orm::ORDER_ASC )
    {
        \Ormega\Orm::driver(__CLASS__)
            ->order_by("ormegatest.user.id", $order);
        return $this;
    }
        
    /**
     * Add a special order to next find() call
     * @param string $order Can be \Ormega\Orm::ORDER_* constant ("ASC", "DESC")
     * @return \Ormega\Mydb\Query\User
     * @author Ormegagenerator_lib
     */
    public function orderByLogin( $order = \Ormega\Orm::ORDER_ASC )
    {
        \Ormega\Orm::driver(__CLASS__)
            ->order_by("ormegatest.user.login", $order);
        return $this;
    }
        
    /**
     * Add a special order to next find() call
     * @param string $order Can be \Ormega\Orm::ORDER_* constant ("ASC", "DESC")
     * @return \Ormega\Mydb\Query\User
     * @author Ormegagenerator_lib
     */
    public function orderByPassword( $order = \Ormega\Orm::ORDER_ASC )
    {
        \Ormega\Orm::driver(__CLASS__)
            ->order_by("ormegatest.user.password", $order);
        return $this;
    }
            
    /**
     * Start the select request composed with any filter, groupby,... set before
     * Return an array of 
     *      \Ormega\Mydb\Entity\User object 
     * @return \Ormega\EntitiesCollection
     * @author Ormegagenerator_lib
     */
    public function find()
    {
        $aReturn = new \Ormega\EntitiesCollection();
            
        \Ormega\Orm::driver(__CLASS__)
            ->select("ormegatest.user.id,ormegatest.user.login,ormegatest.user.password")
            ->from("ormegatest.user");
           
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
                
                $obj = new \Ormega\Mydb\Entity\User();
                
                $obj->setId((int) $row->id);
                $obj->setLogin((string) $row->login);
                $obj->setPassword((string) $row->password);
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
     * @return \Ormega\Mydb\Entity\User
     *
     * @author Ormegagenerator_lib
     */
    public function findOne(){

        $this->limit(1);
        return $this->find()->rewind();
    }
        
}