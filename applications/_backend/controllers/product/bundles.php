<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * ...
 * @author Ivan Despic [ Codeion ]
 */

 use models\Entities\Product;
 use models\Entities\Product\Bundle;
  
 class Bundles extends MY_Controller {
     
      public $bundlesGridParams = array(
        'id'                    => 'productsGrid',
        'width'                 => 700,
        'height'                => 400,
        'rp'                    => 15,
        'rpOptions'             => '[10,15,20,25,40]',
        'pagestat'              => 'Prikaz: {from} do {to} Ukupno: {total} paketa.',
        'blockOpacity'          => 0.5,
        'pagetext'              => 'Stranica', 
        'outof'                 => 'od', 
        'showTableToggleBtn'    => true
     );
     
     public $productBundlesGridParams = array(
        'id'                          => 'productsGrid',
        'width'                       => 700,
        'height'                      => 400,
        'rp'                          => 15,
        'rpOptions'                   => '[10,15,20,25,40]',
        'pagestat'                    => 'Prikaz: {from} do {to} Ukupno: {total} paketa.',
        'blockOpacity'                => 0.5,
        'pagetext'                    => 'Stranica', 
        'outof'                       => 'od', 
        'showTableToggleBtn'          => true
     );

     public function __construct() {
         
        parent::__construct();
         
        $this->load->helper('flexigrid');
        $this->load->library('Flexigrid');
        $this->load->helper('tinymce');
         
        $this->resources['css'] = array();
        $this->resources['js'] = array();
     }

    public function listing( $product_id ) {
            
        $this->resources['css'][] = 'flexigrid';
        $this->resources['js'][] = 'flexigrid';

        $this->bundlesGridParams['title'] = 'Pregled svih paketa';
        
        $colModel['name']  = array( 'Naziv', 400, TRUE, 'center', 1 ); 
        $colModel['price']  = array( 'Fiksna Cena', 120, TRUE, 'center', 1 );
        $colModel['actions']   = array( 'Detalji', 80, FALSE, 'center', 0 ); 
        
        $buttons[] = array('Dodaj paket', 'add', 'grid_commands', site_url("product/bundles/add/".$product_id));
        $buttons[] = array('separator');
        $buttons[] = array('Izaberi sve', 'select_all', 'grid_commands', '/select');
        $buttons[] = array('separator');
        $buttons[] = array('Poništi izbor', 'deselect_all', 'grid_commands', '/desel');
        $buttons[] = array('separator');

        $data['bundles_grid'] = build_grid_js('bundles_grid', site_url("product/bundles/bundles_grid"), $colModel, 'id', 'DESC', $this->bundlesGridParams, $buttons);
        
        $this->productBundlesGridParams['title'] = 'Proizvod paketi';
        
        $productBundleColModel['name']  = array( 'Naziv', 400, TRUE, 'center', 1 ); 
        $productBundleColModel['price']  = array( 'Cena sa proizvodom', 120, TRUE, 'center', 1 );
        $productBundleColModel['actions']   = array( 'Detalji', 80, FALSE, 'center', 0 ); 
        
        $productButtons[] = array('Obriši paket', 'delete', 'grid_commands', site_url("product/bundles/delete/".$product_id));
        $productButtons[] = array('separator');
        $productButtons[] = array('Izaberi sve', 'select_all', 'grid_commands', '/select');
        $productButtons[] = array('separator');
        $productButtons[] = array('Poništi izbor', 'deselect_all', 'grid_commands', '/desel');
        $productButtons[] = array('separator');

        if( $this->input->post('page') ) $this->bundlesGridParams['newp'] = $this->input->post('page');
        if( $this->input->post('page') ) $this->productBundlesGridParams['newp'] = $this->input->post('page');
        
        $data['product_bundles_grid'] = build_grid_js('grid', site_url("product/bundles/product_bundles_grid/".$product_id), $productBundleColModel, 'id', 'DESC', $this->productBundlesGridParams, $productButtons);
        $data['product'] = $this->em->getRepository('models\Entities\Product')->find($product_id);
        
        $data['grid_title'] = "Paket ponude";
        $this->_render_view( "product/bundles_view", $data );
     }
     
     public function bundles_grid() {
          
        $valid_fields = array('id','name','price');
         
        $this->flexigrid->validate_post($this->bundlesGridParams['id'], 'id', 'DESC', $valid_fields);
        $criteria = $this->flexigrid->get_criteria();
        $records = $this->em->getRepository('models\Entities\Product\Bundle')->getBundles( $criteria );
         
        $this->session->unset_userdata('edit_visited');
        $this->output->set_header($this->config->item('json_header'));
        $this->output->set_output($this->flexigrid->json_build($records['record_count'],$records['record_items']));
     }
     
     public function product_bundles_grid( $product_id ) {
          
        $valid_fields = array('id','name','price');
         
        $this->flexigrid->validate_post($this->productBundlesGridParams['id'], 'id', 'DESC', $valid_fields);
        $criteria = $this->flexigrid->get_criteria();
        $records = $this->em->getRepository('models\Entities\Product\Bundle')->getProductBundles( $criteria, $product_id );
         
        $this->session->unset_userdata('edit_visited');
        $this->output->set_header($this->config->item('json_header'));
        $this->output->set_output($this->flexigrid->json_build($records['record_count'],$records['record_items']));
     }

     public function add( $product_id ) {
         
        $product = $this->em->getRepository('models\Entities\Product')->find($product_id); 
         
        $id_list = explode( ',', $this->input->post('items') );
        
        foreach( $id_list as $id ) {  
            $product->setBundle( $this->em->getReference('models\Entities\Product\Bundle', $id) );
            $this->em->flush();     
        }
        $this->output->set_output( TRUE );    
     }
     
     public function delete( $product_id ) {
        
         $id_list = explode( ',', $this->input->post('items') );
         
         $this->em->getRepository('models\Entities\Product')->deleteProductBundles($id_list, $product_id); 

         $this->output->set_output( TRUE );    
     }
}

/* End of file bundles.php */
 /* Location: ./system/applications/_backend/controllers/product/bundles.php */