<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * ...
 * @author Aleksandar Milas [ Codeion ]
 */

 use models\Entities\Preorder;
 use models\Entities\PreorderItem;
 
 class Preorder_Pages extends MY_Controller {
     
     public $gridParams = array(
        'id'                   => 'productsGrid',
        'width'                 => 'auto',
        'height'                => 400,
        'rp'                    => 15,
        'rpOptions'             => '[10,15,20,25,40]',
        'pagestat'              => 'Prikaz: {from} do {to} / Ukupno: {total} vesti.',
        'pagetext'				=> 'Stranica',
		'outof'					=> 'od',
        'findtext'              => 'Pronađi',
        'procmsg'				=> 'Obrada u toku, molimo sačekajte...',
        'blockOpacity'          => 0.5,
        'showTableToggleBtn'    => true
     );
     
     public function __construct() {
         
         parent::__construct();
		 
		 $this->load->helper('flexigrid');
		 $this->load->helper('upload');
		 $this->load->helper('tinymce');
		 
		 $this->load->library('Flexigrid');
		 
		 $this->resources['css'] = array();
         $this->resources['js'] = array();
     }
	 
	 public function listing( $type_id ) {

         $this->session->set_userdata( 'preorder_type_id', $type_id );
         //$preorder_types = unserialize(NEWS_TYPE);
			
		$this->resources['css'][] = 'flexigrid';
		$this->resources['js'][] = 'flexigrid';

		$colModel['image']  = array( 'Thumb', 234, FALSE, 'center', 0 );
        //$colModel['vendor']  = array( 'Vendor', 100, TRUE, 'center', 1 );
		$colModel['date']  = array( 'Datum isteka', 100, TRUE, 'center', 1 );
		$colModel['title']  = array( 'Naslov', 200, TRUE, 'center', 1 );
		$colModel['status'] = array( 'Status', 50, TRUE, 'center', 0 );
		$colModel['actions'] = array( 'Detalji', 50, FALSE, 'center', 0 );

        if($type_id == 1) { 
            $this->gridParams['title'] = 'Pregled svih preordera';
            $data['grid_title'] = "Preorderi"; 
            $buttons[] = array('Novi preorder', 'add', 'grid_commands', site_url("preorder_pages/create"));
            $buttons[] = array('separator');
            //$buttons[] = array('Obriši preorder', 'delete', 'grid_commands', site_url("preorder_pages/delete"));
            //$buttons[] = array('separator');
        // } else { //TODO If a new preorder type is needed
            // $this->gridParams['title'] = 'Pregled svih akcija';
            // $data['grid_title'] = "Akcije"; 
            // $buttons[] = array('Nova akcija', 'add', 'grid_commands', site_url("news/create"));
            // $buttons[] = array('separator');
            // $buttons[] = array('Obriši akciju', 'delete', 'grid_commands', site_url("news/delete"));
            // $buttons[] = array('separator');        }
		
		
		//$buttons[] = array('Izaberi sve', 'select_all', 'grid_commands', '/select');
		//$buttons[] = array('separator');
		//$buttons[] = array('Poništi izbor', 'deselect_all', 'grid_commands', '/desel');
		//$buttons[] = array('separator');

        if( $this->input->post('page') ) $this->gridParams['newp'] = $this->input->post('page');
		$data['grid'] = build_grid_js('grid', site_url("preorder_pages/grid/".$type_id), $colModel, 'id', 'ASC', $this->gridParams, $buttons);

		$this->_render_view( "master/grid_view", $data );
	 }
     
     public function create() {
		
		$this->resources['js'][]  = 'checkbox';
		$this->resources['css'][] = 'datepicker';
        
        $this->_render_view( 'public_relations/create_preorder' );
     }
	 
	 public function details( $id ) {

        if( $data['preorder'] = $this->em->getRepository('models\Entities\Preorder')->find($id) ) {
        	
			$this->resources['css'][] = 'plupload';
		    $this->resources['css'][] = 'datepicker';
            
        	$this->resources['js'][] = 'checkbox';
			$this->resources['js'][] = 'tiny_mce';
			$this->resources['js'][] = 'plupload_full';
			$this->resources['js'][] = 'plupload_queue';
			
			try {
                 $data['product_ids'] = array();   
				 
				 $product_list = $this->em->getRepository('models\Entities\PreorderItem')->findBy(array( 'preorder' => $id));
                 foreach( $product_list as $product ) {
                    $data['product_ids'][] = $product->getProductID() ? $product->getProductID() : $product->getName();
                 }
            }
            catch( \Doctrine\ORM\EntityNotFoundException $e ) {
                 $data['product_ids'] = array();
            }    
			
			$data['plupload'] = build_plupload_js( site_url('upload/preorders/'.$data['preorder']->getID()) );
			$data['tinymce'] = build_tinymce_js('page', 600, 700, site_url('proxy/get_preorder_images/'.$id));
			
        	$this->_render_view( 'public_relations/edit_preorder', $data );
        }
		else show_404();
     }
     
     public function save() {
     	
		$this->resources['js'][] = 'checkbox';
        $this->resources['css'][] = 'datepicker';
        
        // TODO server validation 
        
        $data['preorder'] = new Preorder(); 
		
		if( $thumb = $this->create_thumb() ) {
			
            $data['preorder']->setPreorderTypeID( $this->input->post('preorder_type_id') );
			$data['preorder']->setDate(new \DateTime(date( 'Y-m-d', strtotime( $this->input->post('send_date')))));
			$data['preorder']->setTitle( $this->input->post('title') );
            $data['preorder']->setSummary( $this->input->post('summary') );
			$data['preorder']->setThumb( $thumb );
			
			$this->em->persist($data['preorder']);
			$this->em->flush();
			
			$this->resources['css'][] = 'plupload';
			
			$this->resources['js'][] = 'tiny_mce';
			$this->resources['js'][] = 'plupload_full';
			$this->resources['js'][] = 'plupload_queue';
			
			$data['plupload'] = build_plupload_js( site_url('upload/preorders/'.$data['preorder']->getID()) );
			
			$data['tinymce'] = build_tinymce_js('page', 600, 700, site_url('proxy/get_preorder_images/'.$data['preorder']->getID()));
			
			$this->_render_view( 'public_relations/edit_preorder', $data );
		}
		else {
			$data['message'] = '<p class="message_error">Došlo je do greške! Molimo Vas proverite unete parametre.</p>';
			$this->_render_view( 'public_relations/create_preorder', $data );
		}
     }
	 
	 public function edit( $id ) {

        if( $data['preorder'] = $this->em->getRepository('models\Entities\Preorder')->find($id) ) {
			
			// TODO server validation
			
			if( $thumb = $this->create_thumb($data['preorder']->getThumb()) ) {
				
                $data['preorder']->setDate(new \DateTime(date( 'Y-m-d', strtotime( $this->input->post('send_date')))));
				$data['preorder']->setStatus( $this->input->post('status') );
				$data['preorder']->setTitle( $this->input->post('title') );
				$data['preorder']->setPage( $this->input->post('page') );
                $data['preorder']->setSummary( $this->input->post('summary') );
				$data['preorder']->setThumb( $thumb );
				
							
				$product_list = $this->em->getRepository('models\Entities\PreorderItem')->findBy(array( 'preorder' => $id));
				$product_list_ids = array();
				
	            if($product_list)
					foreach ($product_list as $value) {
						if( !( in_array($value->getProductName(), $this->input->post('product_id')) || in_array($value->getProductID(), $this->input->post('product_id')) ) )
							$this->em->remove($value);
						else $product_list_ids[] = $value->getProductID() ? $value->getProductID() : $value->getProductName();
					}
				
				$data['product_ids'] = array();
				
	            if ($this->input->post('product_id') != ''){
	                    
	                    foreach(array_filter(array_unique(($this->input->post('product_id')))) as $value) {
	                    	
							if( !in_array($value, $product_list_ids) )
							{
								$item = new PreorderItem;
								if($product = $this->em->getRepository('models\Entities\Product')->find($value)) {
									$item->setProduct($product);
								}
								else $item->setName($value);
								$item->setPreorder($data['preorder']);
								
								$this->em->persist($item);
								$data['preorder']->setItem($item);
							}
							
							$data['product_ids'][] = $value;
	                    }
	            }
            
				
				$this->em->persist($data['preorder']);
				$this->em->flush();
				
				$data['message'] = '<p class="message_success">Sve izmene su uspešno izvršene!</p>';
			}
			else {
				$data['message'] = '<p class="message_error">Došlo je do greške! Molimo Vas proverite unete parametre.</p>';
			}
			$this->resources['css'][] = 'plupload';
		    $this->resources['css'][] = 'datepicker';
			$this->resources['js'][] = 'checkbox';
			$this->resources['js'][] = 'tiny_mce';
			$this->resources['js'][] = 'plupload_full';
			$this->resources['js'][] = 'plupload_queue';
			
			$data['plupload'] = build_plupload_js( site_url('upload/preorders/'.$data['preorder']->getID()) );
			$data['tinymce'] = build_tinymce_js('page', 600, 700, site_url('proxy/get_preorder_images/'.$id));
			
			//echo site_url('upload/preorders/'.$data['preorder']->getID());return;
			
        	$this->_render_view( 'public_relations/edit_preorder', $data );
        }
		else show_404();
     }

     public function delete() {
		
		$id_list = explode( ',', $this->input->post('items') );
		
		$this->em->getRepository('models\Entities\Preorder')->deletePreorders($id_list);
		$this->output->set_output( TRUE );
	 }
     
     public function grid( $type_id ) {

		$valid_fields = array('date', 'title', 'status');
         
 		$this->flexigrid->validate_post($this->gridParams['id'], 'date', 'DESC', $valid_fields);
 		$criteria = $this->flexigrid->get_criteria();
 		$records = $this->em->getRepository('models\Entities\Preorder')->getPreorders( $criteria, $type_id );
         
 		$this->session->unset_userdata('edit_visited');
 		$this->output->set_header($this->config->item('json_header'));
 		$this->output->set_output($this->flexigrid->json_build($records['record_count'],$records['record_items']));
     }
	 
	 public function change_status( $id ){
         
          $preorder = $this->em->getRepository('models\Entities\Preorder')->find($id);
		  $preorder->getStatus() ? $preorder->setStatus(0) : $preorder->setStatus(1); 
          
          $this->em->flush();

          $this->output->set_output($preorder->getStatus());
     }
	 
	 private function create_thumb( $thumb = NULL ) {
	 	
		if( !$_FILES['thumb']['size'] ) return $thumb;
		
		$upload_config['encrypt_name'] 		= TRUE;
        $upload_config['upload_path'] 		= SERVER_IMAGE_PATH.'preorders/';
        $upload_config['allowed_types'] 	= 'gif|jpg|png';
        $upload_config['max_size']			= '2048';
        $upload_config['remove_spaces'] 	= TRUE;
		
		$this->load->library('upload');
        
        $this->upload->initialize($upload_config);
		
		if( $this->upload->do_upload('thumb') ) {
			
            $image_data = $this->upload->data();
			
			$resize_config['image_library'] 	= 'gd2';
			$resize_config['source_image']		= $image_data['full_path'];
			$resize_config['width']				= 105;
			$resize_config['height'] 			= 105;
			$resize_config['maintain_ratio']	= TRUE;
			$resize_config['master_dim']		= $image_data['image_width']/$image_data['image_height'] < $resize_config['width']/$resize_config['height'] ? 'width' : 'height';
			
			$this->load->library('image_lib', $resize_config);
			
			if ( $this->image_lib->resize() ) {
				
				if( $thumb ) unlink( SERVER_IMAGE_PATH.'preorders/'.$thumb );
				
				$this->image_lib->clear();
				
				$crop_config['image_library']	= 'gd2';
				$crop_config['source_image']	= $image_data['full_path'];
				$crop_config['width']			= 105;
				$crop_config['height'] 			= 105;
				$crop_config['maintain_ratio'] 	= FALSE;
				
				$imageSize = $this->image_lib->get_image_properties($image_data['full_path'], TRUE);
				
				switch( $resize_config['master_dim'] ) {
					case 'width':
						$crop_config['y_axis'] = ($imageSize['height'] - $crop_config['height']) / 2;
						break;
					case 'height':
						$crop_config['x_axis'] = ($imageSize['width'] - $crop_config['width']) / 2;
						break;
				}
				$this->image_lib->initialize($crop_config);
				
				if ( $this->image_lib->crop() ) {
					$this->image_lib->clear();
				}
				return $image_data['file_name'];
			}
			else return NULL;
        }
		else return NULL;
	 }
 }
 
 /* End of file preorder_pages.php */
 /* Location: ./system/applications/_backend/controllers/preorder_pages.php */