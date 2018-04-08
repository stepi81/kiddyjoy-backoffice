<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */

 use models\Entities\Order;
 use models\Entities\User\Customer; 
 
 class Home extends MY_Controller {
     
    public function __construct() {
    	 	 
        parent::__construct();
		
		$this->resources['css'] = array();
		$this->resources['js'] = array();
    }
     
    public function index() {    	        
		$this->_render_view( "home_view" );
    }
	
	public function change_application() {
         $this->session->set_userdata('application_id', $this->input->post('application_id'));
         redirect();
    }
 }
 
 /* End of file home.php */
 /* Location: ./system/applications/_backend/controllers/home.php */