<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */
 
 class Admin_Group extends MY_Controller {
     
     public $role_id;
     
     public $gridParams = array(
        'id'                    => 'productsGrid',
        'width'                 => 'auto',
        'height'                => 400,
        'rp'                    => 15,
        'rpOptions'             => '[10,15,20,25,40]',
        'pagestat'              => 'Prikaz: {from} do {to} / Ukupno: {total} grupe administratora.',
        'blockOpacity'          => 0.5,
        'showTableToggleBtn'    => true,
        'pagetext'              => 'Stranica',
        'outof'                 => 'od', 
     );
     
     public function __construct() {
         
         parent::__construct();
		 
		 $this->load->helper('flexigrid');
		 $this->load->library('Flexigrid');
		 
		 $this->resources['css'] = array();
         $this->resources['js'] = array();
     }
	 
	 public function listing() {
			
		$this->resources['css'][] = 'flexigrid';
		$this->resources['js'][] = 'flexigrid';

		$this->gridParams['title'] = 'Pregled svih grupa administratora';

		$colModel['name']		= array( 'Tip grupe', 350, TRUE, 'center', 1 );
		$colModel['details']	= array( 'Detalji', 60, FALSE, 'center', 0 );

		$buttons[] = array('Nova grupa', 'add', 'grid_commands', site_url("users/admin_group/create"));
		$buttons[] = array('separator');
		$buttons[] = array('Obriši grupu', 'delete', 'grid_commands', site_url("users/admin_group/delete"));
		$buttons[] = array('separator');
		$buttons[] = array('Izaberi sve', 'select_all', 'grid_commands', '/select');
		$buttons[] = array('separator');
		$buttons[] = array('Poništi izbor', 'deselect_all', 'grid_commands', '/desel');
		$buttons[] = array('separator');

        if( $this->input->post('page') ) $this->gridParams['newp'] = $this->input->post('page');

		$data['grid'] = build_grid_js('grid', site_url("users/admin_group/grid"), $colModel, 'id', 'ASC', $this->gridParams, $buttons);

		$data['grid_title'] = "Grupe";
		$this->_render_view( "master/grid_view", $data );
	 }
     
     public function create() {
        
		$this->resources['js'][] = 'checkbox';
		
		$data['sections'] = $this->user_model->getAllSections();
		
        $this->_render_view( 'user/new_admin_group', $data );
     }
	 
	 public function details( $id ) {

        if( $data['group'] = $this->em->getRepository('models\Entities\User\Admin_Group')->find($id) ) {
        	
			$this->resources['js'][] = 'checkbox';
			
        	$data['sections'] = $this->user_model->getAllSections();
        	$this->_render_view( 'user/edit_admin_group', $data );
        }
		else show_404();
     }
     
     public function save() {
     	
		$this->resources['js'][] = 'checkbox';

        // TODO server validation
        
        $group = new models\Entities\User\Admin_Group;
		$group->setName( $this->input->post('group_name') );
		
		if( $id_list = $this->input->post('sections') ) {
			foreach( $id_list as $section_id ) {
				$group->addSection( $this->em->getReference('models\Entities\Section', $section_id) );
			}
		}
		
		$this->em->persist($group);
		$this->em->flush();
		
    	$data['sections'] = $this->user_model->getAllSections();
		$data['message'] = '<p class="message_success">Nova administratorska grupa je uspešno kreirana!</p>';
		
    	$this->_render_view( 'user/new_admin_group', $data );
     }
	 
	 public function edit( $id ) {

        if( $data['group'] = $this->em->getRepository('models\Entities\User\Admin_Group')->find($id) ) {
        	
			$this->resources['js'][] = 'checkbox';
			
			// TODO server validation
			
			$data['group']->setName( $this->input->post('group_name') );
			$data['group']->deleteSections();
			
			if( $id_list = $this->input->post('sections') ) {
				foreach( $id_list as $section_id ) {
						$data['group']->addSection( $this->em->getReference('models\Entities\Section', $section_id) );
				}
			}
			
			$this->em->persist($data['group']);
			$this->em->flush();
			
        	$data['sections'] = $this->user_model->getAllSections();
			$data['message'] = '<p class="message_success">Sve izmene su uspešno izvršene!</p>';
			
        	$this->_render_view( 'user/edit_admin_group', $data );
        }
		else show_404();
     }

     public function delete() {
     	
		$query = $this->em->createQuery('DELETE FROM models\Entities\User\Admin_Group g WHERE g.id IN ('.$this->input->post('items').')');
		$numDeleted = $query->execute();
		
		if( $numDeleted ) $this->output->set_output( TRUE );
	 }
     
     public function grid() {
     	 
		$valid_fields = array('name');
         
 		$this->flexigrid->validate_post($this->gridParams['id'], 'name', 'ASC', $valid_fields);
		
 		$criteria = $this->flexigrid->get_criteria();
 		$records = $this->user_model->getAdminGroups($criteria);
         
 		$this->session->unset_userdata('edit_visited');
 		$this->output->set_header($this->config->item('json_header'));
 		$this->output->set_output($this->flexigrid->json_build($records['record_count'],$records['record_items']));
     }
 }
 
 /* End of file admin_group.php */
 /* Location: ./system/applications/_backend/controllers/users/admin_group.php */