<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */

 use Doctrine\Common\EventManager;
 
 class MY_Controller extends CI_Controller {
	 
	 public $em;
	 public $evm;
     public $controller;
     public $resources;
	 public $user_model;
     
     public function __construct() {
         
         parent::__construct();
		 
		 //$this->output->enable_profiler(TRUE);
		 
		 $this->load->library('Doctrine');
         
		 $this->em = $this->doctrine->em;
		 $this->evm = new EventManager();
         $this->controller = $this->router->class;
         $this->resources = array();
		 
         $this->load->library('Activity_Manager');
		 
		 ini_set('memory_limit', '1024M');
         
		 $this->user_model = $this->em->getRepository('models\Entities\User\Admin');
     }
	 
	 public function _remap( $method, $params = array() ) {
	 	
		if( method_exists($this, $method ) ) {
			if( $method == 'login' ) $this->$method();
			else {
				if( $this->auth_manager->user() ) $this->auth_manager->allowAccess() ? call_user_func_array(array($this, $method), $params) : show_404();
				else $this->load->view( 'master_login' );
			}
		}
		else show_404();
	 }
     
     /**
     * Render View
     *
     * @access  public
     * @param   string
     * @param   array
     * @return  void
     */
     public function _render_view( $view_name, $data = array() ) {
         
         $data['resources'] = $this->resources;
         $data['page_view'] = $view_name;
		 $data['vendors'] = $this->em->getRepository('models\Entities\Vendor')->findAll();
		 
         $this->load->view( 'master_layout', $data );
     }
	 
	 public function login() {
         
         $email = $this->input->post('login_email');
         $password = $this->input->post('login_password');
         
		 $this->auth_manager->login( $email, $password );
		 redirect();
     }
	 
	 public function logout() {
         
         $this->session->unset_userdata('user_id');
         redirect();
     }
 }
 
 /* End of file MY_Controller.php */
 /* Location: ./system/applications/_backend/core/MY_Controller.php */