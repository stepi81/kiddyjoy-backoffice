<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */

 use models\Entities\News\Info;
 
 class News extends MY_Controller {
     
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

         $this->session->set_userdata( 'news_type_id', $type_id );
         $news_types = unserialize(NEWS_TYPE);
			
		$this->resources['css'][] = 'flexigrid';
		$this->resources['js'][] = 'flexigrid';

		$colModel['image']  = array( 'Thumb', 234, FALSE, 'center', 0 );
		$colModel['date']  = array( 'Datum', 100, TRUE, 'center', 1 );
		$colModel['title']  = array( $news_types[$type_id], 200, TRUE, 'center', 1 );
		$colModel['status'] = array( 'Status', 50, TRUE, 'center', 0 );
		$colModel['comments'] = array('Komentari', 50, FALSE, 'center', 0);
		$colModel['actions'] = array( 'Detalji', 50, FALSE, 'center', 0 );

        if($type_id == 1) { 
            $this->gridParams['title'] = 'Pregled svih novosti';
            $data['grid_title'] = "Novosti"; 
            $buttons[] = array('Nova novost', 'add', 'grid_commands', site_url("news/create"));
            $buttons[] = array('separator');
            $buttons[] = array('Obriši novost', 'delete', 'grid_commands', site_url("news/delete"));
            $buttons[] = array('separator');
        } else { 
            $this->gridParams['title'] = 'Pregled svih akcija';
            $data['grid_title'] = "Akcije"; 
            $buttons[] = array('Nova akcija', 'add', 'grid_commands', site_url("news/create"));
            $buttons[] = array('separator');
            $buttons[] = array('Obriši akciju', 'delete', 'grid_commands', site_url("news/delete"));
            $buttons[] = array('separator');
        }
		
		
		$buttons[] = array('Izaberi sve', 'select_all', 'grid_commands', '/select');
		$buttons[] = array('separator');
		$buttons[] = array('Poništi izbor', 'deselect_all', 'grid_commands', '/desel');
		$buttons[] = array('separator');

        if( $this->input->post('page') ) $this->gridParams['newp'] = $this->input->post('page');
		$data['grid'] = build_grid_js('grid', site_url("news/grid/".$type_id), $colModel, 'id', 'ASC', $this->gridParams, $buttons);

		$this->_render_view( "master/grid_view", $data );
	 }
     
     public function create() {
		
		$this->resources['js'][] = 'checkbox';
		$this->resources['css'][]='datepicker';
        
        $data['vendors'] = $this->em->getRepository('models\Entities\Vendor')->findAll(); 
         
        $this->_render_view( 'news/create_info', $data );
     }
	 
	 public function details( $id ) {

        if( $data['news'] = $this->em->getRepository('models\Entities\News\Info')->find($id) ) {
        	
            $data['vendors'] = $this->em->getRepository('models\Entities\Vendor')->findAll(); 
            
			$this->resources['css'][] = 'plupload';
		    $this->resources['css'][]='datepicker';
            
        	$this->resources['js'][] = 'checkbox';
			$this->resources['js'][] = 'tiny_mce';
			$this->resources['js'][] = 'plupload_full';
			$this->resources['js'][] = 'plupload_queue';
			
			try {
                 $data['product_ids'] = array();   
                 $product_list = $data['news']->getProducts();
                 foreach( $product_list as $product ) {
                    $data['product_ids'][] = $product->getID();
                 }
            } catch( \Doctrine\ORM\EntityNotFoundException $e ) {
                 $data['product_ids'] = array();
            }
			
			$data['plupload'] = build_plupload_js( site_url('upload/news/'.$data['news']->getID()) );
			$data['tinymce'] = build_tinymce_js('page', 925, 700, site_url('proxy/get_news_images/'.$id));
			
        	$this->_render_view( 'news/edit_info', $data );
        }
		else show_404();
     }
     
     public function save() {
     	
		$this->resources['js'][] = 'checkbox';
        $this->resources['css'][] = 'datepicker';
        
        $data['vendors'] = $this->em->getRepository('models\Entities\Vendor')->findAll(); 
        
        // TODO server validation 
        
        $data['news'] = new Info(); 
		
		if( $thumb = $this->create_thumb() ) {
			
            $data['news']->setNewsTypeID( $this->input->post('news_type_id') );
			$data['news']->setDate(new \DateTime(date( 'Y-m-d', strtotime( $this->input->post('send_date')))));
			$data['news']->setTitle( $this->input->post('title') );
            $data['news']->setSummary( $this->input->post('summary') );
			$data['news']->setThumb( $thumb );
            
			if($this->session->userdata('application_id'))
					$data['news']->setVendor($this->em->getReference('models\Entities\Vendor', $this->session->userdata('application_id')));
				else {
	            if ($this->input->post('vendor_id')){
	                $data['news']->setVendor($this->em->getReference('models\Entities\Vendor', $this->input->post('vendor_id')));
	            }
			}
		
			$this->em->persist($data['news']);
			$this->em->flush();
			
			$this->resources['css'][] = 'plupload';
			
			$this->resources['js'][] = 'tiny_mce';
			$this->resources['js'][] = 'plupload_full';
			$this->resources['js'][] = 'plupload_queue';
			
			$data['plupload'] = build_plupload_js( site_url('upload/news/'.$data['news']->getID()) );
			$data['tinymce'] = build_tinymce_js('page', 925, 700, site_url('proxy/get_news_images/'.$data['news']->getID()));
			
			$this->_render_view( 'news/edit_info', $data );
		}
		else {
			$data['message'] = '<p class="message_error">Došlo je do greške! Molimo Vas proverite unete parametre.</p>';
			$this->_render_view( 'news/create_info', $data );
		}
     }
	 
	 public function edit( $id ) {

        if( $data['news'] = $this->em->getRepository('models\Entities\News\Info')->find($id) ) {
			
			// TODO server validation
			
            $data['vendors'] = $this->em->getRepository('models\Entities\Vendor')->findAll(); 
            
			if( $thumb = $this->create_thumb($data['news']->getThumb()) ) {
				
                $data['news']->setDate(new \DateTime(date( 'Y-m-d', strtotime( $this->input->post('send_date')))));
				$data['news']->setStatus( $this->input->post('status') );
				$data['news']->setTitle( $this->input->post('title') );
				$data['news']->setPage( $this->input->post('page') );
                $data['news']->setSummary( $this->input->post('summary') );
				$data['news']->setThumb( $thumb );
                
				if($this->session->userdata('application_id'))
					$data['news']->setVendor($this->em->getReference('models\Entities\Vendor', $this->session->userdata('application_id')));
				else { 
	                if ($this->input->post('vendor_id')){
	                    $data['news']->setVendor($this->em->getReference('models\Entities\Vendor', $this->input->post('vendor_id')));
	                }else{
	                    $data['news']->setVendor(NULL);   
	                }
				}
				
				$data['news']->getProducts()->clear();
            
	            if ($this->input->post('product_id') != ''){
	                if(count(array_filter(array_unique(($this->input->post('product_id'))))) == $this->em->getRepository('models\Entities\News\Info')->getNewsProducts($this->input->post('product_id'))) {
	                    
	                    foreach(array_filter(array_unique(($this->input->post('product_id')))) as $value) {
	                        $data['news']->setProduct($this->em->getReference('models\Entities\Product', $value));
	                        $data['message'] = '<p class="message_success">Vest je uspesno izmenjena.</p>';
	                    }
	                 } else {
	                     $data['message'] = '<p class="message_error">Doslo je do greske prilikom unosa ID Proizvoda, proverite ID listu.</p>';
	                 }
	            }
				
				$this->em->persist($data['news']);
				$this->em->flush();
				
				$data['message'] = '<p class="message_success">Sve izmene su uspešno izvršene!</p>';
			}
			else {
				$data['message'] = '<p class="message_error">Došlo je do greške! Molimo Vas proverite unete parametre.</p>';
			}
			$this->resources['css'][] = 'plupload';
		    $this->resources['css'][]='datepicker';
			$this->resources['js'][] = 'checkbox';
			$this->resources['js'][] = 'tiny_mce';
			$this->resources['js'][] = 'plupload_full';
			$this->resources['js'][] = 'plupload_queue';
			
			try {
                 $data['product_ids'] = array();   
                 $product_list = $data['news']->getProducts();
                 foreach( $product_list as $product ) {
                    $data['product_ids'][] = $product->getID();
                 }
            } catch( \Doctrine\ORM\EntityNotFoundException $e ) {
                 $data['product_ids'] = array();
            }
			
			$data['plupload'] = build_plupload_js( site_url('upload/news/'.$data['news']->getID()) );
			$data['tinymce'] = build_tinymce_js('page', 925, 700, site_url('proxy/get_news_images/'.$id));
			
        	$this->_render_view( 'news/edit_info', $data );
        }
		else show_404();
     }

     public function delete() {
		
		$id_list = explode( ',', $this->input->post('items') );
		
		$this->em->getRepository('models\Entities\News\Info')->deleteNews($id_list);
		$this->output->set_output( TRUE );
	 }
     
     public function grid( $type_id ) {

		$valid_fields = array('date', 'title', 'status');
         
 		$this->flexigrid->validate_post($this->gridParams['id'], 'date', 'DESC', $valid_fields);
 		$criteria = $this->flexigrid->get_criteria();
 		$records = $this->em->getRepository('models\Entities\News\Info')->getNews( $criteria, $type_id );
         
 		$this->session->unset_userdata('edit_visited');
 		$this->output->set_header($this->config->item('json_header'));
 		$this->output->set_output($this->flexigrid->json_build($records['record_count'],$records['record_items']));
     }
	 
	 public function change_status( $id ){
         
          $news = $this->em->getRepository('models\Entities\News\Info')->find($id);
		  $news->getStatus() ? $news->setStatus(0) : $news->setStatus(1); 
          
          $this->em->flush();

          $this->output->set_output($news->getStatus());
     }
	 
	 private function create_thumb( $thumb = NULL ) {
	 	
		if( !$_FILES['thumb']['size'] ) return $thumb;
		
		$upload_config['encrypt_name'] 		= TRUE;
        $upload_config['upload_path'] 		= SERVER_IMAGE_PATH.'news/thumb/';
        $upload_config['allowed_types'] 	= 'gif|jpg|png';
        $upload_config['max_size']			= '2048';
        $upload_config['remove_spaces'] 	= TRUE;
		
		$this->load->library('upload');
        
        $this->upload->initialize($upload_config);
		
		if( $this->upload->do_upload('thumb') ) {
			
            $image_data = $this->upload->data();
			
			$resize_config['image_library'] 	= 'gd2';
			$resize_config['source_image']		= $image_data['full_path'];
			$resize_config['width']				= 206;
			$resize_config['height'] 			= 131;
			$resize_config['maintain_ratio']	= TRUE;
			$resize_config['master_dim']		= $image_data['image_width']/$image_data['image_height'] < $resize_config['width']/$resize_config['height'] ? 'width' : 'height';
			
			$this->load->library('image_lib', $resize_config);
			
			if ( $this->image_lib->resize() ) {
				
				if( $thumb ) unlink( SERVER_IMAGE_PATH.'news/thumb/'.$thumb );
				
				$this->image_lib->clear();
				
				$crop_config['image_library']	= 'gd2';
				$crop_config['source_image']	= $image_data['full_path'];
				$crop_config['width']			= 206;
				$crop_config['height'] 			= 131;
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
 
 /* End of file news.php */
 /* Location: ./system/applications/_backend/controllers/news.php */