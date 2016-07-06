<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'/models/Ormega/Orm.php';

/**
 * Class Welcome
 *
 * @package  test ormega
 * @category Controller
 * @version  20160706
 *
 */
class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->db_test1  = $this->load->database('localtest1', true);

        $aInit = array(
            'test1' => $this->db_test1,
            'test2'  => $this->db_test2
        );

        Ormega\Orm::init($aInit); // Le init doit se faire avant le load de la librairie Session !
	}
}
