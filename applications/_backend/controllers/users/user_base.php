<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * ...
 * @author Andrej The Savic [ ABC Design ]
 */
 
 // if you do not need to use a user type, then you do not need to use the UserBase controller.
 // therefore user type constants are declared here. 
 define('USER_TYPE_UNKNOWW', -1);
 define('USER_TYPE_ADMIN',    0);
 
 class UserBase extends MY_Controller {
 	
	private $type;
	
	public $gridParams = array(
		'width' 			=> 'auto',
		'height'			=> 400,
		'rp'				=> 15,
		'rpOptions'			=>'[10,15,20,25,40]',
		'blockOpacity'		=> 0.5,
		'showTableToggleBtn'=> true,
        'pagestat'          => 'Displaying: {from} to {to} of {total} items.'
	);
	
	public function __construct() {
		
		$type = USER_TYPE_UNKNOWW; // unknown type. Must be overridden in subsequent, inheriting constructors
		
		parent::__construct();
		
		$this->load->helper('flexigrid');
		$this->load->library('flexigrid');
		$this->load->library('form_validation');
	}
	
	public function create() {
		
		switch ($this->type) {
			
			case USER_TYPE_ADMIN :
				$data['groups'] = $this->getAdminGroupRepo()->findAll();
				$data['back'] 	= site_url('users/admin_user/listing');
				$this->_render_view('user/new_admin_user', $data);
				break;
		}
	}
	
	public function details($id) {		
		switch ($this->type) {			
			case USER_TYPE_ADMIN : 
				$entity = 'models\Entities\User\Admin';				
				$data['groups'] = $this->getAdminGroupRepo()->findAll();
				if ($data['user'] = $this->em->getRepository($entity)->find($id)) {
					$this->_render_view('user/edit_admin_user', $data);					
					return;
				}
				break;
		}
		show_404(); // (note the early return above)
	}
	
	public function delete() {
		
		switch($this->type) {
			
			case USER_TYPE_ADMIN :
				$entity = 'models\Entities\User\Admin';
				$query    = $this->em->createQuery('DELETE FROM '.$entity.' u WHERE u.id IN (' .$this->input->post('items') . ')' );
				$numDeleted = $query->execute(); 
				if ($numDeleted) $this->output->set_output( TRUE ); 
				break;
		}
	}
	
	public function grid( $lang = NULL ) {
		
		switch ($this->type) {
			case USER_TYPE_ADMIN :
				$valid_fields = array('group, first_name', 'last_name', 'email');
				$this->flexigrid->validate_post('email', 'asc', $valid_fields, $this->session->userdata('current_listing'));
				$criteria = $this->flexigrid->get_criteria( $lang );
				$records  = $this->user_model->getUsersByType($criteria, $this->type);				
				break;
		}
		
		if( ENVIRONMENT == 'development' ) $this->output->enable_profiler(FALSE);
		
		$this->session->unset_userdata('edit_visited');
		$this->output ->set_header    ($this->config->item('json_header'));
		$this->output ->set_output    ($this->flexigrid->json_build($records['record_count'], $records['record_items']));			
	}
	
	//////////////////////////////////////////////////////////////////////
	
	public function getUserType() {
		return $this->type;
	}
	
	protected function setUserType($value) {
		$this->type = $value;
	} 
	protected function getAdminGroupRepo() {
		return $this->em->getRepository('models\Entities\User\Group\AdminGroup');
	}
		
 }