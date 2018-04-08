<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * ...
 * @author Marko Stepanovic [ Codeion ]
 */

 use models\Entities\Cart\Section;
 
 class Sections extends MY_Controller {
     
     public $gridParams = array(
        'id'                    => 'cartSectionsGrid',
        'width'                 => 'auto',
        'height'                => 400,
        'rp'                    => 15,
        'rpOptions'             => '[10,15,20,25,40]',
        'pagestat'              => 'Prikaz: {from} do {to} Ukupno: {total} sekcija.',
        'blockOpacity'          => 0.5,
        'pagetext'              => 'Stranica', 
        'outof'                 => 'od', 
        'showTableToggleBtn'    => true
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
        
		$this->gridParams['title'] = 'Pregled svih sekcija';
		
		$colModel['id']  = array( 'ID', 80, FALSE, 'center', 0 ); 
		$colModel['title']  = array( 'Naslov', 200, TRUE, 'center', 1 );
		$colModel['actions']   = array( 'Detalji', 80, FALSE, 'center', 0 ); 
        
        if( $this->input->post('page') ) $this->gridParams['newp'] = $this->input->post('page');
		$data['grid'] = build_grid_js('grid', site_url("cart/sections/grid"), $colModel, 'id', 'ASC', $this->gridParams);

		$data['grid_title'] = "Korpa - Sekcije";
		$this->_render_view( "master/grid_view", $data );
	 }

     public function grid() {
     	 
		$valid_fields = array('title');
         
 		$this->flexigrid->validate_post($this->gridParams['id'], 'id', 'ASC', $valid_fields);
 		$criteria = $this->flexigrid->get_criteria();
 		$records = $this->em->getRepository('models\Entities\Cart\Section')->getSections( $criteria );
         
 		$this->session->unset_userdata('edit_visited');
 		$this->output->set_header($this->config->item('json_header'));
 		$this->output->set_output($this->flexigrid->json_build($records['record_count'],$records['record_items']));
     }
	 
	 public function details( $id ) {

        if( $data['section'] = $this->em->getRepository('models\Entities\Cart\Section')->find($id) ) {
        	
			$this->resources['js'][] = 'checkbox';
			
        	$this->_render_view( 'cart/edit_section', $data );
        }
		else show_404();
     }
	 
	 public function edit( $id ) {
	 	
		if( $data['section'] = $this->em->getRepository('models\Entities\Cart\Section')->find($id) ) {
			
			$this->resources['js'][] = 'checkbox';
			
			$data['section']->setLabel( $this->input->post('label') );	
			$data['section']->setTitle( $this->input->post('title') );
	        $data['section']->setDescription( $this->input->post('description') );
			$data['section']->setStatus( $this->input->post('status') );
	
			$this->em->persist($data['section']);
			$this->em->flush();
			
			$data['message'] = '<p class="message_success">Sve izmene su uspešno izvršene!</p>';
			
			$this->_render_view( 'cart/edit_section', $data );
			
        } else show_404();
	 }
}
 
 /* End of file sections.php */
 /* Location: ./system/applications/_backend/controllers/cart/sections.php */