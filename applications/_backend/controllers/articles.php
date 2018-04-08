<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * ...
 * @author Marko Stepanovic [ Codeion ]
 */
 
 use models\Entities\Article;
 
 class Articles extends MY_Controller {
     
     public $gridParams = array(
        'id'                    => 'articlesGrid',
        'width'                 => 'auto',
        'height'                => 400,
        'rp'                    => 15,
        'rpOptions'             => '[10,15,20,25,40]',
        'pagestat'              => 'Prikaz: {from} do {to} / Ukupno: {total} članaka.',
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
	 
	 public function listing( $category_id ) {
			
		$this->resources['css'][] = 'flexigrid';
		$this->resources['js'][] = 'flexigrid';
		
		$data['article_category'] = $this->em->getRepository('models\Entities\Article\Category')->find($category_id);

		$colModel['image']  = array( 'Thumb', 234, FALSE, 'center', 0 );
		$colModel['date']  = array( 'Datum', 100, TRUE, 'center', 1 );
		$colModel['title']  = array( 'Naziv', 200, TRUE, 'center', 1 );
		$colModel['status'] = array( 'Status', 50, TRUE, 'center', 0 );
		$colModel['comments'] = array('Komentari', 50, FALSE, 'center', 0);
		$colModel['actions'] = array( 'Detalji', 50, FALSE, 'center', 0 );

        $this->gridParams['title'] = 'Pregled članaka - '.$data['article_category']->getName();
        $data['grid_title'] = "Članci - ".$data['article_category']->getName(); 
		
        $buttons[] = array('Nov članak', 'add', 'grid_commands', site_url("articles/create/".$category_id));
        $buttons[] = array('separator');
        $buttons[] = array('Obriši članak', 'delete', 'grid_commands', site_url("articles/delete"));
        $buttons[] = array('separator');

		$buttons[] = array('Izaberi sve', 'select_all', 'grid_commands', '/select');
		$buttons[] = array('separator');
		$buttons[] = array('Poništi izbor', 'deselect_all', 'grid_commands', '/desel');
		$buttons[] = array('separator');

		if( $data['article_category']->getParent() ) {
			$data['route'] = "article/article_categories/listing/".$data['article_category']->getParent()->getID();
        	$data['params_id'] = 'articleCategoriesGrid';		
		} else {
			$data['route'] = "article/article_categories/listing/";
        	$data['params_id'] = 'articleCategoriesGrid';
		}

        if( $this->input->post('page') ) $this->gridParams['newp'] = $this->input->post('page');
		$data['grid'] = build_grid_js('grid', site_url("articles/grid/".$category_id), $colModel, 'date', 'ASC', $this->gridParams, $buttons);

		$this->_render_view( "master/grid_view", $data );
	 }

	 public function grid( $category_id ) {

		$valid_fields = array('date', 'title', 'status', 'appliance_id');
         
 		$this->flexigrid->validate_post($this->gridParams['id'], 'date', 'DESC', $valid_fields);
 		$criteria = $this->flexigrid->get_criteria();
 		$records = $this->em->getRepository('models\Entities\Article')->getArticles( $criteria, $category_id );
         
 		$this->session->unset_userdata('edit_visited');
 		$this->output->set_header($this->config->item('json_header'));
 		$this->output->set_output($this->flexigrid->json_build($records['record_count'],$records['record_items']));
     }

	 public function create($category_id) {
		
		$this->resources['js'][] = 'checkbox';
		$this->resources['css'][]='datepicker';
		
		$data['category_id'] = $category_id;	
		$data['article_category'] = $this->em->getRepository('models\Entities\Article\Category')->find($category_id);

        $this->_render_view( 'article/create_article', $data );
     }
     
     public function save() {

		$this->resources['css'][] = 'plupload';
		$this->resources['css'][] = 'datepicker';
		
		$this->resources['js'][] = 'checkbox';
		$this->resources['js'][] = 'tiny_mce';
		$this->resources['js'][] = 'plupload_full';
		$this->resources['js'][] = 'plupload_queue';
		
        $data['article_category'] = $this->em->getRepository('models\Entities\Article\Category')->find($this->input->post('category_id'));
        
        $data['article'] = new Article(); 
		
		if( $thumb = $this->create_thumb() ) {
			$data['article']->setThumb( $thumb );
		}
		
		if( $image = $this->create_image() ) {
			$data['article']->setImage( $image );
		}
		
		if( $this->input->post('appliance_id') ) { $data['article']->setApplianceID( $this->input->post('appliance_id') ); } else { $data['article']->setApplianceID(1); }	
        $data['article']->setCategory($this->em->getReference('models\Entities\Article\Category', $this->input->post('category_id')));
		$data['article']->setDate(new \DateTime(date( 'Y-m-d', strtotime( $this->input->post('send_date')))));
		$data['article']->setTitle( $this->input->post('title') );
        $data['article']->setSummary( $this->input->post('summary') );
		$data['article']->setStatus( $this->input->post('status') );
	
		$this->em->persist($data['article']);
		$this->em->flush();

		$data['plupload'] = build_plupload_js( site_url('upload/articles/'.$data['article']->getID()) );
		$data['tinymce'] = build_tinymce_js('page', 925, 700, site_url('proxy/get_article_images/'.$data['article']->getID()));
		
		//$this->_render_view( 'article/edit_article', $data );
		redirect('articles/details/'.$data['article']->getID());
     }
	 
	 public function details( $id ) {

        if( $data['article'] = $this->em->getRepository('models\Entities\Article')->find($id) ) {
        	
			$data['article_categories'] = $this->em->getRepository('models\Entities\Article\Category')->findAll();
            
			$this->resources['css'][] = 'plupload';
		    $this->resources['css'][] = 'datepicker';
            
        	$this->resources['js'][] = 'checkbox';
			$this->resources['js'][] = 'tiny_mce';
			$this->resources['js'][] = 'plupload_full';
			$this->resources['js'][] = 'plupload_queue';
			
			try {
                 $data['product_ids'] = array();   
                 $product_list = $data['article']->getProducts();
                 foreach( $product_list as $product ) {
                    $data['product_ids'][] = $product->getID();
                 }
            } catch( \Doctrine\ORM\EntityNotFoundException $e ) {
                 $data['product_ids'] = array();
            }
			
			$data['plupload'] = build_plupload_js( site_url('upload/articles/'.$data['article']->getID()) );
			$data['tinymce'] = build_tinymce_js('page', 925, 700, site_url('proxy/get_article_images/'.$id));
			
        	$this->_render_view( 'article/edit_article', $data );
        }
		else show_404();
     }

	 public function edit( $id ) {

        if( $data['article'] = $this->em->getRepository('models\Entities\Article')->find($id) ) {
        	
			$this->resources['css'][] = 'plupload';
		    $this->resources['css'][]='datepicker';
			
			$this->resources['js'][] = 'checkbox';
			$this->resources['js'][] = 'tiny_mce';
			$this->resources['js'][] = 'plupload_full';
			$this->resources['js'][] = 'plupload_queue';
			
			$data['article_categories'] = $this->em->getRepository('models\Entities\Article\Category')->findAll();
			
			// TODO server validation
            
			if( $thumb = $this->create_thumb($data['article']->getThumb()) ) {
				$data['article']->setThumb( $thumb );	
			}
			
			if( $image = $this->create_image($data['article']->getImage()) ) {						
				$data['article']->setImage( $image );	
			}
			
			$data['article']->setApplianceID( $this->input->post('appliance_id') );
			$data['article']->setCategory($this->em->getReference('models\Entities\Article\Category', $this->input->post('category_id')));	
            $data['article']->setDate(new \DateTime(date( 'Y-m-d', strtotime( $this->input->post('send_date')))));
			$data['article']->setStatus( $this->input->post('status') );
			$data['article']->setTitle( $this->input->post('title') );
			$data['article']->setContent( $this->input->post('page') );
            $data['article']->setSummary( $this->input->post('summary') );
			
			$data['article']->getProducts()->clear();
        
            if ($this->input->post('product_id') != ''){
                if(count(array_filter(array_unique(($this->input->post('product_id'))))) == $this->em->getRepository('models\Entities\Article')->getArticleProducts($this->input->post('product_id'))) {
                    
                    foreach(array_filter(array_unique(($this->input->post('product_id')))) as $value) {
                        $data['article']->setProduct($this->em->getReference('models\Entities\Product', $value));
                        $data['message'] = '<p class="message_success">Vest je uspesno izmenjena.</p>';
                    }
                 } else {
                     $data['message'] = '<p class="message_error">Doslo je do greske prilikom unosa ID Proizvoda, proverite ID listu.</p>';
                 }
            }
			
			$this->em->persist($data['article']);
			$this->em->flush();
			
			$data['message'] = '<p class="message_success">Sve izmene su uspešno izvršene!</p>';

			try {
                 $data['product_ids'] = array();   
                 $product_list = $data['article']->getProducts();
                 foreach( $product_list as $product ) {
                    $data['product_ids'][] = $product->getID();
                 }
            } catch( \Doctrine\ORM\EntityNotFoundException $e ) {
                 $data['product_ids'] = array();
            }
			
			$data['plupload'] = build_plupload_js( site_url('upload/articles/'.$data['article']->getID()) );
			$data['tinymce'] = build_tinymce_js('page', 925, 700, site_url('proxy/get_article_images/'.$id));
			
        	$this->_render_view( 'article/edit_article', $data );
        }
		else show_404();
     }

     public function delete() {
		
		$id_list = explode( ',', $this->input->post('items') );
		
		$this->em->getRepository('models\Entities\Article')->deleteArticles($id_list);
		$this->output->set_output( TRUE );
	 }
	 
	 public function change_status( $id ){
         
          $record = $this->em->getRepository('models\Entities\Article')->find($id);
		  $record->getStatus() ? $record->setStatus(0) : $record->setStatus(1); 
          
          $this->em->flush();

          $this->output->set_output($record->getStatus());
     }
	 
	 private function create_thumb($thumb = NULL) {

        if(!$_FILES['thumb']['size']) return $thumb;

        $upload_config['encrypt_name'] = TRUE;
        $upload_config['upload_path'] 		= SERVER_IMAGE_PATH.'articles/thumb/';
        $upload_config['allowed_types'] = 'gif|jpg|png';
        $upload_config['max_size'] = '2048';
        $upload_config['remove_spaces'] = TRUE;

        $this->load->library('upload');

        $this->upload->initialize($upload_config);

        if($this->upload->do_upload('thumb')) {

            $image_data = $this->upload->data();
                
            $this->load->library('Resizer_Librarie');

            $this->resizer_librarie->set($image_data['full_path']);
            $this->resizer_librarie->resize_image(199,199,'crop',SERVER_PATH . 'assets/img/articles/thumb/'.$image_data['file_name']);

			
			if( $thumb ) {
				unlink(SERVER_PATH.'assets/img/articles/thumb/'.$thumb);
			}
			
            return $image_data['file_name'];
				
        } else return NULL;
    }

	private function create_image($image = NULL) {

        if(!$_FILES['main_image']['size']) return $image;

        $upload_config['encrypt_name'] = TRUE;
        $upload_config['upload_path'] 		= SERVER_IMAGE_PATH.'articles/large/';
        $upload_config['allowed_types'] = 'gif|jpg|png';
        $upload_config['max_size'] = '2048';
        $upload_config['remove_spaces'] = TRUE;

        $this->load->library('upload');

        $this->upload->initialize($upload_config);

        if($this->upload->do_upload('main_image')) {

            $image_data = $this->upload->data();
                
            $this->load->library('Resizer_Librarie');

            $this->resizer_librarie->set($image_data['full_path']);
            $this->resizer_librarie->resize_image(440,440,'crop',SERVER_PATH . 'assets/img/articles/large/'.$image_data['file_name']);
			
			$this->resizer_librarie->set($image_data['full_path']);
            $this->resizer_librarie->resize_image(199,199,'crop',SERVER_PATH . 'assets/img/articles/medium/'.$image_data['file_name']);

			
			if( $image ) {
				unlink(SERVER_PATH.'assets/img/articles/large/'.$image);
				unlink(SERVER_PATH.'assets/img/articles/medium/'.$image);
			}
			
            return $image_data['file_name'];
				
        } else return NULL;
    }
 }
 
 /* End of file articles.php */
 /* Location: ./system/applications/_backend/controllers/articles.php */