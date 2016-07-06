<?php
            
namespace Ormega;

class EntitiesCollection implements \ArrayAccess, \Iterator {

    /**
     * @var array Array of entities
     */
    protected $aEntities = array();
            
    /**
     * Constructor
     * @author Ormegagenerator_lib
     */
    public function __construct() 
    {
        $this->aEntities = array();
    }
    
    /**
     * Get all keys of the entities array 
     * @return array
     * @author Ormegagenerator_lib
     */
    public function getArrayKeys()
    {
        return array_keys( $this->aEntities );
    }
    
    /**
     * Is the collection empty ?
     * @return bool
     * @author Ormegagenerator_lib
     */
    public function isEmpty() 
    {
        return empty( $this->aEntities );
    }
    
    
    /**
     * Execute a function on every elements
     *
     * @param string $sMethodName Method name
     * @pararm array $aArgs Method arguments
     *
     * @return array
     *
     * @author Matthieu Dos Santos <m.dossantos@santiane.fr>
     */
    public function __call( $sMethodName, array $aArgs = array() )
    {
        $aReturn = array();
        
        foreach ( $this->aEntities as $oEntity ){
            if( $oEntity instanceof \Ormega\EntitiesCollection ){
                $aReturn[] = call_user_func_array( array($oEntity, $sMethodName), $aArgs);
            }
            elseif( method_exists($oEntity, $sMethodName) )
                $aReturn[ $oEntity->getPkId() ] = call_user_func_array( array($oEntity, $sMethodName), $aArgs);
        }
        return $aReturn;
    }
        
    /**
     * Check if value is correct to be setted in this collection
     * It must be an array (possibly of array of array...) of \Ormega\EntityInterface
     *
     * @param mixed $value
     *
     * @return bool true if valid
     * @throws \InvalidArgumentException If unvalid
     *
     * @author Ormegagenerator_lib
     */
    protected function validSetter( $value )
    {
        if( $value instanceof \Ormega\EntityInterface || $value instanceof \Ormega\EntitiesCollection ){
            return true;
        }
        else {
            throw new \InvalidArgumentException("Entity collection expect an \Ormega\EntityInterface as element or an array of \Ormega\EntityInterface");
        }
    }

    /**
     * Replace l'itérateur sur le premier élément
     *
     * @abstracting Iterator
     * @author Ormegagenerator_lib
     */
    function rewind() 
    {
        return reset($this->aEntities);
    }

    /**
     * Retourne l'élément courant
     *
     * @return mixed
     *
     * @abstracting Iterator
     * @author Ormegagenerator_lib
     */
    function current() 
    {
        return current($this->aEntities);
    }

    /**
     * Retourne la clé de l'élément courant
     *
     * @return int
     *
     * @abstracting Iterator
     * @author Ormegagenerator_lib
     */
    function key() 
    {
        return key($this->aEntities);
    }

    /**
     * Se déplace sur l'élément suivant
     *
     * @abstracting Iterator
     * @author Ormegagenerator_lib
     */
    function next() 
    {
        return next($this->aEntities);
    }

    /**
     * Vérifie si la position courante est valide
     *
     * @return bool
     *
     * @abstracting Iterator
     * @author Ormegagenerator_lib
     */
    function valid() 
    {
        return key($this->aEntities) !== null;
    }

    /**
     * Assigne une valeur à une position donnée
     *
     * @param mixed $offset La position à laquelle assigner une valeur.
     * @param mixed $value La valeur à assigner.
     *
     * @abstracting ArrayAccess
     * @author Ormegagenerator_lib
     */
    public function offsetSet($offset, $value) 
    {
        if( $this->validSetter($value) ){             
            if (is_null($offset)) {
                $this->aEntities[] = & $value;
            } else {
                $this->aEntities[$offset] = & $value;
            }
        }
    }   

    /**
     * Retourne la valeur à la position donnée.
     * Cette méthode est exécutée lorsque l'on vérifie si une position est empty().
     *
     * @param mixed $offset La position à lire.
     *
     * @return \Ormega\EntityInterface|null
     *
     * @abstracting ArrayAccess
     * @author Ormegagenerator_lib
     */
    public function offsetGet($offset) 
    {
        return $this->offsetExists($offset) ? $this->aEntities[$offset] : null;
    }

    /**
     * Indique si une position existe dans un tableau
     * Cette méthode est exécutée lorsque la fonction isset() ou empty()
     * est appliquée à un objet qui implémente l'interface ArrayAccess.
     *
     * @param mixed $offset Une position à vérifier.
     *
     * @return bool Cette fonction retourne TRUE en cas de succès ou FALSE si une erreur survient.
     *
     * @abstracting ArrayAccess
     * @author Ormegagenerator_lib
     */
    public function offsetExists($offset) 
    {
        return isset($this->aEntities[$offset]);
    }

    /**
     * Supprime un élément à une position donnée
     *
     * @param mixed $offset La position à supprimer.
     *
     * @abstracting ArrayAccess
     * @author Ormegagenerator_lib
     */
    public function offsetUnset($offset) 
    {
        if( $this->offsetExists($offset) ) {
            unset($this->aEntities[$offset]);
        }
    }
}