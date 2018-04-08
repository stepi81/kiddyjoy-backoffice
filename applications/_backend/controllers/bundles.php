<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ...
 * @author Marko Stepanovic [ Codeion ]
 */
 
 use models\Entities\Product;
 use models\Entities\Product\Bundle;
 
 class Bundles extends MY_Controller {
     
     public $gridParams = array(
        'id'                   => 'productsGrid',
        'width'                 => 'auto',
        'height'                => 400,
        'rp'                    => 15,
        'rpOptions'             => '[10,15,20,25,40]',
        'pagestat'              => 'Prikaz: {from} do {to} Ukupno: {total} paketa.',
        'blockOpacity'          => 0.5,
        'pagetext'              => 'Stranica', 
        'outof'                 => 'od', 
        'showTableToggleBtn'    => true
     );
     
     public $productGridParams = array(
        'id'                    => 'productsGrid',
        'width'                 => 650,
        'height'                => 200,
        'rp'                    => 100,
        //'rpOptions'             => '[10,15,20,25,40]',
        'pagestat'              => '',
        'blockOpacity'          => 0.5,
        'pagetext'              => 'Stranica', 
        'outof'                 => 'od', 
        'showTableToggleBtn'    => true
     );
	 
	 public $productsGridParams = array(
        'id'                    => 'productsGrid',
        'width'                 => 650,
        'height'                => 400,
        'rp'                    => 50,
        'rpOptions'             => '[25,50,75,100]',
        'pagestat'              => 'Prikaz: {from} do {to} Ukupno: {total} proizvoda.',
        'blockOpacity'          => 0.5,
        'pagetext'              => 'Stranica', 
        'outof'                 => 'od', 
        'showTableToggleBtn'    => true
     );
     
     public function __construct() {
         
         parent::__construct();
         
         $this->load->helper('flexigrid');
         $this->load->library('Flexigrid');
         
         $this->resources['css'] = array();
         $this->resources['js'] = array();
     }
     
     public function listing() {
            
        $this->resources['css'][] = 'flexigrid';
        $this->resources['js'][] = 'flexigrid';

        $this->gridParams['title'] = 'Pregled svih paketa';
        
        $colModel['name']  = array( 'Naziv', 450, TRUE, 'center', 1 ); 
        $colModel['price']  = array( 'Fiksna Cena', 120, TRUE, 'center', 1 );
        $colModel['actions']   = array( 'Detalji', 80, FALSE, 'center', 0 ); 
		
        $buttons[] = array('Novi paket', 'add', 'grid_commands', site_url("bundles/create"));
        $buttons[] = array('separator');
        $buttons[] = array('Obriši paket', 'delete', 'grid_commands', site_url("bundles/delete"));
        $buttons[] = array('separator');
        $buttons[] = array('Izaberi sve', 'select_all', 'grid_commands', '/select');
        $buttons[] = array('Poništi izbor', 'deselect_all', 'grid_commands', '/desel');
        $buttons[] = array('separator');

        if( $this->input->post('page') ) $this->gridParams['newp'] = $this->input->post('page');
        $data['grid'] = build_grid_js('grid', site_url("bundles/grid"), $colModel, 'id', 'DESC', $this->gridParams, $buttons);
        
        $data['grid_title'] = "Paket ponude";
        $this->_render_view( "master/grid_view", $data );
     }
     
     public function grid() {
          
         $valid_fields = array('id','name','price');
         
         $this->flexigrid->validate_post($this->gridParams['id'], 'id', 'DESC', $valid_fields);
         $criteria = $this->flexigrid->get_criteria();
         $records = $this->em->getRepository('models\Entities\Product\Bundle')->getBundles( $criteria );
         
         $this->session->unset_userdata('edit_visited');
         $this->output->set_header($this->config->item('json_header'));
         $this->output->set_output($this->flexigrid->json_build($records['record_count'],$records['record_items']));
     }
     
     public function create() {
        
        $this->_render_view( 'bundle/new_bundle' );
     }
     
     public function details( $id, $message = null ) {

        if( $data['bundle'] = $this->em->getRepository('models\Entities\Product\Bundle')->find($id) ) {
            
            $this->resources['css'][] = 'flexigrid';
            $this->resources['js'][] = 'flexigrid';

            $this->gridParams['title'] = 'Pregled svih paketa';
            
            $colModel['name']  = array( 'Naziv', 340, TRUE, 'center', 1 );
            $colModel['price']  = array( 'Cena', 100, FALSE, 'center', 0 );
            $colModel['discount']  = array( 'Popust', 60, FALSE, 'center', 0 ); 
            $colModel['discount_price']  = array( 'Cena sa popustom', 100, FALSE, 'center', 0 ); 
            $buttons[] = array('Obriši', 'delete', 'grid_commands', site_url("bundles/delete/".$id));
            $buttons[] = array('separator');
            $buttons[] = array('Izaberi sve', 'select_all', 'grid_commands', '/select');
            $buttons[] = array('Poništi izbor', 'deselect_all', 'grid_commands', '/desel');
            $buttons[] = array('separator');

            $data['grid'] = build_grid_js('grid', site_url("bundles/products_grid/".$data['bundle']->getID()), $colModel, 'id', 'ASC', $this->productGridParams, $buttons);

            $data['grid_title'] = "Proizvodi"; 
            $data['message'] = $message;
            
            $this->_render_view( 'bundle/edit_bundle', $data ); 
        }
        else show_404();
     }
     
     public function products_grid( $bundle_id ) {
          
         $valid_fields = array('name');
         
         $this->flexigrid->validate_post($this->productGridParams['id'], 'name', 'ASC', $valid_fields);
         $criteria = $this->flexigrid->get_criteria();
      
         $records = $this->em->getRepository('models\Entities\Product\Bundle')->getBundleItems( $criteria, $bundle_id );
         
         $this->session->unset_userdata('edit_visited');
         $this->output->set_header($this->config->item('json_header'));
         $this->output->set_output($this->flexigrid->json_build($records['record_count'],$records['record_items']));
     }
     
     public function save() {
        
         $data['bundle'] = new Bundle();
            
         $data['bundle']->setName( $this->input->post('bundle_name') );
         $data['bundle']->setPrice( $this->input->post('bundle_price') );
    
         $this->em->persist($data['bundle']);
         $this->em->flush();
         
         // ACTIVITY MONITORING
         $evtArgs = new \Doctrine\Common\EventArgs();
         $evtArgs->type = ACTIVITY_BUNDLE;
         $evtArgs->operation = ACTIVITY_OPERATION_BUNDLE;
         $evtArgs->process = ACTIVITY_PROCESS_CREATE;
         $evtArgs->record = $data['bundle'];
         $this->evm->dispatchEvent(ACTIVITY_EVENT, $evtArgs);

         $this->details( $data['bundle']->getID() );
     }
     
     public function edit( $id ) {
        
         $data['bundle'] = $this->em->getRepository('models\Entities\Product\Bundle')->find($id);
            
         $data['bundle']->setName( $this->input->post('bundle_name') );
         $data['bundle']->setPrice( $this->input->post('bundle_price') );
    
         $this->em->persist($data['bundle']);
         $this->em->flush();

         $message = '<p class="message_success">Paket je uspešno editovan!</p>'; 
         $this->details( $id, $message );
     }
     
     public function delete( $id = null ) {
        
         $id_list = explode( ',', $this->input->post('items') );

         if( $id ) {
            $this->em->getRepository('models\Entities\Product\Bundle')->deleteBundleProducts($id_list, $id);    
         } else {
            $this->em->getRepository('models\Entities\Product\Bundle')->deleteBundles($id_list);     
         }
          
         $this->output->set_output( TRUE );   
     }
     
     public function insert_product( $id ) {
     
         $bundle = $this->em->getRepository('models\Entities\Product\Bundle')->find($id);
         $product_id = $this->input->post('product_id');
         
         if( $bundle->productExists($product_id) ) $message = '<p class="message_error">Izabrali ste proizvod koji se nalazi u paketu!</p>';
         else {
         	
			if( $product = $this->em->getRepository('models\Entities\Product')->checkBundleProduct($product_id)  ) {
         	 	
	          	$item = new models\Entities\Product\Bundle\BundleItem();
	            $item->setProduct($product);
	            $item->setPrice($this->input->post('fix_price'));
	            $item->setDiscount($this->input->post('discount'));
	            
	            $bundle->setItem($item);
	            
				$this->em->persist($item);
				$this->em->flush();
				
				$message = '<p class="message_success">Uspešno ste dodali paket proizvod!</p>';
			}
			else $message = '<p class="message_error">Greška! Nepostojeći ili neaktivan proizvod!</p>';
		}  
         
		$this->details( $id, $message );   
	}

	public function bundle_settings() {
		
		$this->resources['css'][] = 'flexigrid';
		$this->resources['css'][] = 'multiselect_dropdown';
        $this->resources['js'][] = 'flexigrid';
		$this->resources['js'][] = 'multiselect_dropdown';

        $this->gridParams['title'] = 'Pregled svih proizvoda sa paketima';
        
        $colModel['name']  = array( 'Naziv', 340, TRUE, 'center', 1 );
		$colModel['subcategory'] = array( 'Kategorija', 160, TRUE, 'center', 1 );
        $colModel['bundles'] = array( 'Paketi', 50, FALSE, 'center', 0 );
		$colModel['details'] = array('Detalji', 50, FALSE, 'center', 0);

        $data['grid'] = build_grid_js('grid', site_url("bundles/bundle_settings_grid/"), $colModel, 'id', 'DESC', $this->productsGridParams);

        $data['grid_title'] = "Proizvodi sa paketima"; 
		
		$data['categories'] = $this->em->getRepository('models\Entities\Product\Category')->findAll();
        $data['brands'] = $this->em->getRepository('models\Entities\Product\Brand')->findAll();
		$data['bundles'] = $this->em->getRepository('models\Entities\Product\Bundle')->findAll();
		
		$data['message'] = '';
		
		$this->_render_view( 'bundle/bundle_settings', $data ); 
	}

	public function bundle_settings_grid() {
          
         $valid_fields = array('name', 'subcategory');
         
         $this->flexigrid->validate_post($this->productGridParams['id'], 'name', 'DESC', $valid_fields);
         $criteria = $this->flexigrid->get_criteria();
      
         $records = $this->em->getRepository('models\Entities\Product\Bundle')->getProductsWithBundle( $criteria );
         
         $this->session->unset_userdata('edit_visited');
         $this->output->set_header($this->config->item('json_header'));
         $this->output->set_output($this->flexigrid->json_build($records['record_count'],$records['record_items']));
     }
	
	 public function get_specifications() {
	 	
		$subcategory_id = $this->input->post('subcategory_id');
	 
	 	$subcategory = $this->em->getRepository('models\Entities\Product\Subcategory')->find($subcategory_id);
	 
	 	$specifications = array();	
	 
	 	if ($subcategory && count( $subcategory->getSpecifications() )) {
	 		foreach( $subcategory->getSpecifications() as $specification ) {
	 			if( $specification->getBundleVisibility() ) {
	 				$specifications[] = $specification;	
				}
	 		}	
	 	}
		
		$html = '<li>';
		foreach( $specifications as $specification ) {

			if($specification->getTypeID() == 2) continue;
			$html .= '<li>';
	            $html .= '<label class="alignLeft">'.$specification->getName().'</label>';
	            $html .= '<div class="customComboHolder">';
					$html .= '<input type="hidden" name="specifications[]" value="" />';
	                $html .= '<div id ="multiSelect" class="multiSelect">Odaberi</div>';
	                $html .= '<div id="multiSelection" class="multiSelection">';
	                    foreach( $specification->getFilters() as $filter ) {
	                    	$html .= '<label><input type="checkbox" value="'. $filter->getID() .'" name="ad_filters[]" />'. $filter->getName() .'</label>';
	                    }
	                $html .= '</div>';
	            $html .= '</div>';
	        $html .= '</li>';
		}
		$html .= '</li>';
		
		$html .= '<script>multiSelect(false);</script>';
		
		$this->output->set_output($html);
	 }

	public function set_bundle_on_products() {
		
		if( $this->input->post('filter_subcategory') || $this->input->post('filter_group') ) {
			$this->input->post('filter_subcategory') ? $subcategory_id = $this->input->post('filter_subcategory') : $subcategory_id = $this->input->post('filter_group');
			$subcategory = $this->em->getRepository('models\Entities\Product\Subcategory')->find($subcategory_id);
		} else {
			$subcategory = null;	
		}
		
		$this->input->post('product_brands') ? $brands = $this->input->post('product_brands') : $brands = null;
		
		$bundle_id = $this->input->post('filter_bundle');

		$this->input->post( 'specifications' ) ? $specifications = $this->input->post( 'specifications' ) : $specifications = null; 
		
		$sp = array();
		$ss = array();
		foreach($specifications as $s)
		{
			$s = explode(',',$s);
			if( count( $s ) ) {
				foreach($s as $a)
				{
					if($a){
					array_push($ss,$a);
					}
				}
				
				if(count($ss))
				array_push($sp,$ss);
				
				unset($ss);
				$ss = array();
			}
		}
		$specifications = $sp;
		
		$products = $this->em->getRepository('models\Entities\Product')->searchProducts( $brands, $subcategory, $specifications );

		if( count( $products ) ) {
			foreach( $products as $product ) {
				$product->setBundle( $this->em->getReference('models\Entities\Product\Bundle', $bundle_id) );	
				$this->em->persist($product);
			}
			
			try {
				$this->em->flush();
				
				foreach( $products as $product ) {
					// ACTIVITY MONITORING
					$evtArgs = new \Doctrine\Common\EventArgs();
					$evtArgs->type = ACTIVITY_PRODUCT;
					$evtArgs->operation = ACTIVITY_OPERATION_BUNDLE;
					$evtArgs->process = ACTIVITY_PROCESS_GROUP_LINKAGE;
					$evtArgs->record = $product;
					$this->evm->dispatchEvent(ACTIVITY_EVENT, $evtArgs);
				}
			}
			catch( Exception $e ) {
				// TODO
			}
			
			$product_numbers = count( $products );
			
			$data['message'] =  "<p class='message_success' style='width:400px !important'>Paket je uspešno vezan za $product_numbers proizvoda!</p>";
		} else {
			$data['message'] = "<p class='message_error' style='width:400px !important'>Ne postoje proizvodi koji ispunjavaju uslove pretrage.</p>";
		}
		
		$this->resources['css'][] = 'flexigrid';
		$this->resources['css'][] = 'multiselect_dropdown';
        $this->resources['js'][] = 'flexigrid';
		$this->resources['js'][] = 'multiselect_dropdown';

        $this->gridParams['title'] = 'Pregled svih proizvoda sa paketima';
        
        $colModel['name']  = array( 'Naziv', 340, TRUE, 'center', 1 );
		$colModel['subcategory'] = array( 'Kategorija', 160, TRUE, 'center', 1 );
        $colModel['bundles'] = array( 'Paketi', 50, FALSE, 'center', 0 );
		$colModel['details'] = array('Detalji', 50, FALSE, 'center', 0);

        $data['grid'] = build_grid_js('grid', site_url("bundles/bundle_settings_grid/"), $colModel, 'id', 'DESC', $this->productsGridParams);

        $data['grid_title'] = "Proizvodi sa paketima"; 
		
		$data['categories'] = $this->em->getRepository('models\Entities\Product\Category')->findAll();
        $data['brands'] = $this->em->getRepository('models\Entities\Product\Brand')->findAll();
		$data['bundles'] = $this->em->getRepository('models\Entities\Product\Bundle')->findAll();
		
		$this->_render_view( 'bundle/bundle_settings', $data ); 
	}
}
 
 /* End of file bundles.php */
 /* Location: ./system/applications/_backend/controllers/bundles.php */