# Ormega
Basic ORM for MySQL with classes generator directly from database 

*! Non CodeIgniter projects !*

This ORM use Codeigniter Querybuilder so there's a standalone CI Querybuilder integreted with.

For codeigniter projects see [here](https://github.com/4k1r0/ormega/tree/CI).

## Install with composer

    composer require 4k1r0/ormega

## Model Generation

### How to

```php
<?php

// Composer autoloader
require 'vendor/autoload.php';

use Evolution\CodeIgniterDB as CI;

// Codeigniter style database configuration
$db_data_1 = array(
    'dsn'   => '',
    'hostname' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'database1',
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => TRUE,
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
);

$db_data_2 = array(
    'dsn'   => '',
    'hostname' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'database2',
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => TRUE,
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
);

// Creates CI database connectors 
$db1 =& CI\DB($db_data_1);
$db2 =& CI\DB($db_data_2);

// Generator config
$config = array(
    'databases' => array(
        'database1' => array(
            'db' => $db1,
            'filter' => '^(user|profil|application)$',
        ),
        'database2' => array(
            'db' => $db2,
            'filter' => 'business_.*',
        ),
    ),
    'path'      => 'models/',
    'namespace' => 'Ormega'
);

try {
    // Go !
    $Ormegagen = new \Ormega\Generator($config);
    $Ormegagen->run();
}
catch (\InvalidArgumentException $e){
    // ...
}
```

### Generated classes

This will create a dir `{namespace}` in `./{path}`

If I run the generator from `/var/www/myproject/` whith 

```php
$config['path'] = 'models/';
$config['namespace'] = 'Ormega';
```

Will result in :
```
/var/www/myproject/models/Ormega/Database1/Entity
/var/www/myproject/models/Ormega/Database1/Enum
/var/www/myproject/models/Ormega/Database1/Query

/var/www/myproject/models/Ormega/Database2/Entity
/var/www/myproject/models/Ormega/Database2/Enum
/var/www/myproject/models/Ormega/Database2/Query
```

'Enum' is explained below.
 
'Entity' and 'Query' dir contains one empty php class for each table parsed with the generator and a 'base' directory.

These classes inherits from the one inside the base directory. They're empty to allow custom methods override.

They will not being erased if you restart the generation.

The "true" classes are in 'base' directories. 

**They must no be manually modified because a new file is wrote at each generation.**

### Custom methods

In these "empty" classes you're free to redefine every method.
 
You can add whatever you want in setters, getters, or even save method.

Or create customs queries (override `find()`) or filters.

### Enum

Enums are specials models created from tables named 'enum[...]' and are designed to contains CONSTANTS.

These tables must have only 3 columns : 'id', 'label', 'constant'

If you want to add a constant, you will have to restart the generation.

Example : 

Table `enumgender`

| id | label           | constant |
|----|-----------------|----------|
| 1  | man gender id   | MAN      |
| 2  | woman gender id | WOMAN    |

Can be used like this

```php
 if( $oUser->getGenderId() == \Ormega\Database\Enum\Enumgender::MAN ){
    // Do stuff if man
 }
```

This allow a more readable code than hardcode the man ID.

The 'label' column is used as a description.

The code generated will look like this

```php
<?php 
        
namespace Ormega\Database\Enum;

class Enumgroup implements \Ormega\EnumInterface {
    
    /** 
     * @var int man gender id
     */
    const MAN = 1;
    
    /** 
     * @var int woman gender id
     */
    const WOMAN = 2;
    
    // ...
}
```

There's also a set of methods within :

```php
    /**
     * Get the "Label" associated to an ID
     * @param int $nId
     * @return string
     */
    public static function getLabel( $nId ){ 
        // ... 
    }
    
    /**
     * Get the "Constant" associated to an ID
     * @param int $nId
     * @return string
     * @author Ormegagenerator_lib
     */
    public static function getConstant( $nId ){ 
        // ... 
    }
    
    **
     * Get all the constants in a array form
     * @return array
     * @author Ormegagenerator_lib
     */
    public static function getArray(){ 
        return array(
            "QUALITE" => array(
                "id" => "1",
                "label" => "man gender id",
                "constant" => "MAN",
            ),
            "MANAGER" => array(
                "id" => "2",
                "label" => "woman gender id",
                "constant" => "WOMAN",
            ),
        );
    }
    
    **
     * Get an ID from a string constant
     * @param string $sConstant
     * @return int
     * @author Ormegagenerator_lib
     */
    public static function getId( $sConstant ){ 
        // ... 
    }
```

## Usage 

### Init

```php
<?php

// Composer autoloader
require 'vendor/autoload.php';

use Evolution\CodeIgniterDB as CI;

// Codeigniter style database configuration
$db_data_1 = array(
    'dsn'   => '',
    'hostname' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'database1',
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => TRUE,
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
);

$db_data_2 = array(
    'dsn'   => '',
    'hostname' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'database2',
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => TRUE,
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
);

// Creates CI database connectors 
$db1 =& CI\DB($db_data_1);
$db2 =& CI\DB($db_data_2);

try {
    
    // Orm config. Index must be the same as declared in the generator
    $aInit = array(
        'database1' => $db1,
        'database2' => $db2,
    );

    require 'models/Ormega/Orm.php';

    Ormega\Orm::init($aInit);
    
    // ...
}
catch( \InvalidArgumentException $e ){
    // ...
}
```

This will add an autoloader for generated models.

### Manipulate, insert or update

```php
$oCareneeds = new \Ormega\Entity\User();
$oCareneeds->setEmail('test@gmail.com')
           ->setAge(20)
           ->setEmailPublic(true)
           ->save();
```

There's a setter and a getter for every table's columns.

Setters have an automatic data check based on column's type and length.

_Ex : a mysql tinyint(1) will be converted to php boolean_
 
This check throw a InvalidArgumentException.

### Select multiple

```php
$oUserCollection = \Ormega\Query\User::create()
    ->filterByAge(20, \Ormega\Orm::OPERATOR_LOWER_THAN)
    ->filterById($aIds, \Ormega\Orm::OPERATOR_IN)
    ->filterByIsActive(true) // EQUALS as default second argument
    ->orderByDateinsert('DESC')
    ->find();
```

`::create()` is a shortcut for `$oUserQuery = new \Ormega\Query\User();`

### Select one

```php    
$oUserEntity = \Ormega\Query\User::create()
    ->filterById( $nUserId )
    ->findOne();
```    
 
### Results
   
```php   
// Test results
if( $oUserCollection->isEmpty() ){
    // No results   
}

// browse each result
foreach( $oUserCollection as $oUserEntity ){
    /**
     * @var \Ormega\Entity\User $oUserEntity
     */
}

// Get all primary key (ex : to use it within a `WHERE field IN()` sql statement)
$aIds = $oUserCollection->getArrayKeys()

```

## Usage : Foreign keys

The generator detects foreign keys and add an attribute for each in generated models.

So you can directly set the referenced Ormega entity model instead of foreign ID.

| User  | Profil      |
|-------|-------------|
| id    | id          |
| login | #fk_user_id |

_FK1 user_id reference User.id_

```php
$oUserEntity = new Ormega\Database\Entity\User();
$oProfilEntity = new Ormega\Database\Entity\Profil();

$oProfilEntity->setUser( $oUserEntity );
$oProfilEntity->setFkUserId( $oUserEntity->getId() );  // this 2 lines do the same stuff

// Same way for getters :
$oProfilEntity = new Ormega\Database\Entity\Profil();
$oUserEntity = $oProfilEntity->getUser();
```

_Tips : If you modify both User and Profil entity, only one save is necessary on Profil entity._

Model generated :

```php
<?php 
        
namespace Ormega\Database\Entity\Base;

class Profil implements \Ormega\EntityInterface {
     
    /**
     * @var int $fk_user_id Null:NO Maxlenght:10
     */
    protected $fk_user_id;
    
    /**
     * @var Ormega\Database\Entity\User $user Null:NO
     */
    protected $user;
    
    // ...
    
    /**
     * @param int $fk_user_id Maxlenght:11
     * @throw \InvalidArgumentException
     */
    public function setFkUserId( $fk_user_id )
    {
        if( !is_int( $fk_user_id ) ) {
            throw new \InvalidArgumentException("Invalid parameter for \"".__METHOD__."\" : (int) expected ; \"$fk_user_id\" (".gettype($fk_user_id).") provided");
         }
            
        $this->fk_user_id = $fk_user_id;
        $this->modified(true);
        
        return $this;
    }
    
    /**
     * @param \Ormega\Database\Entity\User $user
     */
    public function setComment(\Ormega\Database\Entity\User $user)
    {
        $this->user = $user;
        $this->fk_user_id = $user->getId();
        $this->modified(true);
        
        return $this;
    }
}
```




