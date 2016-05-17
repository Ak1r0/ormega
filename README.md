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
            'databases' => array(
                'mpq' => array(
                    'db' => $this->db_1, // CI Database connection 1
                    'filter' => '.*', // Generate files for all tables
                ),
                'backend' => array(
                    'db' => $this->db_2,  // CI Database connection 2 (if multiple databases used)
                    'filter' => '^(table1|table2|table3|table4)$',
                ),
                'gestrh' => array(
                    'db' => $this->db_3, // CI Database connection 3 (if multiple databases used)
                    'filter' => '^table_x_.+',
                )
            ),
            'path'      => 'application/models/',
            'namespace' => 'Ormega'
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

### Manipulate, insert or update, Select, find

```php
// I.E. Into a CI controller

public function test(){

    try {
        $aInit = array(
            'mpq'     => $this->db_mpq,
            'backend' => $this->db_backend,
        );

        require APPPATH . '/models/Ormega/Orm.php';

        Ormega\Orm::init($aInit);

        # Manipulate, insert or update

        $comment = new Ormega\Mpq\Entity\Comment();
        $comment
            ->setQualitycaseId(1919)
            ->setFormblockId(4)
            ->setStepId(3)
            ->setUserId(24)
            ->setGroupby('test')
            ->setMessage('test')
            ->save();

        # Select, find

        $aCareneeds = \Ormega\Query\Careneeds::create()
            ->filterByAge(20)
            ->orderByDateinsert('DESC')
            ->find();

    }
    catch( \InvalidArgumentException $e ){
        echo $e->getMessage();
    }
}
```