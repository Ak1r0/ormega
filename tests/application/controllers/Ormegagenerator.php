<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controlleur de la page de lising des affaires par steps
 * @package qualite.in.santiane.fr
 * @category controller
 * @version 20160310
 */
class Ormegagenerator extends CI_Controller {
	
    /**
     * Méthode du contrôleur qui va récupérer 
     * de quoi remplir le filtre de recherche des affaires 
     * dans l'étape et charger la vue
     * @author Matthieu Dos Santos <m.dossantos@santiane.fr>
     * @return void
     */
    public function index()
    {
		$this->db_local  = $this->load->database('default', true);
		
        $config = array(
            'databases' => array(
                'mydb' => array(
                    'db' => $this->db_local,
                    'filter' => '.*',
                )
            ),
            'path'      => 'application/models/',
            'namespace' => 'Ormega'
        );

        try {

            require_once FCPATH . '../libraries/Ormegagenerator_lib.php';
            $Ormegagen = new Ormegagenerator_lib($config);
            $Ormegagen->run();

            /* Add package installed with composer
            
            $this->load->add_package_path(APPPATH . 'third_party/ormega');
            $this->load->library('Ormegagenerator_lib', $config, 'Ormegagen');
            $this->load->remove_package_path(APPPATH . 'third_party/ormega');

            $this->Ormegagen->run();
            */

        }
        catch(\InvalidArgumentException $e){
            echo $e->getMessage();
        }
    }
}