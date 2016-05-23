<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Ormega\Generator
 *
 * @package  Ormega
 * @category External
 * @version  20160411
 */
class Ormegagenerator_lib
{
    public $sBasePath = '';
    public $sTableFilter = '.*';

    public $sqlQuote = '"';
    public $sqlEscQuote = '\'';

    public $verbose = true;

    protected $aDb;
    protected $db;

    protected $aTables;
    protected $aCols;
    protected $aKeys;
    protected $aPrimaryKeys;
    protected $aForeignKeys;
    protected $aFiles = array();

    protected $sDatabase = 'database';

    protected $sDirBase = 'Ormega';
    protected $sDirEntity = 'Entity';
    protected $sDirPrivate = 'Base';
    protected $sDirEnum = 'Enum';
    protected $sDirQuery = 'Query';

    /**
     * Generator constructor.
     *
     * @param array $config array containing all configs needed
     *                      'db' => database driver link
     *                      'table_filter' => regular expression to filter tables for which we want generate models
     *                      'path' => relative path where to generate models (relative to app root)
     *                      'namespace' => relative path where to generate models (relative to app root)
     *
     * @author Matthieu Dos Santos <m.dossantos@santiane.fr>
     */
    public function __construct( array $config )
    {
        if( empty( $config['databases'] ) || !is_array($config['databases']) ){
            throw new InvalidArgumentException('Array expected for config["tables"]');
        }

        foreach ( $config['databases'] as $aDbConf ) {
            if ( !is_a($aDbConf['db'], 'CI_DB_driver') ) {
                throw new InvalidArgumentException('Instance of CI_DB_driver needed to start the model generator');
            }
        }

        $this->aDb = $config['databases'];

        if( isset($config['path']) ) {}
            $this->sBasePath = $config['path'];

        if( isset($config['namespace']) ) {}
            $this->sDirBase = $config['namespace'];

    }

    /**
     * Start method to start the php files generation
     *
     * @throws \Exception
     *
     * @author Matthieu Dos Santos <m.dossantos@santiane.fr>
     */
    public function run()
    {
        $this->output('Start @ ' . date('Y-m-d H:i:s'));

        $this->aFiles[] = array(
            'file'    => $this->sDirBase . '/Orm.php',
            'content' => $this->genOrm(),
            'erase'   => true,
        );

        $this->aFiles[] = array(
            'file'    => $this->sDirBase . '/EntitiesCollection.php',
            'content' => $this->genEntitiesCollection(),
            'erase'   => true,
        );

        $this->aFiles[] = array(
            'file'    => $this->sDirBase . '/EntityInterface.php',
            'content' => $this->genEntityInterface(),
            'erase'   => true,
        );

        $this->aFiles[] = array(
            'file'    => $this->sDirBase . '/QueryInterface.php',
            'content' => $this->genQueryInterface(),
            'erase'   => true,
        );

        $this->aFiles[] = array(
            'file'    => $this->sDirBase . '/EnumInterface.php',
            'content' => $this->genEnumInterface(),
            'erase'   => true,
        );

        foreach ( $this->aDb as $sDatabase => $aDb ) {

            $this->db = $aDb['db'];
            $this->sDatabase = ucfirst($sDatabase);
            $this->sTableFilter = $aDb['filter'];

            $this->output('Config : Filter tables with regular expression "'. $this->sTableFilter .'"');
            $this->output('Config : Generate models in "'. $this->sBasePath . $this->sDirBase .'/'. $this->sDatabase .'"');

            $this->getTables();

            foreach ( $this->aTables as $sTable ) {

                $this->getFields($sTable);
                $this->getKeys($sTable);

                if ( strpos($sTable, 'enum') === 0 ) {

                    $this->aFiles[] = array(
                        'file'    => $this->sDirBase . '/' . $this->sDatabase . '/' . $this->sDirEnum . '/' . $this->formatPhpClassName($sTable) . '.php',
                        'content' => $this->genFileEnum($sTable),
                        'erase'   => true,
                    );
                }
                else {

                    $this->aFiles[] = array(
                        'file'    => $this->sDirBase . '/' . $this->sDatabase . '/' . $this->sDirEntity . '/' . $this->formatPhpClassName($sTable) . '.php',
                        'content' => $this->genFileEntity($sTable),
                        'erase'   => false,
                    );

                    $this->aFiles[] = array(
                        'file'    => $this->sDirBase . '/' . $this->sDatabase . '/' . $this->sDirEntity . '/' . $this->sDirPrivate . '/' . $this->formatPhpClassName($sTable) . '.php',
                        'content' => $this->genFileEntityPrivate($sTable),
                        'erase'   => true,
                    );

                    $this->aFiles[] = array(
                        'file'    => $this->sDirBase . '/' . $this->sDatabase . '/' . $this->sDirQuery . '/' . $this->formatPhpClassName($sTable) . '.php',
                        'content' => $this->genFileQuery($sTable),
                        'erase'   => false,
                    );

                    $this->aFiles[] = array(
                        'file'    => $this->sDirBase . '/' . $this->sDatabase . '/' . $this->sDirQuery . '/' . $this->sDirPrivate . '/' . $this->formatPhpClassName($sTable) . '.php',
                        'content' => $this->genFileQueryPrivate($sTable),
                        'erase'   => true,
                    );
                }
            }

            $this->genFiles();
        }

        $this->output('End @ ' . date('Y-m-d H:i:s'));
    }

    protected function output( $print )
    {
        if ( $this->verbose ) {
            echo '<div style="border:1px solid black; margin: 2px; padding: 5px">' . $print . '</div>';
        }
    }

    /**
     * Get table list
     *
     * @author Matthieu Dos Santos <m.dossantos@santiane.fr>
     */
    protected function getTables()
    {
        $this->aTables = array();
        $this->aCols = array();
        $this->aKeys = array();
        $this->aPrimaryKeys = array();
        $this->aForeignKeys = array();

        $query = $this->db->query('SHOW TABLES');
        foreach( $query->result_array() as $aTable ) {
            $aTable = array_values($aTable);
            if ( empty($this->sTableFilter) || preg_match('/' . $this->sTableFilter . '/', $aTable[0]) )
                $this->aTables[] = $aTable[0];
        }

        $this->output('Table list : ' . implode(' ; ', $this->aTables));
    }



