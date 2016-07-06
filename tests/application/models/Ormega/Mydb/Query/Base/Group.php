<?php 
        
namespace Ormega\Mydb\Query\Base;

class Group implements \Ormega\QueryInterface {
    
    /**
     * Get an instance of this class to chain methods 
     *      without have to use $var = new Group()
     *
     * @return \Ormega\Mydb\Query\Group
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
     * @return \Ormega\Mydb\Query\Group
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
     * @return \Ormega\Mydb\Query\Group
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
                    ->where_in("ormegatest.group.id", $value);
                break;
            case \Ormega\Orm::OPERATOR_NOTIN:
                \Ormega\Orm::driver(__CLASS__)
                    ->where_not_in("ormegatest.group.id", $value);
                break;
            case \Ormega\Orm::OPERATOR_LIKE_PC:
                \Ormega\Orm::driver(__CLASS__)
                    ->like("ormegatest.group.id", $value, "after");
                break;
            case \Ormega\Orm::OPERATOR_PC_LIKE:
                \Ormega\Orm::driver(__CLASS__)
                    ->like("ormegatest.group.id", $value, "before");
                break;
            case \Ormega\Orm::OPERATOR_PC_LIKE_PC:
                \Ormega\Orm::driver(__CLASS__)
                    ->like("ormegatest.group.id", $value, "both");
                break;
            case \Ormega\Orm::OPERATOR_ISNULL:
                \Ormega\Orm::driver(__CLASS__)
                    ->where("ormegatest.group.id IS NULL");
                break;
            case \Ormega\Orm::OPERATOR_ISNOTNULL:
                \Ormega\Orm::driver(__CLASS__)
                    ->where("ormegatest.group.id IS NOT NULL");
                break;
            default:
                \Ormega\Orm::driver(__CLASS__)
                    ->where( "ormegatest.group.id ".$operator, $value );
        }
        
        return $this;
    }
    
    /**
     * Add a special where to next find() call
     * @param mixed $value The filter value
     * @param string $operator Can be \Ormega\Orm::OPERATOR_* constant (">", "<=", "=", ...)
     * @return \Ormega\Mydb\Query\Group
     * @author Ormegagenerator_lib
     */
    public function filterByLabel( $value, $operator = \Ormega\Orm::OPERATOR_EQUALS )
    {
        if( $operator == \Ormega\Orm::OPERATOR_IN || $operator == \Ormega\Orm::OPERATOR_NOTIN ){
            if( !is_array($value) ) {
                $value = explode(",", $value);
            }
        }
        
        switch( $operator ){
            case \Ormega\Orm::OPERATOR_IN:
                \Ormega\Orm::driver(__CLASS__)
                    ->where_in("ormegatest.group.label", $value);
                break;
            case \Ormega\Orm::OPERATOR_NOTIN:
                \Ormega\Orm::driver(__CLASS__)
                    ->where_not_in("ormegatest.group.label", $value);
                break;
            case \Ormega\Orm::OPERATOR_LIKE_PC:
                \Ormega\Orm::driver(__CLASS__)
                    ->like("ormegatest.group.label", $value, "after");
                break;
            case \Ormega\Orm::OPERATOR_PC_LIKE:
                \Ormega\Orm::driver(__CLASS__)
                    ->like("ormegatest.group.label", $value, "before");
                break;
            case \Ormega\Orm::OPERATOR_PC_LIKE_PC:
                \Ormega\Orm::driver(__CLASS__)
                    ->like("ormegatest.group.label", $value, "both");
                break;
            case \Ormega\Orm::OPERATOR_ISNULL:
                \Ormega\Orm::driver(__CLASS__)
                    ->where("ormegatest.group.label IS NULL");
                break;
            case \Ormega\Orm::OPERATOR_ISNOTNULL:
                \Ormega\Orm::driver(__CLASS__)
                    ->where("ormegatest.group.label IS NOT NULL");
                break;
            default:
                \Ormega\Orm::driver(__CLASS__)
                    ->where( "ormegatest.group.label ".$operator, $value );
        }
        
        return $this;
    }
    
    /**
     * Add a special groupby to next find() call 
     * @return \Ormega\Mydb\Query\Group
     * @author Ormegagenerator_lib
     */
    public function groupById()
    {
        \Ormega\Orm::driver(__CLASS__)
            ->group_by("ormegatest.group.id");
        return $this;
    }
    
    /**
     * Add a special groupby to next find() call 
     * @return \Ormega\Mydb\Query\Group
     * @author Ormegagenerator_lib
     */
    public function groupByLabel()
    {
        \Ormega\Orm::driver(__CLASS__)
            ->group_by("ormegatest.group.label");
        return $this;
    }
        
    /**
     * Add a special order to next find() call
     * @param string $order Can be \Ormega\Orm::ORDER_* constant ("ASC", "DESC")
     * @return \Ormega\Mydb\Query\Group
     * @author Ormegagenerator_lib
     */
    public function orderById( $order = \Ormega\Orm::ORDER_ASC )
    {
        \Ormega\Orm::driver(__CLASS__)
            ->order_by("ormegatest.group.id", $order);
        return $this;
    }
        
    /**
     * Add a special order to next find() call
     * @param string $order Can be \Ormega\Orm::ORDER_* constant ("ASC", "DESC")
     * @return \Ormega\Mydb\Query\Group
     * @author Ormegagenerator_lib
     */
    public function orderByLabel( $order = \Ormega\Orm::ORDER_ASC )
    {
        \Ormega\Orm::driver(__CLASS__)
            ->order_by("ormegatest.group.label", $order);
        return $this;
    }
            
    /**
     * Start the select request composed with any filter, groupby,... set before
     * Return an array of 
     *      \Ormega\Mydb\Entity\Group object 
     * @return \Ormega\EntitiesCollection
     * @author Ormegagenerator_lib
     */
    public function find()
    {
        $aReturn = new \Ormega\EntitiesCollection();
            
        \Ormega\Orm::driver(__CLASS__)
            ->select("ormegatest.group.id,ormegatest.group.label")
            ->from("ormegatest.group");
           
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
                
                $obj = new \Ormega\Mydb\Entity\Group();
                
                $obj->setId((int) $row->id);
                $obj->setLabel((string) $row->label);
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
     * @return \Ormega\Mydb\Entity\Group
     *
     * @author Ormegagenerator_lib
     */
    public function findOne(){

        $this->limit(1);
        return $this->find()->rewind();
    }
        
}