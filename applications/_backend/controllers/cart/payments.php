<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * ...
 * @author Marko Stepanovic [ Codeion ]
 */

 use models\Entities\Cart\PaymentOption;
 
 class Payments extends MY_Controller {
     
     public $gridParams = array(
        'id'                    => 'cartPaymentGrid',
        'width'                 => 'auto',
        'height'                => 400,
        'rp'                    => 15,
        'rpOptions'             => '[10,15,20,25,40]',
        'pagestat'              => 'Prikaz: {from} do {to} Ukupno: {total} plaćanja.',
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
        
		$this->gridParams['title'] = 'Pregled svih plaćanja';
		
		$colModel['id']  = array( 'ID', 80, FALSE, 'center', 0 ); 
		$colModel['title']  = array( 'Naslov', 200, TRUE, 'center', 1 );
		$colModel['status'] = array( 'Status', 50, TRUE, 'center', 0 );
		$colModel['actions']   = array( 'Detalji', 80, FALSE, 'center', 0 ); 
		
		$buttons[] = array('Novo plaćanje', 'add', 'grid_commands', site_url("cart/payments/create"));
        $buttons[] = array('separator');
        $buttons[] = array('Obriši plaćanje', 'delete', 'grid_commands', site_url("cart/payments/delete"));
        $buttons[] = array('separator');
		$buttons[] = array('Izaberi sve', 'select_all', 'grid_commands', '/select');
		$buttons[] = array('separator');
		$buttons[] = array('Poništi izbor', 'deselect_all', 'grid_commands', '/desel');
		$buttons[] = array('separator');
        
        if( $this->input->post('page') ) $this->gridParams['newp'] = $this->input->post('page');
		$data['grid'] = build_grid_js('grid', site_url("cart/payments/grid"), $colModel, 'id', 'ASC', $this->gridParams, $buttons);

		$data['grid_title'] = "Korpa - Plaćanja";
		$this->_render_view( "master/grid_view", $data );
	 }

     public function grid() {
     	 
		$valid_fields = array('title');
         
 		$this->flexigrid->validate_post($this->gridParams['id'], 'id', 'ASC', $valid_fields);
 		$criteria = $this->flexigrid->get_criteria();
 		$records = $this->em->getRepository('models\Entities\Cart\PaymentOption')->getPaymentOptions( $criteria );
         
 		$this->session->unset_userdata('edit_visited');
 		$this->output->set_header($this->config->item('json_header'));
 		$this->output->set_output($this->flexigrid->json_build($records['record_count'],$records['record_items']));
     }

	 public function create() {
        	
		$this->resources['js'][] = 'checkbox';
		
    	$this->_render_view( 'cart/create_payment' );
     }
	 
	 public function save() {
	 	
		$this->resources['js'][] = 'checkbox';
        
        $data['payment'] = new PaymentOption(); 
		
		if( $icon = $this->create_icon() ) {
			$data['payment']->setIcon( $icon );
		}

		$data['payment']->setPlugin( $this->input->post('plugin') );
		$data['payment']->setTitle( $this->input->post('title') );
        $data['payment']->setDescription( $this->input->post('description') );
		$data['payment']->setStatus( $this->input->post('status') );

		$this->em->persist($data['payment']);
		$this->em->flush();
		
		$data['message'] = '<p class="message_success">Uspešno ste dodali nov način Plćanja</p>';
		
		$this->_render_view( 'cart/create_payment', $data );
	 }

	 public function details( $id ) {

        if( $data['payment'] = $this->em->getRepository('models\Entities\Cart\PaymentOption')->find($id) ) {
        	
			$this->resources['js'][] = 'checkbox';
			
        	$this->_render_view( 'cart/edit_payment', $data );
        }
		else show_404();
     }
	 
	 public function edit( $id ) {
	 	
		if( $data['payment'] = $this->em->getRepository('models\Entities\Cart\PaymentOption')->find($id) ) {
			
			$this->resources['js'][] = 'checkbox';
			
			if( $icon = $this->create_icon($data['payment']->getIcon()) ) {
				$data['payment']->setIcon( $icon );	
			}
	
			$data['payment']->setPlugin( $this->input->post('plugin') );
			$data['payment']->setTitle( $this->input->post('title') );
	        $data['payment']->setDescription( $this->input->post('description') );
			$data['payment']->setStatus( $this->input->post('status') );
	
			$this->em->persist($data['payment']);
			$this->em->flush();
			
			$data['message'] = '<p class="message_success">Sve izmene su uspešno izvršene!</p>';
			
			$this->_render_view( 'cart/edit_payment', $data );
			
        } else show_404();
	 }
	 
	 public function change_status( $id ){
         
          $record = $this->em->getRepository('models\Entities\Cart\PaymentOption')->find($id);
		  $record->getStatus() ? $record->setStatus(0) : $record->setStatus(1); 
          
          $this->em->flush();

          $this->output->set_output($record->getStatus());
     }

	 public function delete() {
		
		$id_list = explode( ',', $this->input->post('items') );
		
		$this->em->getRepository('models\Entities\Cart\PaymentOption')->deletePayments($id_list);
		
		$this->output->set_output( TRUE );
	 }
	 
	 private function create_icon($icon = NULL) {

        if(!$_FILES['icon']['size']) return $icon;

        $upload_config['encrypt_name'] = TRUE;
        $upload_config['upload_path'] 		= SERVER_IMAGE_PATH.'cart/payment/';
        $upload_config['allowed_types'] = 'gif|jpg|png';
        $upload_config['max_size'] = '2048';
        $upload_config['remove_spaces'] = TRUE;
		
		$this->load->library('upload');

        $this->upload->initialize($upload_config);

        if($this->upload->do_upload('icon')) {

            $image_data = $this->upload->data();
			
			$this->load->library('Resizer_Librarie');

            $this->resizer_librarie->set($image_data['full_path']);
            $this->resizer_librarie->resize_image(70,50,'crop',SERVER_PATH . '/assets/img/cart/payment/'.$image_data['file_name']);

			if( $icon ) {
				unlink(SERVER_PATH.'/assets/img/cart/payment/'.$icon);
			}
			
            return $image_data['file_name'];
				
        } else return NULL;
    }
}
 
 /* End of file payments.php */
 /* Location: ./system/applications/_backend/controllers/cart/payments.php */