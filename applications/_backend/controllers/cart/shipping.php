<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * ...
 * @author Marko Stepanovic [ Codeion ]
 */

 use models\Entities\Cart\ShippingOption;
 
 class Shipping extends MY_Controller {
     
     public $gridParams = array(
        'id'                    => 'cartShippingGrid',
        'width'                 => 'auto',
        'height'                => 400,
        'rp'                    => 15,
        'rpOptions'             => '[10,15,20,25,40]',
        'pagestat'              => 'Prikaz: {from} do {to} Ukupno: {total} isporuka.',
        'blockOpacity'          => 0.5,
        'pagetext'              => 'Stranica', 
        'outof'                 => 'od', 
        'showTableToggleBtn'    => true
     );
     
     public function __construct() {
         
         parent::__construct();
		 
		 $this->load->helper('flexigrid');
		 $this->load->helper('upload');
		 
		 $this->load->library('Flexigrid');
		 
		 $this->resources['css'] = array();
         $this->resources['js'] = array();
     }
	 
	 public function listing() {
			
		$this->resources['css'][] = 'flexigrid';
		$this->resources['js'][] = 'flexigrid';
        
		$this->gridParams['title'] = 'Pregled svih isporuka';
		
		$colModel['id']  = array( 'ID', 80, FALSE, 'center', 0 ); 
		$colModel['title']  = array( 'Naslov', 200, TRUE, 'center', 1 );
		$colModel['status'] = array( 'Status', 50, TRUE, 'center', 0 );
		$colModel['actions']   = array( 'Detalji', 80, FALSE, 'center', 0 ); 

        $buttons[] = array('Nova isporuka', 'add', 'grid_commands', site_url("cart/shipping/create"));
        $buttons[] = array('separator');
        $buttons[] = array('Obriši isporuke', 'delete', 'grid_commands', site_url("cart/shipping/delete"));
        $buttons[] = array('separator');
		$buttons[] = array('Izaberi sve', 'select_all', 'grid_commands', '/select');
		$buttons[] = array('separator');
		$buttons[] = array('Poništi izbor', 'deselect_all', 'grid_commands', '/desel');
		$buttons[] = array('separator');
        
        if( $this->input->post('page') ) $this->gridParams['newp'] = $this->input->post('page');
		$data['grid'] = build_grid_js('grid', site_url("cart/shipping/grid"), $colModel, 'id', 'ASC', $this->gridParams, $buttons);

		$data['grid_title'] = "Korpa - Isporuke";
		$this->_render_view( "master/grid_view", $data );
	 }

     public function grid() {
     	 
		$valid_fields = array('title');
         
 		$this->flexigrid->validate_post($this->gridParams['id'], 'id', 'ASC', $valid_fields);
 		$criteria = $this->flexigrid->get_criteria();
 		$records = $this->em->getRepository('models\Entities\Cart\ShippingOption')->getShippingOptions( $criteria );
         
 		$this->session->unset_userdata('edit_visited');
 		$this->output->set_header($this->config->item('json_header'));
 		$this->output->set_output($this->flexigrid->json_build($records['record_count'],$records['record_items']));
     }
	 
	 public function create() {
        	
		$this->resources['js'][] = 'checkbox';
		
    	$this->_render_view( 'cart/create_shipping' );
     }
	 
	 public function save() {
	 	
		$this->resources['js'][] = 'checkbox';
        
        $data['shipping'] = new ShippingOption(); 
		
		if( $icon = $this->create_icon() ) {
			$data['shipping']->setIcon( $icon );
		}

		$data['shipping']->setTitle( $this->input->post('title') );
		$data['shipping']->setLimit( $this->input->post('limit') );
		$data['shipping']->setPrice( $this->input->post('price') );
        $data['shipping']->setDescription( $this->input->post('description') );
		$data['shipping']->setLocations( $this->input->post('location') );
		$data['shipping']->setStatus( $this->input->post('status') );

		$this->em->persist($data['shipping']);
		$this->em->flush();
		
		$data['message'] = '<p class="message_success">Uspešno ste dodali nov način isporuke</p>';
		
		$this->_render_view( 'cart/create_shipping', $data );
	 }

	 public function details( $id ) {

        if( $data['shipping'] = $this->em->getRepository('models\Entities\Cart\ShippingOption')->find($id) ) {
        	
			$this->resources['js'][] = 'checkbox';
			
        	$this->_render_view( 'cart/edit_shipping', $data );
        }
		else show_404();
     }
	 
	 public function edit( $id ) {
	 	
		if( $data['shipping'] = $this->em->getRepository('models\Entities\Cart\ShippingOption')->find($id) ) {
			
			$this->resources['js'][] = 'checkbox';
			
			if( $icon = $this->create_icon($data['shipping']->getIcon()) ) {
				$data['shipping']->setIcon( $icon );	
			}
	
			$data['shipping']->setTitle( $this->input->post('title') );
			$data['shipping']->setLimit( $this->input->post('limit') );
			$data['shipping']->setPrice( $this->input->post('price') );
	        $data['shipping']->setDescription( $this->input->post('description') );
			$data['shipping']->setLocations( $this->input->post('location') );
			$data['shipping']->setStatus( $this->input->post('status') );
	
			$this->em->persist($data['shipping']);
			$this->em->flush();
			
			$data['message'] = '<p class="message_success">Sve izmene su uspešno izvršene!</p>';
			
			$this->_render_view( 'cart/edit_shipping', $data );
			
        } else show_404();
	 }

	 public function change_status( $id ){
         
          $record = $this->em->getRepository('models\Entities\Cart\ShippingOption')->find($id);
		  $record->getStatus() ? $record->setStatus(0) : $record->setStatus(1); 
          
          $this->em->flush();

          $this->output->set_output($record->getStatus());
     }

	 public function delete() {
		
		$id_list = explode( ',', $this->input->post('items') );
		
		$this->em->getRepository('models\Entities\Cart\ShippingOption')->deleteShipping($id_list);
		
		$this->output->set_output( TRUE );
	 }
	 
	 private function create_icon($icon = NULL) {

        if(!$_FILES['icon']['size']) return $icon;

        $upload_config['encrypt_name'] = TRUE;
        $upload_config['upload_path'] 		= SERVER_IMAGE_PATH.'cart/shipping/';
        $upload_config['allowed_types'] = 'gif|jpg|png';
        $upload_config['max_size'] = '2048';
        $upload_config['remove_spaces'] = TRUE;
		
		$this->load->library('upload');

        $this->upload->initialize($upload_config);

        if($this->upload->do_upload('icon')) {

            $image_data = $this->upload->data();
			
			$this->load->library('Resizer_Librarie');

            $this->resizer_librarie->set($image_data['full_path']);
            $this->resizer_librarie->resize_image(70,50,'crop',SERVER_PATH . '/assets/img/cart/shipping/'.$image_data['file_name']);

			if( $icon ) {
				unlink(SERVER_PATH.'/assets/img/cart/shipping/'.$icon);
			}
			
            return $image_data['file_name'];
				
        } else return NULL;
    }
}
 
 /* End of file shipping.php */
 /* Location: ./system/applications/_backend/controllers/cart/shipping.php */