<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * ...
 * @author Ivan Despic [ Codeion ]
 */
 
 class Categories extends MY_Controller {
     
     public $gridParams = array(
        'id'                    => 'categoriesGrid',
        'width'                 => 'auto',
        'height'                => 400,
        'rp'                    => 15,
        'rpOptions'             => '[10,15,20,25,40]',
        'pagestat'              => 'Prikaz: {from} do {to} / Ukupno: {total}.',
        'pagetext'              => 'Stranica',
        'outof'                 => 'od',
        'findtext'              => 'Pronađi',
        'procmsg'               => 'Obrada u toku, molimo sačekajte...',
        'blockOpacity'          => 0.5,
        'showTableToggleBtn'    => true
     );
     
     public function __construct() {
         
        parent::__construct();
        
        $this->load->helper(array('form', 'url'));
        $this->load->helper('flexigrid');

        $this->load->library('Flexigrid');
		$this->load->library('Cache_Manager');
         
        $this->resources['css'] = array();
        $this->resources['js'] = array();
     }
     
     public function listing() {
            
        $this->resources['css'][] = 'flexigrid';
        $this->resources['js'][] = 'flexigrid';

        $this->gridParams['title'] = 'Pregled svih kategorija proizvoda';

        $colModel['position']  = array( 'Pozicija', 50, TRUE, 'center', 1 );
		$colModel['id']  = array( 'ID', 50, TRUE, 'center', 1 );
        $colModel['name'] = array( 'Ime', 200, FALSE, 'center', 0 );
        $colModel['groups'] = array('Grupe', 80, FALSE, 'center', 0);
		$colModel['products'] = array( 'Porizvodi', 80, FALSE, 'center', 0 );
		$colModel['brands'] = array( 'Brendovi', 50, FALSE, 'center', 0);
		$colModel['actions'] = array( 'Detalji', 80, FALSE, 'center', 0 ); 
 
        if( $this->input->post('page') ) $this->gridParams['newp'] = $this->input->post('page');
        $data['grid'] = build_grid_js('grid', site_url("product/categories/grid"), $colModel, 'position', 'ASC', $this->gridParams);

        $data['grid_title'] = "Kategorije proizvoda";
        $this->_render_view( "master/grid_view", $data );
     }
     
          
     public function grid() {
          
         $valid_fields = array( 'name', 'position', 'id' );
         
         $this->flexigrid->validate_post($this->gridParams['id'], 'position', 'ASC', $valid_fields);
         $criteria = $this->flexigrid->get_criteria();

         $records = $this->em->getRepository('models\Entities\Product\Category')->getCategories( $criteria );
         
         $this->session->unset_userdata('edit_visited');
         $this->output->set_header($this->config->item('json_header'));
         $this->output->set_output($this->flexigrid->json_build($records['record_count'],$records['record_items']));
     }
	 
	 public function details($category_id, $message=NULL) {
     
        if ($data['category'] = $this->em->getRepository('models\Entities\Product\Category')->find($category_id)) {
            $data['message'] = $message;   
            $this->_render_view('product/category/edit_category', $data);
        } else show_404();    
     }
	 
	 public function edit($category_id) {
           
         if($data['category'] = $this->em->getRepository('models\Entities\Product\Category')->find($category_id)) {
               
            $data['category']->setName( $this->input->post('name') );
			 
			if( $thumb = $this->create_thumb($data['category']->getImage()) ) $data['category']->setImage( $thumb );

            $this->em->persist($data['category']);
            $this->em->flush();
            
            $data['message'] = '<p class="message_success"  style="width: 373px; padding: 8px 5px;">Sve izmene su uspešno sačuvane!</p>';  
			
			$this->cache_manager->deleteCache('EcomCatalog_CatalogRepository_getCategories');
			$this->cache_manager->deleteCache('EcomCatalog_CatalogRepository_getCategoryMenus');
			$this->cache_manager->deleteCache('EcomCatalog_CatalogRepository_getSubcategoryBrands');
			  
			$this->cache_manager->deleteCache('EcomCatalog_MenuRepository_getCategoryMenus');
			$this->cache_manager->deleteCache('EcomCatalog_MenuRepository_getMenuSubcategoryBrands');
			$this->cache_manager->deleteCache('EcomCatalog_CatalogRepository_getMenuAds');
			
            $this->_render_view('product/category/edit_category', $data);   
       
		} else show_404(); 
    }
	 
	 public function category_brand_listing( $category_id, $message = NULL ) {

        $this->resources['css'][] = 'flexigrid';
        $this->resources['js'][] = 'flexigrid';
		$this->resources['js'][] = 'checkbox';

        $this->gridParams['title']='Pregled brendova';

        $colModel['name'] = array( 'Ime', 200, TRUE, 'center', 1 );

        if( $this->input->post('page') ) $this->gridParams['newp'] = $this->input->post('page');

        $data['grid'] = build_grid_js('grid', site_url("product/categories/category_brands_grid/" . $category_id), $colModel, 'name', 'ASC', $this->gridParams);
		$data['category'] = $this->em->getRepository('models\Entities\Product\Category')->find($category_id);
		//$data['brands'] = $this->em->getRepository('models\Entities\Product\Brand')->getSubcategoryBrands($data['subcategory']);
		$data['brands'] = $this->em->getRepository('models\Entities\Product\Brand')->findAll();

		
        $data['grid_title'] = 'Brendovi - ' . $data['category']->getName() ;
        If (isset($message)){$data['message'] = $message;}
		
		$this->cache_manager->deleteCache('EcomCatalog_CatalogRepository_getCategories');
		$this->cache_manager->deleteCache('EcomCatalog_CatalogRepository_getCategoryMenus');
		$this->cache_manager->deleteCache('EcomCatalog_CatalogRepository_getSubcategoryBrands');
		  
		$this->cache_manager->deleteCache('EcomCatalog_MenuRepository_getCategoryMenus');
		$this->cache_manager->deleteCache('EcomCatalog_MenuRepository_getMenuSubcategoryBrands');
		$this->cache_manager->deleteCache('EcomCatalog_CatalogRepository_getMenuAds');
		
        $this->_render_view("product/category/create_brands", $data);
     }
     
     public function category_brands_grid( $category_id ){
       
         $valid_fields = array( 'name' );
         $this->flexigrid->validate_post($this->gridParams['id'], 'name', 'ASC', $valid_fields);
         $criteria = $this->flexigrid->get_criteria();
         $records = $this->em->getRepository('models\Entities\Product\Category')->getCategoryBrands($criteria, $category_id);

         $this->session->unset_userdata('edit_visited');
         $this->output->set_header($this->config->item('json_header'));
         $this->output->set_output($this->flexigrid->json_build($records['record_count'],$records['record_items']));
     }
	 
	 public function set_brands() {

		
		if($data['category'] = $this->em->getRepository('models\Entities\Product\Category')->find($this->input->post('category_id'))) {
			
			  $data['category']->getBrands()->clear();
              
			  foreach( $this->input->post('brand_list') as $key => $value ) { 
              	$data['category']->setBrand( $this->em->getReference('models\Entities\Product\Brand', $value) );
			  }
			  
			  $this->em->persist($data['category']);
              $this->em->flush();
			  
			  $this->cache_manager->deleteCache('EcomCatalog_CatalogRepository_getCategories');
			  $this->cache_manager->deleteCache('EcomCatalog_CatalogRepository_getCategoryMenus');
			  $this->cache_manager->deleteCache('EcomCatalog_CatalogRepository_getSubcategoryBrands');
			  
			  $this->cache_manager->deleteCache('EcomCatalog_MenuRepository_getCategoryMenus');
			  $this->cache_manager->deleteCache('EcomCatalog_MenuRepository_getMenuSubcategoryBrands');
			  $this->cache_manager->deleteCache('EcomCatalog_CatalogRepository_getMenuAds');

			  redirect( 'product/categories/category_brand_listing/'.$this->input->post('category_id') );  
			     
         } else show_404(); 
		
	 }

	 private function create_thumb( $thumb = NULL ) {
	 	
		if( !$_FILES['thumb']['size'] ) return $thumb;
		
		$upload_config['encrypt_name'] 		= FALSE;
        $upload_config['upload_path'] 		= SERVER_IMAGE_PATH.'icons/categories/';
        $upload_config['allowed_types'] 	= 'gif|jpg|png';
        $upload_config['max_size']			= '2048';
        $upload_config['remove_spaces'] 	= TRUE;
		
		$this->load->library('upload');
        
        $this->upload->initialize($upload_config);
		
		if( $this->upload->do_upload('thumb') ) {
			
            $image_data = $this->upload->data();
			
			$resize_config['image_library'] 	= 'gd2';
			$resize_config['source_image']		= $image_data['full_path'];
			$resize_config['width']				= 80;
			$resize_config['height'] 			= 80;
			$resize_config['maintain_ratio']	= TRUE;
			$resize_config['master_dim']		= $image_data['image_width']/$image_data['image_height'] < $resize_config['width']/$resize_config['height'] ? 'width' : 'height';
			
			$this->load->library('image_lib', $resize_config);
			
			if ( $this->image_lib->resize() ) {
				
				if( $thumb ) unlink( SERVER_IMAGE_PATH.'icons/categories/'.$thumb );
				
				$this->image_lib->clear();
				
				$crop_config['image_library']	= 'gd2';
				$crop_config['source_image']	= $image_data['full_path'];
				$crop_config['width']			= 80;
				$crop_config['height'] 			= 80;
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
 
 /* End of file categories.php */
 /* Location: ./system/applications/_backend/controllers/product/categories.php */