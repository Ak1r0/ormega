# ormega
Basic ORM classes generator for CodeIgniter

## Install with composer

    composer require 4k1r0/ormega:dev-CI

## How to generate classes

### Config

Tell CI to autoload vendors' libraries :

In the config.php file set
```php
$config['composer_autoload'] = FCPATH.'vendor/autoload.php';
```

### Create a controller "Ormegagenerator.php"

```php
class Ormegagenerator extends MY_Controller {

    /**
     * Step constructor
     * @author Matthieu Dos Santos <m.dossantos@santiane.fr>
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Méthode du contrôleur qui va récupérer
     * de quoi remplir le filtre de recherche des affaires
     * dans l'étape et charger la vue
     * @author Matthieu Dos Santos <m.dossantos@santiane.fr>
     * @return void
     */
    public function index()
    {
        $config = array(
            'db' => $this->db_mpq,
            'table_filter' => '.*',
            'dir_base' => 'application/models/Ormega'
        );

        try {
            $this->load->add_package_path(APPPATH . 'third_party/ormega');
            $this->load->library('Ormegagenerator_lib', $config, 'Ormegagen');
            $this->load->remove_package_path(APPPATH . 'third_party/ormega');

            $this->Ormegagen->run();
        }
        catch(InvalidArgumentException $e){
            echo $e->getMessage();
        }
    }
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