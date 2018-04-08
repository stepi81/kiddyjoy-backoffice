<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * ...
 * @author Aleksandar Milas [ Codeion ]
 */

 use models\Entities\Product;
 use models\Entities\Images\ProductImage;
 use models\Entities\Product\Master;
 use models\Entities\Product\TextFilter;
  
 class Vendor_Highlights extends MY_Controller {

	public function menu_products( $cat_id, $subcat_id = NULL, $back = NULL ) {
		
		if(!$this->session->userdata('application_id')) show_404();
		
		if($data['category'] = $this->em->getRepository('models\Entities\Product\Category')->find($cat_id))	{
            	
            try {
                 $data['product_ids'] = array();   
				 
                 //$product_list = $data['category']->getVendorHighlights();
				 $product_list = $this->em->getRepository('models\Entities\Vendor\Video')->getMenuCategoryHighlights($cat_id);
                 foreach( $product_list as $product ) {
                    $data['product_ids'][] = $product->getID();
                 }
            }
            catch( \Doctrine\ORM\EntityNotFoundException $e ) {
                 $data['product_ids'] = array();
            }        
            
            $data['cat_id'] = $cat_id;
			
            $this->_render_view( 'vendor/edit_vendor_menu_highlights', $data );
        }
        else show_404();
     }
     
    public function edit_menu( $cat_id ) {
    	
		if(!$this->session->userdata('application_id')) show_404();
		
        if( $data['category'] = $this->em->getRepository('models\Entities\Product\Category')->find($cat_id) ) {

			$product_list = $this->em->getRepository('models\Entities\Vendor\Video')->getMenuCategoryHighlights($cat_id);
			
            if($product_list) {
				foreach ($product_list as $value){
					$product_list->removeElement( $this->em->getReference('models\Entities\Product', $value->getID()) );
				}
			}
			
            if ($this->input->post('product_id') != ''){
                if(count(array_filter(array_unique(($this->input->post('product_id'))))) == $this->em->getRepository('models\Entities\Promotion\Page')->getPromotionProducts($this->input->post('product_id'))) {
                    
                    foreach(array_filter(array_unique(($this->input->post('product_id')))) as $value) {
                        $data['category']->setVendorHighlight($this->em->getReference('models\Entities\Product', $value));
                        $data['message'] = '<p class="message_success">Sve izmene su uspešno izvršene!</p>';
                    }
                 } else {
                     $data['message'] = '<p class="message_error">Doslo je do greske prilikom unosa ID Proizvoda, proverite ID listu.</p>';
                 }
            }
            
            $this->em->persist($data['category']);
            $this->em->flush();
            
            try {
                 $data['product_ids'] = array();   
				 $product_list = $this->em->getRepository('models\Entities\Vendor\Video')->getMenuCategoryHighlights($cat_id);
                 foreach( $product_list as $product ) {
                    $data['product_ids'][] = $product->getID();
                 }
            } catch( \Doctrine\ORM\EntityNotFoundException $e ) {
                 $data['product_ids'] = array();
            } 

            $data['cat_id'] = $cat_id;
			
            $this->_render_view( 'vendor/edit_vendor_menu_highlights', $data );
        }
        else show_404();
     }
	 
 }
 
 /* End of file vendor_highlights.php */
 /* Location: ./system/applications/_backend/controllers/vendor_highlights.php */