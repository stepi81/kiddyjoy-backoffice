<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */
 
 require_once 'abstract_user.php';
 
 use models\Entities\User\Admin;
 
 class Admin_User extends Abstract_User {
     
     public function __construct() {
         
         parent::__construct();

         $this->resources['css'] = array();
         $this->resources['js'] = array();
		 
		 $this->type = USER_TYPE_ADMIN;
     }
     
     public function listing() {
         
         $this->resources['css'][] = 'flexigrid';
         $this->resources['js'][] = 'flexigrid';
         
         $this->gridParams['title'] = 'Pregled svih administratora';
         
		 $colModel['group']			= array( 'Grupa', 204, TRUE, 'center', 1 );
         $colModel['first_name']	= array( 'Ime', 204, TRUE, 'center', 1 );
         $colModel['last_name']		= array( 'Prezime', 204, TRUE, 'center', 1 );
         $colModel['email']			= array( 'E-mail', 200, TRUE, 'center', 1 );
         $colModel['actions']		= array( 'Detalji', 80, FALSE, 'center', 0 ); 
         
         $buttons[] = array('Novi administrator', 'add', 'grid_commands', site_url("users/admin_user/create"));
         $buttons[] = array('separator');
         $buttons[] = array('Obriši administratora', 'delete', 'grid_commands', site_url("users/admin_user/delete"));
         $buttons[] = array('separator');
         $buttons[] = array('Izaberi sve', 'select_all', 'grid_commands', '/select');
		 $buttons[] = array('separator');
         $buttons[] = array('Poništi izbor', 'deselect_all', 'grid_commands', '/desel');
         $buttons[] = array('separator');

         if( $this->input->post('page') ) $this->gridParams['newp'] = $this->input->post('page');

         $data['grid'] = build_grid_js('grid', site_url("users/admin_user/grid"), $colModel, 'id', 'ASC', $this->gridParams, $buttons);
         $data['grid_title'] = 'Administratori';
         
         $this->_render_view( "master/grid_view", $data );
     }
     
     public function save() {
        
        // TODO server validation
        
        $user = new Admin();
		$user->setEmail( $this->input->post('email') );
		$user->setPassword( $this->input->post('password') );
		$user->setFirstName( $this->input->post('first_name') );
		$user->setLastName( $this->input->post('last_name') );
		$user->setPhone( $this->input->post('phone') );
		$user->setGroup( $this->em->getReference('models\Entities\User\Admin_Group', $this->input->post('group_id')) );
		
		$this->em->persist($user);
		$this->em->flush();
		
		$data['groups'] = $this->em->getRepository('models\Entities\User\Admin_Group')->findAll();
		$data['message'] = '<p class="message_success">Novi administrator je uspešno kreiran!</p>';
		
    	$this->_render_view( 'user/new_admin_user', $data );
    }
     
     public function edit( $id ){
     	
		if( $data['user'] = $this->em->getRepository('models\Entities\User\Admin')->find($id) ) {
			
			// TODO server validation
			
			$data['user']->setEmail( $this->input->post('email') );
            if( $this->input->post('password') ) $data['user']->setPassword( $this->input->post('password') );  
			$data['user']->setFirstName( $this->input->post('first_name') );
			$data['user']->setLastName( $this->input->post('last_name') );
			$data['user']->setPhone( $this->input->post('phone') );
			$data['user']->setGroup( $this->em->getReference('models\Entities\User\Admin_Group', $this->input->post('group_id')) );
			
			$this->em->persist($data['user']);
			$this->em->flush();
			
        	$data['groups'] = $this->em->getRepository('models\Entities\User\Admin_Group')->findAll();
			$data['message'] = '<p class="message_success">Sve izmene su uspešno izvršene!</p>';
			
        	$this->_render_view( 'user/edit_admin_user', $data );
        }
		else show_404();
     }
     
     private function user_data( $user_data ){

        $user_data->setFirstName( $this->input->post('first_name') );
        $user_data->setLastName( $this->input->post('last_name') );    
     }
 }
 
 /* End of file admin_user.php */
 /* Location: ./system/applications/_backend/controllers/users/admin_user.php */