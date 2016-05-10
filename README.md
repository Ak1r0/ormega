# ormega
Basic ORM for MySQL with classes generator

# Non CodeIgniter projects

## Install with composer

    composer require 4k1r0/ormega:1.0.0

## How to generate classes

```php
require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

require_once 'myApp/adapter/Db.php'; // Include your mysql adapter, it must extends the ormega\DbInterface

$db = new Db('database', '127.0.0.1', 'root', '');

try {
    $gen = new \Ormega\Generator($db);
    $gen->sTableFilter = "^user.*"; // regex filter on table's name to generate classes only for specifics tables
    $gen->sBasePath = "myApp/generatedClasses"; // define where to save generated files - this folder must already exists
    $gen->run();
}
catch (Exception $e){
    echo $e->getMessage();
}
```


## How to use generated classes

### Init

```php
require_once 'myApp/vendor/autoload.php'; // Autoload files using Composer's autoload
require_once 'myApp/generatedClasses/Orm.php';

[...]
\Ormega\Orm::init($db);
```

> `$db` must be an instance of \Ormega\DbInterface (autoloaded with composer)
> so you have to implement this interface in any mysql adapter used

This will add an autoloader for generated classes.

### Manipulate, insert or update

```php
$oCareneeds = new \Ormega\Entity\User();
$oCareneeds->setEmail('test@gmail.com')
           ->setAge(20)
           ->setEmailPublic(true)
           ->save();
```

### Select, find

```php
$aCareneeds = \Ormega\Query\Careneeds::create()
    ->filterByAge(20)
    ->orderByDateinsert('DESC')
    ->find();
```