    /**
     * Get field list for a table
     *
     * @param string $sTable Table name
     *
     * @author Matthieu Dos Santos <m.dossantos@santiane.fr>
     */
    protected function getFields( $sTable )
    {
        $this->aCols[ $sTable ] = array();
        $query = $this->db->query('SHOW COLUMNS FROM `' . $sTable . '`');
        foreach ( $query->result_array() as $aCol ) {
            $this->aCols[ $sTable ][ $aCol['Field'] ] = $aCol;
        }
    }

    /**
     * Get indexes for a table
     *
     * @param string $sTable Table name
     *
     * @author Matthieu Dos Santos <m.dossantos@santiane.fr>
     */
    protected function getKeys( $sTable )
    {
        $this->aKeys[ $sTable ] = array();
        $this->aPrimaryKeys[ $sTable ] = array();
        $this->aForeignKeys[ $sTable ] = array();

        $query = $this->db->query(
            "SELECT COLUMN_NAME, CONSTRAINT_NAME, REFERENCED_COLUMN_NAME, REFERENCED_TABLE_NAME
                  FROM information_schema.KEY_COLUMN_USAGE
                  WHERE TABLE_NAME = '$sTable'"
        );
        foreach ( $query->result_array() as $aKey ) {
            if( isset($this->aCols[ $sTable ][ $aKey['COLUMN_NAME'] ]) ) {
                $this->aKeys[ $sTable ][ $aKey['COLUMN_NAME'] ] = $aKey;
                if ( $aKey['CONSTRAINT_NAME'] == 'PRIMARY' )
                    $this->aPrimaryKeys[ $sTable ][ $aKey['COLUMN_NAME'] ] = $aKey;

                if ( $this->isGenerableForeignKey($sTable, $this->aCols[ $sTable ][ $aKey['COLUMN_NAME'] ]) )
                    $this->aForeignKeys[ $sTable ][ $aKey['COLUMN_NAME'] ] = $aKey;
            }
        }
    }

    /**
     * Check if a Column is a foreign key
     * and if we can map it with an Entity object of the referenced table
     *
     * @param string $sTable Table name
     * @param array $aCol Sql column infos
     *
     * @return bool
     *
     * @author Matthieu Dos Santos <m.dossantos@santiane.fr>
     */
    protected function isGenerableForeignKey( $sTable, array $aCol )
    {
        return
            isset($this->aKeys[ $sTable ][ $aCol['Field'] ])
            && !empty($this->aKeys[ $sTable ][ $aCol['Field'] ]['REFERENCED_COLUMN_NAME'])
            && !empty($this->aKeys[ $sTable ][ $aCol['Field'] ]['REFERENCED_TABLE_NAME'])
            && !isset($this->aPrimaryKeys[ $sTable ][ $aCol['Field'] ])
            && strpos($aCol['Field'], 'enum') === false;
    }

