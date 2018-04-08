<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * ...
 * @author Damir Mozar [ ABC Design ]
 */

 use models\Entities\Promotion\PromotionGroup;
 
 class Promotion_Code extends MY_Controller {
     
     public $gridParams = array(
        'id'                   => 'promocodeGrid',
        'width'                 => 'auto',
        'height'                => 400,
        'rp'                    => 15,
        'rpOptions'             => '[10,15,20,25,40]',
        'pagestat'              => 'Prikaz: {from} do {to} / Ukupno: {total} promocija.',
        'pagetext'              => 'Stranica',
        'outof'                 => 'od',
        'findtext'              => 'Pronađi',
        'procmsg'               => 'Obrada u toku, molimo sačekajte...',
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
     
     public function listing() {
            
        $this->resources['css'][] = 'flexigrid';
        $this->resources['js'][] = 'flexigrid';
        
        $this->gridParams['title'] = 'Pregled svih promocija';

        $colModel['title'] = array( 'Naslov', 261, TRUE, 'center', 1 );
		$colModel['code'] = array( 'Kod', 100, TRUE, 'center', 1 );
        $colModel['status'] = array( 'Status', 50, TRUE, 'center', 1 );
		$colModel['details'] = array( 'Detalji', 50, FALSE, 'center', 0 );

        $buttons[] = array('Nova promocija', 'add', 'grid_commands', site_url("promotion_code/create"));
        $buttons[] = array('separator');
        $buttons[] = array('Obriši promociju', 'delete', 'grid_commands', site_url("promotion_code/delete"));
        $buttons[] = array('separator');
        $buttons[] = array('Izaberi sve', 'select_all', 'grid_commands', '/select');
		$buttons[] = array('separator');
        $buttons[] = array('Poništi izbor', 'deselect_all', 'grid_commands', '/desel');
        $buttons[] = array('separator');

        $data['grid'] = build_grid_js('grid', site_url("promotion_code/grid"), $colModel, 'title', 'ASC', $this->gridParams, $buttons);

        $data['grid_title'] = "Promotion Code";
        $this->_render_view( "master/grid_view", $data );
     }
     
     public function create() {
        
		$this->resources['css'][] = 'dropbox';        
		$this->resources['css'][] ='datepicker';
		$this->resources['css'][] ='numberspinner';
		$this->resources['js'][] = 'checkbox';
        $this->resources['js'][] = 'dropbox';
        $this->resources['js'][] = 'numberspinner';
		 
		$data['percent'] = 0;
		$data['a1'] = 'checked';
		$data['a2'] = '';
		$data['tip'] = 'Ostalo';
		$data['t1'] = 'selected';

		$data['brand_list'] = $this->em->getRepository('models\Entities\Product\Brand')->getAllBrands();
		$data['category_list'] = $this->em->getRepository('models\Entities\Product')->getAllCategories();
		$data['subcategory_list'] = $this->em->getRepository('models\Entities\Product')->getAllSubcategories();
		
		$brand_model = $this->em->getRepository('models\Entities\Product\Brand');
		$data['brands'] = $brand_model->getAllBrands();

        $this->_render_view( 'promotion/promotion_code/create_promotion', $data );
     }
	 
	  public function edit( $id ) {
	  	
		$this->session->set_flashdata('edit_promo_code', TRUE);
	  	
		$this->resources['css'][] = 'dropbox';        
		$this->resources['css'][] ='datepicker';
		$this->resources['css'][] ='numberspinner';
		$this->resources['js'][] = 'checkbox';
        $this->resources['js'][] = 'dropbox';
        $this->resources['js'][] = 'numberspinner';

		$coupon = $this->em->getRepository('models\Entities\Order\Coupon')->getCouponByID($id);
		$definition	= json_decode($coupon->getDefinition());
		
		$data['name'] = $coupon->getTitle();
		$data['start'] = $coupon->getPublishstart()->format('d/m/Y'); 
		$data['end'] = $coupon->getPublishend()->format('d/m/Y'); 
		$data['id'] = $coupon->getID();
		$data['discount'] = $coupon->getDiscount();
		$data['status'] = $coupon->getStatus();
		$data['type'] = ($coupon->getType() == 1) ? 'Ostalo' : 'Rođendanska čestitka';
		$data['type_id'] = $coupon->getType();
		
		if( isset($definition->products) ) { 
			$data['products'] = explode(',',rtrim($definition->products,','));
		} else {
			$data['products'] = array();	
		}
		
		if( isset($definition->brands) ) { 
			$data['brands'] = explode(',',rtrim($definition->brands,','));
		} else {
			$data['brands'] = array();	
		}
		
		if( isset($definition->categories) ) { 
			$data['categories'] = explode(',',rtrim($definition->categories,','));
		} else {
			$data['categories'] = array();	
		}
		
		if( isset($definition->subcategories) ) { 
			$data['subcategories'] = explode(',',rtrim($definition->subcategories,','));
		} else {
			$data['subcategories'] = array();	
		}
		
		$data['brand_list'] = $this->em->getRepository('models\Entities\Product\Brand')->getAllBrands();
		$data['category_list'] = $this->em->getRepository('models\Entities\Product')->getAllCategories();
		$data['subcategory_list'] = $this->em->getRepository('models\Entities\Product')->getAllSubcategories();
		
        $this->_render_view( 'promotion/promotion_code/edit_promotion', $data );
     }

     public function delete() {
        
        $id_list = explode( ',', $this->input->post('items') );       
        $this->em->getRepository('models\Entities\Order\Coupon')->deletePromotions($id_list);		
		$this->output->set_output( TRUE );
     }

	private function generateRandomString($length = 10) {
		
	    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, strlen($characters) - 1)];
	    }
	    return $randomString;
	}
	 
	 public function submit() {
	 	
	 	$this->resources['css'][] = 'dropbox';        
		$this->resources['css'][] ='datepicker';
		$this->resources['css'][] ='numberspinner';
		$this->resources['js'][] = 'checkbox';
        $this->resources['js'][] = 'dropbox';
        $this->resources['js'][] = 'numberspinner';

	 	$success 		= 'true';
		$error   		= 'false';
		
		$id 			= $this->input->post('productid');
		$title 			= $this->input->post('title');
		$type 			= $this->input->post('type');
		$publish_start 	= $this->input->post('start_date');
		$publish_end 	= $this->input->post('end_date');
		$discount 		= $this->input->post('discount');
		$brands 		= $this->input->post('brand');
		$categories 	= $this->input->post('category');
		$subcategories 	= $this->input->post('subcategory');
		$status 		= $this->input->post('status');
		$products 		= $this->input->post('product');
		
		if( $id ) {
			$data['coupon'] = $this->em->getRepository('models\Entities\Order\Coupon')->getCouponByID($id); 
		} else {
			$data['coupon'] = new models\Entities\Order\Coupon();
			$data['coupon']->setCode($this->generateRandomString(6));
		}
		
		$definition_elements = array();
		
		if(!empty($brands)) {
			$brands = implode(',',$brands);
			$definition_elements[] = '"brands":"'.$brands.'"';
		} 
		if(!empty($categories)) {
			$categories = implode(',',$categories);
			$definition_elements[] = '"categories":"'.$categories.'"';
		}
		if(!empty($subcategories)) {
			$subcategories = implode(',',$subcategories);
			$definition_elements[] = '"subcategories":"'.$subcategories.'"';
		}
		if(!empty($products)) {
			$products = implode(',',$products);
			$definition_elements[] = '"products":"'.$products.'"';
		}	
		$elements_string = implode(',',$definition_elements);
			
		$definition = '{'.$elements_string.'}';  
		
		$data['coupon']->setTitle($title);
		$data['coupon']->setType($type);
		$data['coupon']->setPublishstart((new \DateTime(date( 'Y-m-d', strtotime( $publish_start )))));
		$data['coupon']->setPublishend((new \DateTime(date( 'Y-m-d', strtotime( $publish_end )))));
		$data['coupon']->setDiscount($discount);
		$data['coupon']->setStatus($status);
		$data['coupon']->setDefinition($definition);

		$this->em->persist($data['coupon']);
		$this->em->flush();

		$data['brand_list']			= $this->em->getRepository('models\Entities\Product\Brand')->getAllBrands();
		$data['category_list'] 		= $this->em->getRepository('models\Entities\Product')->getAllCategories();
		$data['subcategory_list'] 	= $this->em->getRepository('models\Entities\Product')->getAllSubcategories();
		
		if($data['coupon']->getID()) {
			$data['message'] = '<p class="message_success">Sve izmene su uspešno izvršene!</p>';
		}
		else {
			$data['message'] = '<p class="message_error">Došlo je do greške! Molimo Vas proverite unete parametre.</p>';
		}
		
		if( $this->session->flashdata('edit_promo_code') ) {
			redirect( 'promotion_code/edit/'.$id );
		} else {
			$this->_render_view( 'promotion/promotion_code/create_promotion', $data );
		}
	 }

     public function grid() {
          
         $valid_fields = array('title', 'status');
         
         $this->flexigrid->validate_post($this->gridParams['id'], 'title', 'ASC', $valid_fields);
         $criteria = $this->flexigrid->get_criteria();
         $records = $this->em->getRepository('models\Entities\Order\Coupon')->getPromotions( $criteria );
         
         $this->session->unset_userdata('edit_visited');
         $this->output->set_header($this->config->item('json_header'));
         $this->output->set_output($this->flexigrid->json_build($records['record_count'],$records['record_items']));
     }
     
     public function change_status( $id ){
         
          $promotion = $this->em->getRepository('models\Entities\Order\Coupon')->find($id);
          $promotion->getStatus() ? $promotion->setStatus(0) : $promotion->setStatus(1); 
          
          $this->em->flush();

          $this->output->set_output($promotion->getStatus());
     }
     
 }
 
 /* End of file promotion_code.php */
 /* Location: ./system/applications/_backend/controllers/promotion_code.php */
