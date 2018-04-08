<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */
 
 class Account extends MY_Controller {
     
     public function __construct() {
         
         parent::__construct();
     }
	 
	 public function index() {
	 	
		$data['user'] = $this->auth_manager->user();
        $this->_render_view( 'user/edit_account', $data );
	 }
	 
	 public function edit() {
	 	
		// TODO server validation
		
		$data['user'] = $this->auth_manager->user();
			
		$data['user']->setEmail( $this->input->post('email') );
		// TODO only if input is not an empty string
        if( $this->input->post('password') ) $data['user']->setPassword( $this->input->post('password') ); 
		$data['user']->setFirstName( $this->input->post('first_name') );
		$data['user']->setLastName( $this->input->post('last_name') );
		$data['user']->setPhone( $this->input->post('phone') );
		
		$this->em->persist($data['user']);
		$this->em->flush();
		
		$data['message'] = '<p class="message_success">Sve izmene su uspešno izvršene!</p>';
		
    	$this->_render_view( 'user/edit_account', $data );
	 }
 }
 
 /* End of file account.php */
 /* Location: ./system/applications/_backend/controllers/users/account.php */