    /**
     * Generate the code for the bridge between the app and the generated
     * classes
     *
     * @return string
     *
     * @author Matthieu Dos Santos <m.dossantos@santiane.fr>
     */
    protected function genOrm()
    {
        return '<?php
            
namespace ' . $this->sDirBase . ';
        
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
     * Get the database driver set in the init() method
     *
     * @param string $sClassName Classname of the calling class, used to determine which connection use in case of multiple database connections
     *
     * @return CI_DB_driver
     *
     * @author ' . __CLASS__ . '
     */
    public static function driver( $sClassName )
    {
        $aClass = explode("\\\", $sClassName);

        if( isset($aClass[1]) && isset( self::$aDb[ $aClass[1] ] ) )
            return self::$aDb[ $aClass[1] ];
        else
            return reset(self::$aDb);
    }
    
    /**
     * Initiate the orm with a database connection (can be adapted to any driver
     *      as long as it implement the \Ormega\DbInterface interface).
     * Define an autoload for all Ormega generated classes
     *
     * @param array $aDb Array of CI_DB_driver objects
     * @return void
     *
     * @author ' . __CLASS__ . '
     */
    public static function init(array $aDb)
    {
        self::$aDb = array();
        foreach ( $aDb as $sDatabase => $aDbDriver ) {
            if ( !is_a($aDbDriver, "CI_DB_driver") ) {
                throw new \\InvalidArgumentException("Array of CI_DB_driver objects expected for " . __METHOD__);
            }
            self::$aDb[ ucfirst($sDatabase) ] = $aDbDriver;
        }

        spl_autoload_register(function($class){
            $aPaths = explode("\\\", $class);

            if( isset($aPaths[0]) && $aPaths[0] == __NAMESPACE__ ) {

                $basepath = __DIR__."/";
                if( isset($aPaths[1]) && isset( self::$aDb[ $aPaths[1] ] ) ) {
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
    }
}
';

    }

    protected function genEntitiesCollection(){
        return '<?php
            
namespace ' . $this->sDirBase . ';

class EntitiesCollection implements \ArrayAccess, \Iterator {

    /**
     * @var array Array of entities
     */
    protected $aEntities = array();
            
    /**
     * Constructor
     * @author ' . __CLASS__ . '
     */
    public function __construct() 
    {
        $this->aEntities = array();
    }
    
    /**
     * Get all keys of the entities array 
     */
    public function getArrayKeys()
    {
        return array_keys( $this->aEntities );
    }
    
    /**
     * Execute a function on every elements
     *
     * @param string $sMethodName Method name
     *
     * @return array
     *
     * @author Matthieu Dos Santos <m.dossantos@santiane.fr>
     */
    public function __call( $sMethodName, array $aArgs = array() )
    {
        $aReturn = array();
        foreach ( $this->aEntities as $oEntity ){
            if( method_exists($oEntity, $sMethodName) )
                $aReturn[ $oEntity->getPkId() ] = call_user_func_array( array($oEntity, $sMethodName), $aArgs);
        }
        return $aReturn;
    }

    /**
     * Replace l\'itérateur sur le premier élément
     *
     * @abstracting Iterator
     * @author ' . __CLASS__ . '
     */
    function rewind() 
    {
        return reset($this->aEntities);
    }

    /**
     * Retourne l\'élément courant
     *
     * @return mixed
     *
     * @abstracting Iterator
     * @author ' . __CLASS__ . '
     */
    function current() 
    {
        return current($this->aEntities);
    }

    /**
     * Retourne la clé de l\'élément courant
     *
     * @return int
     *
     * @abstracting Iterator
     * @author ' . __CLASS__ . '
     */
    function key() 
    {
        return key($this->aEntities);
    }

    /**
     * Se déplace sur l\'élément suivant
     *
     * @abstracting Iterator
     * @author ' . __CLASS__ . '
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
     * @author ' . __CLASS__ . '
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
     * @author ' . __CLASS__ . '
     */
    public function offsetSet($offset, $value) 
    {
        if( $value instanceof \Ormega\EntityInterface ){             
            if (is_null($offset)) {
                $this->aEntities[] = & $value;
            } else {
                $this->aEntities[$offset] = & $value;
            }
        }
    }   

    /**
     * Retourne la valeur à la position donnée.
     * Cette méthode est exécutée lorsque l\'on vérifie si une position est empty().
     *
     * @param mixed $offset La position à lire.
     *
     * @return \Ormega\EntityInterface|null
     *
     * @abstracting ArrayAccess
     * @author ' . __CLASS__ . '
     */
    public function offsetGet($offset) 
    {
        return $this->offsetExists($offset) ? $this->aEntities[$offset] : null;
    }

    /**
     * Indique si une position existe dans un tableau
     * Cette méthode est exécutée lorsque la fonction isset() ou empty()
     * est appliquée à un objet qui implémente l\'interface ArrayAccess.
     *
     * @param mixed $offset Une position à vérifier.
     *
     * @return bool Cette fonction retourne TRUE en cas de succès ou FALSE si une erreur survient.
     *
     * @abstracting ArrayAccess
     * @author ' . __CLASS__ . '
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
     * @author ' . __CLASS__ . '
     */
    public function offsetUnset($offset) 
    {
        if( $this->offsetExists($offset) ) {
            unset($this->aEntities[$offset]);
        }
    }
}';
    }

    protected function genEntityInterface() {
        return '<?php
            
namespace ' . $this->sDirBase . ';

interface EntityInterface {
    
    /**
     * @return int Return the primary key ID
     */
    public function getPkId();
}
';
    }

    protected function genQueryInterface() {
        return '<?php
            
namespace ' . $this->sDirBase . ';

interface QueryInterface {
}
';
    }

    protected function genEnumInterface() {
        return '<?php
            
namespace ' . $this->sDirBase . ';

interface EnumInterface {
}
';
    }

    /**
     * Gen code for specifics table beginning with "enum" in is name
     * This classe will only content CONSTANTS
     * The "enum" table must contains 3 fields :
     *      - id
     *      - label
     *      - constant
     *
     * Each constant is a row from the db and is formatted as
     *      CONST *constant* = *id*
     *
     * This allow increased code readability and maintenance
     *
     * @param string $sTable
     *
     * @return string
     *
     * @author Matthieu Dos Santos <m.dossantos@santiane.fr>
     */
    protected function genFileEnum( $sTable )
    {
        $pattern = '/([^\w])/';
        $sClassName = $this->formatPhpClassName($sTable);

        $query = $this->db->query("SELECT id, label, constant FROM `$sTable`");

        $aConstants = array();
        foreach ( $query->result_array() as $aData ) {
            $aData['constant'] = strtoupper( preg_replace($pattern, '', $aData['constant']) );
            $aConstants[] = $aData;
        }

        $php = '<?php 
        
namespace ' . $this->sDirBase . '\\' . $this->sDatabase . '\\' . $this->sDirEnum . ';

class ' . $sClassName . ' implements \Ormega\EnumInterface {
';
        foreach ( $aConstants as $aConstant ) {
            $php .= '
    
    /**
     * @var int ' . $aConstant['label'] . '
     */
    const ' . strtoupper($aConstant['constant']) . ' = ' . $aConstant['id'] . ';';
        }

        $php .= '
    
    /**
     * Get an ID from a string constant
     * @param string $sConstant
     * @return int
     * @author ' . __CLASS__ . '
     */
    public static function getId( $sConstant )
    {
        switch( strtoupper($sConstant) ){';

        foreach ( $aConstants as $aConstant ) {
            $php .= '
            case "'. $aConstant['constant'] .'":
                return self::'. $aConstant['constant'] .';
                break;';
        }

        $php .= '
            default:
                return 0;
        }
    }
    
    /**
     * Get all the constants in a array form
     * @return array
     * @author ' . __CLASS__ . '
     */
    public static function getArray()
    {
        return array(
            ';

        foreach ( $aConstants as $aConstant ) {
            $php .= '"' . $aConstant['constant'] . '" => array("id"=>"' . $aConstant['id'] . '", "label"=>"' . $aConstant['label'] . '", "constant"=>"' . $aConstant['constant'] . '"),';
        }

        $php .= '
        );
    }    
    
    /**
     * The label (description) associated with one ID
     * @param int $nId Constant ID
     * @return string
     * @author ' . __CLASS__ . '
     */
    public static function getLabel( $nId ){
        
        $aLabels = array(';

        foreach ( $aConstants as $aConstant ) {
            $php .= '
            '.$aConstant['id'] . ' => "' . $aConstant['label'] .'",';
        }
        $php .= '
        );
        
        return isset($aLabels[ $nId ])? $aLabels[ $nId ] : "";
    }
    
    /**
     * The constant string associated with one ID
     * @param int $nId Constant ID
     * @return string
     * @author ' . __CLASS__ . '
     */
    public static function getConstant( $nId ){
        
        $aConstants = array(';

        foreach ( $aConstants as $aConstant ) {
            $php .= '
            '.$aConstant['id'] . ' => "' . $aConstant['constant'] .'",';
        }
        $php .= '
        );
        
        return isset($aConstants[ $nId ])? $aConstants[ $nId ] : "";
    }
}';

        return $php;
    }

    /**
     * Generate the "public" entity class
     * The entity class is made to "emulate" a table in php
     * Each Entity object represent one row
     * This classes are used to save data (insert and update)
     *
     * The "public" file can be freely modified by end user as it's not
     * overwritten if it already exists
     *
     * @param string $sTable
     *
     * @return string
     *
     * @author Matthieu Dos Santos <m.dossantos@santiane.fr>
     */
    protected function genFileEntity( $sTable )
    {
        $sClassName = $this->formatPhpClassName($sTable);

        $php = '<?php 
        
namespace ' . $this->sDirBase . '\\' . $this->sDatabase . '\\' . $this->sDirEntity . ';

class ' . $sClassName . ' extends ' . $this->sDirPrivate . '\\' . $sClassName . ' {
            
           
}';

        return $php;
    }

    /**
     *
     *
     * @param string $sTable
     *
     * @return string
     *
     * @author Matthieu Dos Santos <m.dossantos@santiane.fr>
     */
    protected function genFileEntityPrivate( $sTable )
    {
        $sClassName = $this->formatPhpClassName($sTable);

        $php = '<?php 
        
namespace ' . $this->sDirBase . '\\' . $this->sDatabase . '\\' . $this->sDirEntity . '\\' . $this->sDirPrivate . ';

class ' . $sClassName . ' implements \Ormega\EntityInterface {
            
    /**
     * @var bool $_isLoadedFromDb for intern usage : let know the class data comes from db or not 
     */
    protected $_isLoadedFromDb;
    
    /**
     * @var bool $_isModified for intern usage : let know the class if data changed from last save
     */
    protected $_isModified;
';
        foreach ( $this->aCols[ $sTable ] as $aCol ) {
            $php .= $this->genAttribute($aCol);
        }

        foreach ( $this->aForeignKeys[ $sTable ] as $sKeyName => $aKey ) {
            $php .= $this->genForeignAttribute($sTable, $this->aCols[ $sTable ][ $sKeyName ]);
        }

        $aPks = array();
        foreach ($this->aPrimaryKeys[ $sTable ] as $aPk) {
            $aPks[] = '$this->get'.$this->formatPhpFuncName($aPk['COLUMN_NAME']).'()';
        }

        $php .= $this->genConstructor($sClassName);
        $php .= '
        
    /**
     * Check if the model is loaded from bdd
     * @param boolean $bLoaded
     * @return boolean
     * @author ' . __CLASS__ . '
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
     * @author ' . __CLASS__ . '
     */
    public function modified( $bModified = null )
    {
        if (!is_null($bModified) && is_bool($bModified)) {
            $this->_isModified = $bModified;
        }

        return $this->_isModified;
    }
    
    /**
     * Get a unique identifier composed by all primary keys 
     * with "-" separator
     *
     * @return string
     * 
     * @abstracted \Ormega\EntityInterface
     * @author ' . __CLASS__ . '
     */
    public function getPkId(){
        return (string)'.implode('."-".', $aPks).';
    }
    
    ';

        foreach ( $this->aCols[ $sTable ] as $aCol ) {
            $php .= $this->genGetter($aCol);
        }
        foreach ( $this->aForeignKeys[ $sTable ] as $sKeyName => $aKey ) {
            $php .= $this->genGetterForeignKey($sTable, $this->aCols[ $sTable ][ $sKeyName ]);
        }
        foreach ( $this->aCols[ $sTable ] as $aCol ) {
            $php .= $this->genSetter($sTable, $aCol);
        }
        foreach ( $this->aForeignKeys[ $sTable ] as $sKeyName => $aKey ) {
            $php .= $this->genSetterForeignKey($sTable, $this->aCols[ $sTable ][ $sKeyName ]);
        }

        $php .= $this->genSave($sTable);

        $php .= '
        
}';

        return $php;
    }

    protected function genConstructor($sClassName)
    {
        $php = '
    /**
     * ' . $sClassName . ' constructor
     * @return void
     * @author ' . __CLASS__ . '
     */
    public function __construct()
    {       
        $this->_isLoadedFromDb  = false;
        $this->_isModified      = false;
    }';

        return $php;
    }

    protected function genAttribute( array $aCol )
    {
        $sAttrName = $this->formatPhpAttrName($aCol['Field']);
        $sType = $this->getPhpType($aCol);

        $php = '
    /**
     * @var ' . $sType . ' $' . $sAttrName . ' Null:' . $aCol['Null'] . ' Maxlenght:' . $this->getMaxLength($aCol) . ' ' . $aCol['Extra'] . '
     */
    protected $' . $sAttrName . ';
        ';

        return $php;
    }

    public function genForeignAttribute( $sTable, array $aCol )
    {
        $aFK = $this->aKeys[ $sTable ][ $aCol['Field'] ];
        $sObjAttrName = $this->formatPhpForeignAttrName($aCol['Field']);

        $sType = '\\' . $this->sDirBase
            . '\\' . $this->sDatabase
            . '\\' . $this->sDirEntity
            . '\\' . $this->formatPhpClassName($aFK['REFERENCED_TABLE_NAME']);

        $php = '
    /**
     * @var ' . $sType . ' $' . $sObjAttrName . ' Null:' . $aCol['Null'] . ' ' . $aCol['Extra'] . '
     */
    protected $' . $sObjAttrName . ';
        ';

        return $php;
    }

    protected function genGetter( array $aCol )
    {
        $sFuncName = $this->formatPhpFuncName($aCol['Field']);
        $sAttrName = $this->formatPhpAttrName($aCol['Field']);

        $php = '
    
    /**
     * @return ' . $this->getPhpType($aCol) . '
     * @author ' . __CLASS__ . '
     */
    public function get' . $sFuncName . '()
    {
        return $this->' . $sAttrName . ';
    }';

        return $php;
    }

    public function genGetterForeignKey( $sTable, array $aCol )
    {
        $aFK = $this->aKeys[ $sTable ][ $aCol['Field'] ];

        $sObjFuncName = $this->formatPhpForeignFuncName($aCol['Field']);
        $sObjAttrName = $this->formatPhpForeignAttrName($aCol['Field']);

        $sType = '\\' . $this->sDirBase
            . '\\' . $this->sDatabase
            . '\\' . $this->sDirEntity
            . '\\' . $this->formatPhpClassName($aFK['REFERENCED_TABLE_NAME']);

        $php = '
    
    /**
     * @return ' . $sType . '
     * @author ' . __CLASS__ . '
     */
    public function get' . $sObjFuncName . '()
    {
        if( is_null($this->'.$sObjAttrName.') ){';

        if( isset($this->aForeignKeys[ $sTable ][ $aCol['Field'] ]) ){
            $aFK = $this->aForeignKeys[ $sTable ][ $aCol['Field'] ];
            $sQuery = '\\' . $this->sDirBase
                . '\\' . $this->sDatabase
                . '\\' . $this->sDirQuery
                . '\\' . $this->formatPhpClassName($aFK['REFERENCED_TABLE_NAME']);

                $php .= '
            $this->' . $sObjAttrName . ' = '.$sQuery.'::create()
                ->filterBy'.$this->formatPhpFuncName( $aFK['REFERENCED_COLUMN_NAME'] )
                .'($this->'.$this->formatPhpAttrName( $aCol['Field'] ).')
                ->findOne();';
        }

        $php .= '
        }
        
        return $this->' . $sObjAttrName . ';
    }';

        return $php;
    }

    protected function genSetter( $sTable, array $aCol )
    {
        $sFuncName = $this->formatPhpFuncName($aCol['Field']);
        $sAttrName = $this->formatPhpAttrName($aCol['Field']);
        $sType     = $this->getPhpType($aCol);

        $sDefault   = '';
        $sTest      = '!is_' . $sType . '( $' . $sAttrName . ' )';

        if( $this->isNull($aCol) ){
            $sDefault = ' = null';
            $sTest = '!is_null( $' . $sAttrName . ' ) && ' . $sTest;
        }

        $php = '
        
    /**
     * @param ' . $sType . ' $' . $sAttrName . ' Maxlenght:' . $this->getMaxLength($aCol) . '
     * @throw \InvalidArgumentException
     * @author ' . __CLASS__ . '
     */
    public function set' . $sFuncName . '( $' . $sAttrName . $sDefault . ' )
    {
        if( '.$sTest.' ) {
            throw new \InvalidArgumentException("Invalid parameter for ".__METHOD__." : (' . $sType . ') excepted ($' . $sAttrName . ') provided");
         }
            
        $this->' . $sAttrName . ' = $' . $sAttrName . ';
        $this->_isModified = true;
        
        return $this;
    }';

        return $php;
    }

    public function genSetterForeignKey( $sTable, array $aCol )
    {
        $aFK = $this->aForeignKeys[ $sTable ][ $aCol['Field'] ];

        $sObjFuncName = $this->formatPhpForeignFuncName($aCol['Field']);
        $sObjAttrName = $this->formatPhpForeignAttrName($aCol['Field']);

        $sType = '\\' . $this->sDirBase
            . '\\' . $this->sDatabase
            . '\\' . $this->sDirEntity
            . '\\' . $this->formatPhpClassName($aFK['REFERENCED_TABLE_NAME']);

        $sAttrName = $this->formatPhpAttrName($aCol['Field']);
        $sReferencedAttr = $this->formatPhpFuncName($aFK['REFERENCED_COLUMN_NAME']);

        $php = '
        
    /**
     * @param ' . $sType . ' $' . $sObjAttrName . '
     * @author ' . __CLASS__ . '
     */
    public function set' . $sObjFuncName . '(' . $sType . ' $' . $sObjAttrName . ' )
    {
        $this->' . $sObjAttrName . ' = $' . $sObjAttrName . ';
        $this->' . $sAttrName . ' = $' . $sObjAttrName . '->get' . $sReferencedAttr . '();
        $this->_isModified = true;
        
        return $this;
    }';

        return $php;
    }

    protected function genSave( $sTable )
    {
        $aUpdateWhere = array();
        foreach ( $this->aPrimaryKeys[ $sTable ] as $aPrimaryKey ) {
            $sPrimaryName = $aPrimaryKey['COLUMN_NAME'];
            $sPrimaryPhpName = $this->formatPhpAttrName($sPrimaryName);
            $aUpdateWhere[] = $sPrimaryName . ' = '
                . $this->sqlEscQuote . $this->sqlQuote
                . '.$this->' . $sPrimaryPhpName . '.'
                . $this->sqlQuote . $this->sqlEscQuote;
        }

        $php = '
        
    /**
     * Save the object into the database
     * @return bool false if an error occurred ; true otherwise
     * @author ' . __CLASS__ . '
     */
    public function save(){

        $return = true;
        ';
        foreach ( $this->aForeignKeys[ $sTable ] as $sKeyName => $aKey ) {

            $sObjAttrName = $this->formatPhpForeignAttrName( $aKey['COLUMN_NAME'] );

            $php .= '
        $return = $return && (!$this->' . $sObjAttrName . ' || $this->' . $sObjAttrName . '->save());
        ';
        }

        $php .= '        
        if( $this->_isModified && $return ){ 
        ';

        foreach ( $this->aCols[ $sTable ] as $aCol ) {
            $sAttrName = $this->formatPhpAttrName($aCol['Field']);

            $sValue = '$this->' . $sAttrName;
            $sSetter = '\\' . $this->sDirBase . '\Orm::driver(__CLASS__)->set('. $this->sqlQuote . $aCol['Field'] . $this->sqlQuote .', ' . $sValue . ');';

            if( !$this->isNull($aCol) ){
                 $php .= '
            if( !is_null('.$sValue.') ){
                '.$sSetter.'
            }';
            }
            else {
                $php .= '
            '.$sSetter;
            }
        }

         $php .= ' 
         
            if( !$this->_isLoadedFromDb ){ 
                $return = $return && \\' . $this->sDirBase . '\Orm::driver(__CLASS__)->insert('. $this->sqlQuote . $sTable . $this->sqlQuote .'); 
                $this->' . $sPrimaryPhpName . ' = \\' . $this->sDirBase . '\Orm::driver(__CLASS__)->insert_id();
                $this->_isLoadedFromDb = true;
            }
            else {
                \\' . $this->sDirBase . '\Orm::driver(__CLASS__)->where('. $this->sqlQuote . implode(' AND ', $aUpdateWhere) . $this->sqlQuote .');
                $return = $return && \\' . $this->sDirBase . '\Orm::driver(__CLASS__)->update('. $this->sqlQuote . $sTable . $this->sqlQuote .');
            }
            
            if( $return ){ 
                $this->_isModified = false;
            }
        }
        
        return $return;
    }';

        return $php;
    }

    protected function getPhpEscape( $var )
    {
        return $this->sqlQuote . '.\\' . $this->sDirBase . '\Orm::driver(__CLASS__)->escape(' . $var . ').' . $this->sqlQuote;
    }

    /**
     * Generate the code for the 'public' query file
     * This file allow the "SELECT" requests from a table
     * The public one can be freely modified by end user as it's not overwritten
     *  if it already exists
     *
     * @param string $sTable Table name
     *
     * @return string
     *
     * @author Matthieu Dos Santos <m.dossantos@santiane.fr>
     */
    public function genFileQuery( $sTable )
    {

        $sClassName = $this->formatPhpClassName($sTable);

        $php = '<?php 
        
namespace ' . $this->sDirBase . '\\' . $this->sDatabase . '\\' . $this->sDirQuery . ';

class ' . $sClassName . ' extends ' . $this->sDirPrivate . '\\' . $sClassName . ' implements implements \Ormega\QueryInterface {

           
}';

        return $php;
    }

    /**
     * Generate the code for the 'private' query file
     * This file allow the "SELECT" requests from a table
     * The private file is overwritten in each generation
     *
     * @param string $sTable Table name
     *
     * @return string
     *
     * @author Matthieu Dos Santos <m.dossantos@santiane.fr>
     */
    public function genFileQueryPrivate( $sTable )
    {

        $sClassName = $this->formatPhpClassName($sTable);

        $php = '<?php 
        
namespace ' . $this->sDirBase . '\\' . $this->sDatabase . '\\' . $this->sDirQuery . '\\' . $this->sDirPrivate . ';

class ' . $sClassName . ' {
    
    /**
     * Get an instance of this class to chain methods 
     *      without have to use $var = new ' . $sClassName . '()
     *
     * @return \\' . $this->sDirBase . '\\' . $this->sDatabase . '\\' . $this->sDirQuery . '\\'.$sClassName.'
     *
     * @author '.__CLASS__.'
     */
    public static function create(){
        return new static();
    }
    
    /**
     * Add a limit to the nb result row for the next find() request
     
     * @param int $limit
     * @param int $start
     *
     * @return \\' . $this->sDirBase . '\\' . $this->sDatabase . '\\' . $this->sDirQuery . '\\'.$sClassName.'
     *
     * @author '.__CLASS__.'
     */
    public function limit( $limit, $start = 0 ){
        \\' . $this->sDirBase . '\Orm::driver(__CLASS__)->limit( $limit, $start );
        return $this;
    }
    ';

        $php .= $this->genFilterBy($sTable);
        $php .= $this->genGroupBy($sTable);
        $php .= $this->genOrderBy($sTable);
        $php .= $this->genFind($sTable);

        $php .= '
    
    /**
     * Get the first result from the find() request 
     *
     * @return \\' . $this->sDirBase . '\\' . $this->sDatabase . '\\' . $this->sDirEntity . '\\'.$sClassName.'
     *
     * @author '.__CLASS__.'
     */
    public function findOne(){

        $this->limit(1);
        return $this->find()->rewind();
    }
        
}';

        return $php;
    }

    protected function genFilterBy( $sTable )
    {
        $sClassName = $this->formatPhpClassName($sTable);

        $php = '';

        foreach ( $this->aCols[ $sTable ] as $aCol ) {
            $sFuncName = $this->formatPhpFuncName($aCol['Field']);

            $php .= '
    
    /**
     * Add a special where to next find() call
     * @param mixed $value The filter value
     * @param string $operator Can be \Ormega\Orm::OPERATOR_* constant (">", "<=", "=", ...)
     * @return \\' . $this->sDirBase . '\\' . $this->sDatabase . '\\' . $this->sDirQuery . '\\'.$sClassName.'
     * @author '.__CLASS__.'
     */
    public function filterBy' . $sFuncName . '( $value, $operator = \Ormega\Orm::OPERATOR_EQUALS )
    {
        if( $operator == \Ormega\Orm::OPERATOR_IN || $operator == \Ormega\Orm::OPERATOR_NOTIN ){
            if( !is_array($value) ) {
                $value = explode('.$this->sqlQuote.','.$this->sqlQuote.', $value);
            }
        }
        
        switch( $operator ){
            case \Ormega\Orm::OPERATOR_IN:
                \\' . $this->sDirBase . '\Orm::driver(__CLASS__)
                    ->where_in(' . $this->sqlQuote . $this->db->database .'.'. $sTable . '.' . $aCol['Field'] . $this->sqlQuote . ', $value);
                break;
            case \Ormega\Orm::OPERATOR_NOTIN:
                \\' . $this->sDirBase . '\Orm::driver(__CLASS__)
                    ->where_not_in(' . $this->sqlQuote . $this->db->database .'.'. $sTable . '.' . $aCol['Field'] . $this->sqlQuote . ', $value);
                break;
            case \Ormega\Orm::OPERATOR_LIKE_PC:
                \\' . $this->sDirBase . '\Orm::driver(__CLASS__)
                    ->like(' . $this->sqlQuote . $this->db->database .'.'. $sTable . '.' . $aCol['Field'] . $this->sqlQuote . ', $value, '.$this->sqlQuote.'after'.$this->sqlQuote.');
                break;
            case \Ormega\Orm::OPERATOR_PC_LIKE:
                \\' . $this->sDirBase . '\Orm::driver(__CLASS__)
                    ->like(' . $this->sqlQuote . $this->db->database .'.'. $sTable . '.' . $aCol['Field'] . $this->sqlQuote . ', $value, '.$this->sqlQuote.'before'.$this->sqlQuote.');
                break;
            case \Ormega\Orm::OPERATOR_PC_LIKE_PC:
                \\' . $this->sDirBase . '\Orm::driver(__CLASS__)
                    ->like(' . $this->sqlQuote . $this->db->database .'.'. $sTable . '.' . $aCol['Field'] . $this->sqlQuote . ', $value, '.$this->sqlQuote.'both'.$this->sqlQuote.');
                break;
            case \Ormega\Orm::OPERATOR_ISNULL:
                \\' . $this->sDirBase . '\Orm::driver(__CLASS__)
                    ->where(' . $this->sqlQuote . $this->db->database .'.'. $sTable . '.' . $aCol['Field'] . ' IS NULL' . $this->sqlQuote . ');
                break;
            case \Ormega\Orm::OPERATOR_ISNOTNULL:
                \\' . $this->sDirBase . '\Orm::driver(__CLASS__)
                    ->where(' . $this->sqlQuote . $this->db->database .'.'. $sTable . '.' . $aCol['Field'] . ' IS NOT NULL' . $this->sqlQuote . ');
                break;
            default:
                \\' . $this->sDirBase . '\Orm::driver(__CLASS__)
                    ->where( ' . $this->sqlQuote . $this->db->database .'.'. $sTable . '.' . $aCol['Field'] . ' ' . $this->sqlQuote . '.$operator, $value );
        }
        
        return $this;
    }';

        }

        return $php;
    }

    protected function genGroupBy( $sTable )
    {
        $sClassName = $this->formatPhpClassName($sTable);

        $php = '';

        foreach ( $this->aCols[ $sTable ] as $aCol ) {
            $sFuncName = $this->formatPhpFuncName($aCol['Field']);

            $php .= '
    
    /**
     * Add a special groupby to next find() call 
     * @return \\' . $this->sDirBase . '\\' . $this->sDatabase . '\\' . $this->sDirQuery . '\\'.$sClassName.'
     * @author '.__CLASS__.'
     */
    public function groupBy' . $sFuncName . '()
    {
        \\' . $this->sDirBase . '\Orm::driver(__CLASS__)
            ->group_by('.$this->sqlQuote . $this->db->database .'.'. $sTable . '.' . $aCol['Field'] . $this->sqlQuote.');
        return $this;
    }';

        }

        return $php;
    }

    protected function genOrderBy( $sTable )
    {
        $sClassName = $this->formatPhpClassName($sTable);

        $php = '';

        foreach ( $this->aCols[ $sTable ] as $aCol ) {
            $sFuncName = $this->formatPhpFuncName($aCol['Field']);

            $php .= '
        
    /**
     * Add a special order to next find() call
     * @param string $order Can be \Ormega\Orm::ORDER_* constant ("ASC", "DESC")
     * @return \\' . $this->sDirBase . '\\' . $this->sDatabase . '\\' . $this->sDirQuery . '\\'.$sClassName.'
     * @author '.__CLASS__.'
     */
    public function orderBy' . $sFuncName . '( $order = \Ormega\Orm::ORDER_ASC )
    {
        \\' . $this->sDirBase . '\Orm::driver(__CLASS__)
            ->order_by('.$this->sqlQuote . $this->db->database .'.'. $sTable . '.' . $aCol['Field'] . $this->sqlQuote.', $order);
        return $this;
    }';

        }

        return $php;
    }

    protected function genFind( $sTable )
    {
        $sClassName = $this->formatPhpClassName($sTable);

        $aColumns = array();
        foreach ( $this->aCols[ $sTable ] as $aCol ) {
            $aColumns[] = $this->db->database.'.'.$sTable.'.'.$aCol['Field'];
        }

        $php = '
            
    /**
     * Start the select request composed with any filter, groupby,... set before
     * Return an array of 
     *      \\' . $this->sDirBase . '\\' . $this->sDatabase . '\\' . $this->sDirEntity . '\\'.$sClassName.' object 
     * @return \Ormega\EntitiesCollection
     * @author '.__CLASS__.'
     */
    public function find(){

        $aReturn = new \Ormega\EntitiesCollection();

        $query = \\' . $this->sDirBase . '\Orm::driver(__CLASS__)
            ->select(' . $this->sqlQuote . implode(',', $aColumns) . $this->sqlQuote . ')
            ->get(' . $this->sqlQuote . $this->db->database .'.'. $sTable . $this->sqlQuote . ');
        
        foreach( $query->result() as $row ){
            
            $obj = new \\' . $this->sDirBase
                . '\\' . $this->sDatabase
                . '\\' . $this->sDirEntity
                . '\\'.$sClassName.'();
            ';

        $aIdentifier = array();

        foreach ( $this->aCols[ $sTable ] as $aCol ) {

            $sFuncName = $this->formatPhpFuncName($aCol['Field']);
            $sType = $this->getPhpType($aCol);

            if( $this->isNull($aCol) ){
                $php .= '
            if( !is_null($row->' . $aCol['Field'] . ') ){
                $obj->set' . $sFuncName . '((' . $sType . ') $row->' . $aCol['Field'] . ');
            }';

            } else {
                $php .= '
            $obj->set' . $sFuncName . '((' . $sType . ') $row->' . $aCol['Field'] . ');';
            }
        }



        $php .= '
            $obj->loaded(true);
            $obj->modified(false);
            
            $aReturn[ $obj->getPkId() ] = $obj;
        }

        return $aReturn;
    }';

        return $php;
    }

    protected function genFiles()
    {
        $sRealBasePath = realpath($this->sBasePath);
        if ( !$sRealBasePath ) {
            throw new \Exception('Destination dir doesnt exists : ' . $sRealBasePath);
        }

        # ./
        if ( !is_dir($sRealBasePath . '/' . $this->sDirBase) ) {
            mkdir($sRealBasePath . '/' . $this->sDirBase, 0775);
        }

        # ./database
        if ( !is_dir($sRealBasePath . '/' . $this->sDirBase . '/' . $this->sDatabase) ) {
            mkdir($sRealBasePath . '/' . $this->sDirBase . '/' . $this->sDatabase, 0775);
        }

        $sRealSourceDir = realpath($sRealBasePath . '/' . $this->sDirBase . '/' . $this->sDatabase);

        # ./Enum
        if ( !is_dir($sRealSourceDir . '/' . $this->sDirEnum) ) {
            mkdir($sRealSourceDir . '/' . $this->sDirEnum, 0775);
        }

        # ./Entity
        if ( !is_dir($sRealSourceDir . '/' . $this->sDirEntity) ) {
            mkdir($sRealSourceDir . '/' . $this->sDirEntity, 0775);
        }
        $sRealSourceEntityDir = realpath($sRealSourceDir . '/' . $this->sDirEntity);

        # ./Entity/Private
        if ( !is_dir($sRealSourceEntityDir . '/' . $this->sDirPrivate) ) {
            mkdir($sRealSourceEntityDir . '/' . $this->sDirPrivate, 0775);
        }

        # ./Query
        if ( !is_dir($sRealSourceDir . '/' . $this->sDirQuery) ) {
            mkdir($sRealSourceDir . '/' . $this->sDirQuery, 0775);
        }
        $sRealSourceQueryDir = realpath($sRealSourceDir . '/' . $this->sDirQuery);

        # ./Query/Private
        if ( !is_dir($sRealSourceQueryDir . '/' . $this->sDirPrivate) ) {
            mkdir($sRealSourceQueryDir . '/' . $this->sDirPrivate, 0775);
        }

        foreach ( $this->aFiles as $aFile ) {

            $this->createFile($sRealBasePath . '/' . $aFile['file'], $aFile['content'], $aFile['erase']);
        }
    }

    /**
     * Create one file
     *
     * @param string $fullFilePath Full path for the new file
     * @param string $content      File content
     * @param bool   $erase        Overwrite the file if it already exists
     *
     * @author Matthieu Dos Santos <m.dossantos@santiane.fr>
     */
    protected function createFile( $fullFilePath, $content, $erase = false )
    {
        $bGen = false;
        $bFileExists = file_exists($fullFilePath);

        if ( !$bFileExists || $erase )
            $bGen = file_put_contents($fullFilePath, $content);

        if ( $bGen )
            $this->output('Gen file "' . $fullFilePath . '" : OK');
        else {
            $sFileExists = $bFileExists ? 'YES' : 'NO';
            $sErase = $erase ? 'YES' : 'NO';
            $this->output(
                'Gen file "'. $fullFilePath . '" : KO ; File already exists ? '
                . $sFileExists . ' ; Overwrite ? ' . $sErase
            );
        }
    }

    /**
     * Is $aCol an unsigned field ?
     *
     * @param array $aCol Column info from "SHOW COLUMNS" request
     *
     * @return bool
     *
     * @author Matthieu Dos Santos <m.dossantos@santiane.fr>
     */
    protected function isUnsigned( array $aCol )
    {
        return strpos($aCol['Type'], 'unsigned') !== false;
    }

    /**
     * Is NULL allowed for a column ?
     *
     * @param array $aCol Field info from "SHOW COLUMNS" request
     *
     * @return bool
     *
     * @author Matthieu Dos Santos <m.dossantos@santiane.fr>
     */
    protected function isNull( array $aCol )
    {
        if( isset($aCol['Null']) && $aCol['Null'] == 'YES') return true;
        else return false;
    }

    protected function getPhpType( $aCol )
    {
        preg_match('/^([a-z]+)/', $aCol['Type'], $aMatches);
        $sType = $aMatches[0];

        switch ( $sType ) {
            case 'tinyint':
                if ( $this->getMaxLength($aCol) == 1 ) {
                    $sType = 'bool';
                }
                else {
                    $sType = 'int';
                }
                break;
            case 'smallint':
            case 'mediumint':
            case 'int':
            case 'bigint':
            case 'bit':
                $sType = 'int';
                break;
            case 'float':
            case 'double':
            case 'decimal':
                $sType = 'float';
                break;
            case 'varchar':
            case 'char':
            case 'varbinary':
            case 'tinytext':
            case 'text':
            case 'mediumtext':
            case 'longtext':
            case 'tinyblob':
            case 'blob':
            case 'mediumblob':
            case 'longblob':
            case 'timestamp':
            case 'datetime':
            case 'date':
            case 'enum':
                $sType = 'string';
                break;
        }

        return $sType;
    }

    protected function getMaxLength( array $aCol )
    {
        $nMaxlength = null;

        preg_match('/\(([0-9]+)\)/', $aCol['Type'], $aMatches);
        if ( isset($aMatches[1]) )
            $nMaxlength = (int)$aMatches[1];
        else {
            switch ( $aCol['Type'] ) {
                case 'date';
                    $nMaxlength = 10;
                    break;
                case 'timestamp':
                case 'datetime':
                    $nMaxlength = 19;
                    break;
                case 'tinytext':
                case 'tinyblob':
                    $nMaxlength = 255;
                    break;
                case 'text':
                case 'blob':
                    $nMaxlength = 65000;
                    break;
                case 'mediumtext':
                case 'mediumblob':
                    $nMaxlength = 16777215;
                    break;
                case 'longtext':
                case 'longblob':
                    $nMaxlength = 4294967295;
                    break;
            }
        }

        return $nMaxlength;
    }

    protected function formatPhpFuncName( $sName )
    {
        return str_replace(array('-','_'), '', ucwords($sName, '_'));
    }

    protected function formatPhpForeignFuncName( $sName )
    {
        return str_replace(array('-','_'), '', ucwords(substr( $sName, 0, strrpos( $sName, '_' ) ), '_'));
    }

    protected function formatPhpClassName( $sName )
    {
        return str_replace(array('-','_'), '', ucwords($sName, '_'));
    }

    protected function formatPhpAttrName( $sName )
    {
        return $sName;
    }

    /**
     * Return the name of the attribute without the characters after the last underscore (_)
     *
     * @param string $name
     *
     * @return string
     *
     * @author Matthieu Dos Santos <m.dossantos@santiane.fr>
     */
    protected function formatPhpForeignAttrName( $sName )
    {
        return substr( $sName, 0, strrpos( $sName, '_' ) );
    }
}