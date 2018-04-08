<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * ...
 * @author Marko Stepanovic [ Codeion ]
 */

 use models\Entities\Promotion\Page;
 
 class Promotions extends MY_Controller {
     
     public $gridParams = array(
        'id'                   => 'productsGrid',
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

        $colModel['title']  = array( 'Naslov', 261, TRUE, 'center', 1 );
        $colModel['status'] = array( 'Status', 50, TRUE, 'center', 1 );
        $colModel['actions'] = array( 'Detalji', 50, FALSE, 'center', 0 );

        $buttons[] = array('Nova promocija', 'add', 'grid_commands', site_url("promotions/create"));
        $buttons[] = array('separator');
        $buttons[] = array('Obriši promociju', 'delete', 'grid_commands', site_url("promotions/delete"));
        $buttons[] = array('separator');
        $buttons[] = array('Izaberi sve', 'select_all', 'grid_commands', '/select');
		$buttons[] = array('separator');
        $buttons[] = array('Poništi izbor', 'deselect_all', 'grid_commands', '/desel');
        $buttons[] = array('separator');
        
        if( $this->input->post('page') ) $this->gridParams['newp'] = $this->input->post('page');
        $data['grid'] = build_grid_js('grid', site_url("promotions/grid"), $colModel, 'title', 'ASC', $this->gridParams, $buttons);

        $data['grid_title'] = "Promocije";
        $this->_render_view( "master/grid_view", $data );
     }
     
     public function create() {
        
        $this->resources['js'][] = 'checkbox';
        
        $this->_render_view( 'promotion/create_promotion' );
     }
     
     public function details( $id ) {

        if( $data['promotion'] = $this->em->getRepository('models\Entities\Promotion\Page')->find($id) ) {
            
            $this->resources['css'][] = 'plupload';
            
            $this->resources['js'][] = 'checkbox';
            $this->resources['js'][] = 'tiny_mce';
            $this->resources['js'][] = 'plupload_full';
            $this->resources['js'][] = 'plupload_queue';
            
            try {
                 $data['product_ids'] = array();   
                 $product_list = $data['promotion']->getProducts();
                 foreach( $product_list as $product ) {
                    $data['product_ids'][] = $product->getID();
                 }
            } catch( \Doctrine\ORM\EntityNotFoundException $e ) {
                 $data['product_ids'] = array();
              }        
            
            $data['plupload'] = build_plupload_js( site_url('upload/promotions/'.$data['promotion']->getID()) );
            $data['tinymce'] = build_tinymce_js('page', 950, 700, site_url('proxy/get_promotions_images/'.$data['promotion']->getID()));
            $data['vendors'] = $this->em->getRepository('models\Entities\Vendor')->findAll();
            
            $this->_render_view( 'promotion/edit_promotion', $data );
        }
        else show_404();
     }
     
     public function save() {
         
        $this->resources['js'][] = 'checkbox';

        // TODO server validation
        
        $data['promotion'] = new Page();

        $data['promotion']->setTitle( $this->input->post('title') );
        $data['promotion']->setStatus( $this->input->post('status') );
    
        $this->em->persist($data['promotion']);
        $this->em->flush();
        
        $this->resources['css'][] = 'plupload';
        
        $this->resources['js'][] = 'tiny_mce';
        $this->resources['js'][] = 'plupload_full';
        $this->resources['js'][] = 'plupload_queue';
        
        $data['plupload'] = build_plupload_js( site_url('upload/promotions/'.$data['promotion']->getID()) );
        $data['tinymce'] = build_tinymce_js('page', 950, 700, site_url('proxy/get_promotions_images/'.$data['promotion']->getID()));
        
        $this->_render_view( 'promotion/edit_promotion', $data );
     }
     
     public function edit( $id ) {

        if( $data['promotion'] = $this->em->getRepository('models\Entities\Promotion\Page')->find($id) ) {
            
            // TODO server validation
            
            $data['promotion']->setStatus( $this->input->post('status') );
            $data['promotion']->setTitle( $this->input->post('title') );
            $data['promotion']->setContent( $this->input->post('page') );
            
			if($this->session->userdata('application_id'))
				$data['promotion']->setVendor($this->em->getReference('models\Entities\Vendor', $this->session->userdata('application_id')));
			else {
	            if ($this->input->post('vendor_id')){
	                $data['promotion']->setVendor($this->em->getReference('models\Entities\Vendor', $this->input->post('vendor_id')));
	            }else{
	                $data['promotion']->setVendor(NULL);   
	            }
            }
            $data['message'] = '<p class="message_success">Sve izmene su uspešno izvršene!</p>';
            
            $this->resources['css'][] = 'plupload';
            
            $this->resources['js'][] = 'checkbox';
            $this->resources['js'][] = 'tiny_mce';
            $this->resources['js'][] = 'plupload_full';
            $this->resources['js'][] = 'plupload_queue';
            
            $data['promotion']->getProducts()->clear();
            
            if ($this->input->post('product_id') != ''){
                if(count(array_filter(array_unique(($this->input->post('product_id'))))) == $this->em->getRepository('models\Entities\Promotion\Page')->getPromotionProducts($this->input->post('product_id'))) {
                    
                    foreach(array_filter(array_unique(($this->input->post('product_id')))) as $value) {
                        $data['promotion']->setProduct($this->em->getReference('models\Entities\Product', $value));
                        $data['message'] = '<p class="message_success">Reklama je uspesno izmenjena.</p>';
                    }
                 } else {
                     $data['message'] = '<p class="message_error">Doslo je do greske prilikom unosa ID Proizvoda, proverite ID listu.</p>';
                 }
            }
            
            $this->em->persist($data['promotion']);
            $this->em->flush();
            
            try {
                 $data['product_ids'] = array();   
                 $product_list = $data['promotion']->getProducts();
                 foreach( $product_list as $product ) {
                    $data['product_ids'][] = $product->getID();
                 }
            } catch( \Doctrine\ORM\EntityNotFoundException $e ) {
                 $data['product_ids'] = array();
            } 
            
            $data['plupload'] = build_plupload_js( site_url('upload/promotions/'.$data['promotion']->getID()) );
            $data['tinymce'] = build_tinymce_js('page', 950, 700, site_url('proxy/get_promotions_images/'.$data['promotion']->getID()));
            $data['vendors'] = $this->em->getRepository('models\Entities\Vendor')->findAll();
            
            $this->_render_view( 'promotion/edit_promotion', $data );
        }
        else show_404();
     }

     public function delete() {
        
        $id_list = explode( ',', $this->input->post('items') );
        
        $this->em->getRepository('models\Entities\Promotion\Page')->deletePromotions($id_list);
        $this->output->set_output( TRUE );
     }
     
     public function grid() {
          
         $valid_fields = array('title', 'status');
         
         $this->flexigrid->validate_post($this->gridParams['id'], 'title', 'ASC', $valid_fields);
         $criteria = $this->flexigrid->get_criteria();
         $records = $this->em->getRepository('models\Entities\Promotion\Page')->getPromotions( $criteria );
         
         $this->session->unset_userdata('edit_visited');
         $this->output->set_header($this->config->item('json_header'));
         $this->output->set_output($this->flexigrid->json_build($records['record_count'],$records['record_items']));
     }
     
     public function change_status( $id ){
         
          $promotion = $this->em->getRepository('models\Entities\Promotion\Page')->find($id);
          $promotion->getStatus() ? $promotion->setStatus(0) : $promotion->setStatus(1); 
          
          $this->em->flush();

          $this->output->set_output($promotion->getStatus());
     }
     
 }
 
 /* End of file promotions.php */
 /* Location: ./system/applications/_backend/controllers/promotions.php */
