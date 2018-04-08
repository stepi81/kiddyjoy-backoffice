<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * ...
 * @author Ivan Despic [ Codeion ]
 */
 
 use models\Entities\Product\Specification;
 
 class Specifications extends MY_Controller {
     
     public $gridParams = array(
        'id'                    => 'specificationsGrid',
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
        
        $this->resources['js'][] = 'checkbox';
     }

     public function listing( $subcategory_id, $message = NULL ) {

        $this->resources['css'][]='flexigrid';
        $this->resources['js'][]='flexigrid';

        $this->gridParams['title']='Pregled svih specifikacija';

        $colModel['position'] 				= array( 'Pozicija', 				50, 	TRUE, 	'center', 1 ); 
        $colModel['position_info'] 			= array( 'Info Pozicija', 			80, 	TRUE, 	'center', 1 );      
		$colModel['position_klirit']   		= array( 'Klirit Pozicija', 		80, 	TRUE, 	'center', 1 );
        $colModel['name'] 					= array( 'Ime', 					200, 	TRUE, 	'center', 1 );  
        $colModel['status'] 				= array( 'Status', 					50, 	TRUE, 	'center', 1 );
		$colModel['visibility'] 			= array( 'Vidljivost - Detalji', 	80, 	TRUE, 	'center', 1 );
		$colModel['filter_visibility'] 		= array( 'Vidljivost - Pretraga', 	90, 	TRUE, 	'center', 1 );
		$colModel['package_visibility'] 	= array( 'Vidljivost - Paketi', 	90, 	TRUE, 	'center', 1 );
		$colModel['tech_icon_visibility'] 	= array( 'Ikonica u kliritu', 		90, 	TRUE, 	'center', 1 );
        $colModel['filters'] 				= array( 'Filteri', 				50, 	FALSE, 	'center', 0 );
        $colModel['actions'] 				= array( 'Detalji', 				50, 	FALSE, 	'center', 0 );

        $buttons[] = array('Obriši specifikaciju', 'delete', 'grid_commands', site_url("product/specifications/spec_del"));
        $buttons[] = array('separator');
        $buttons[] = array('Izaberi sve', 'select_all', 'grid_commands', '/select');
		$buttons[] = array('separator');
        $buttons[] = array('Poništi izbor', 'deselect_all', 'grid_commands', '/desel');
        $buttons[] = array('separator');

        if( $this->input->post('page') ) $this->gridParams['newp'] = $this->input->post('page');

        $data['grid'] = build_grid_js('grid', site_url("product/specifications/grid/" . $subcategory_id), $colModel, 'position', 'ASC', $this->gridParams, $buttons);
        $data['subcategory'] = $this->em->getRepository('models\Entities\Product\Subcategory')->find($subcategory_id);
        $data['grid_title'] = 'Specifikacije podkategorije::' . $data['subcategory']->getName() ;
        If (isset($message)){$data['message'] = $message;}
        $this->_render_view("product/specification/create_specification", $data);
     }
     
     public function grid( $subcategory_id ){
       
        $valid_fields=array( 'name', 'position', 'position_info', 'position_klirit' );
        $this->flexigrid->validate_post($this->gridParams['id'], 'position', 'ASC', $valid_fields);
        $criteria=$this->flexigrid->get_criteria();
        $records=$this->em->getRepository('models\Entities\Product\Specification')->getSubcategorySpecifications($criteria, $subcategory_id);

        $this->session->unset_userdata('edit_visited');
        $this->output->set_header($this->config->item('json_header'));
        $this->output->set_output($this->flexigrid->json_build($records['record_count'],$records['record_items']));
     }
     
     public function spec_del() {
     	
		$this->cache_manager->deleteCache('EcomCatalog_CatalogRepository_getCategoryMenus');
		$this->cache_manager->deleteCache('EcomCatalog_MenuRepository_getCategoryMenus');
     
	 	$repo = $this->em->getRepository('models\Entities\Product\Specification');
        $id_list = explode(',', $this->input->post('items'));
        $repo->deleteSubcategorySpecifications($id_list);
        
        //$obj = $repo->find($id_list[0]);
		//$subcat_id = $obj->getSubcategory()->getID();		
		//$specs = $repo->getSpecificationsBySubcategory( $subcat_id );
		//$repo->ensureOrdinality($specs, 'getPosition', 'setPosition');
		//$repo->ensureOrdinality($specs, 'getPositionInfo', 'setPositionInfo');
		//$repo->ensureOrdinality($specs, 'getPositionKlirit', 'setPositionKlirit');
		
		$this->output->set_output(TRUE);
    }
	 
	

     public function save( $subcategory_id ){
      
        $specification = new Specification();
         
        $specification->setName($this->input->post('name'));
        $specification->setSubcategory( $this->em->getReference('models\Entities\Product\Subcategory', $subcategory_id));
        $specification->setStatus($this->input->post('status'));
		$specification->setVisibility(0);
		$specification->setFilterVisibility(0);
		$specification->setBundleVisibility(0);
		$specification->setTechIconVisibility(0);
        $specification->setTypeID($this->input->post('type'));
		
		
		
		$specificationPosition = $this->input->post('position');			
		$kliritPosition        = $this->input->post('position_klirit');
		$infoPosition		   = $this->input->post('position_info');
		     
		$repo = $this->em->getRepository('models\Entities\Product\Specification');
		$specs = $repo->getSpecificationsBySubcategory($subcategory_id);
		$repo->enforceOrdinalProperty($specs, $specification, $specificationPosition, 	'getPosition', 			'setPosition' );
		$repo->enforceOrdinalProperty($specs, $specification, $kliritPosition,	  		'getPositionKlirit', 	'setPositionKlirit', TRUE);
		$repo->enforceOrdinalProperty($specs, $specification, $infoPosition, 			'getPositionInfo', 		'setPositionInfo', TRUE);
		
		
		
		/*$repo = $this->em->getRepository('models\Entities\Product\Specification');
		
		$specs = $repo->getSpecificationsBySubcategory($subcategory_id);
		$specification->setPosition($this->input->post('position_info'));
		$repo->enforceOrdinalProperty($specs, $specification, $this->input->post('position_info'), 		'getPositionInfo', 		'setPositionInfo', TRUE);
		$repo->enforceOrdinalProperty($specs, $specification, $this->input->post('position_klirit'), 	'getPositionKlirit', 	'setPositionKlirit', TRUE);
		$repo->enforceOrdinalProperty($specs, $specification, $this->input->post('position'), 			'getPosition', 			'setPosition');*/
		 
        $message='<p class="message_success"  style="width: 250px; padding: 8px 5px;">Nova specifikacija je dodata!</p>';
		
		$this->cache_manager->deleteCache('EcomCatalog_CatalogRepository_getCategoryMenus');
		$this->cache_manager->deleteCache('EcomCatalog_MenuRepository_getCategoryMenus');

        $this->listing( $subcategory_id, $message );
    }

    public function change_status( $id ) {
    	
		$this->cache_manager->deleteCache('EcomCatalog_CatalogRepository_getCategoryMenus');
		$this->cache_manager->deleteCache('EcomCatalog_MenuRepository_getCategoryMenus');

        $specification = $this->em->getRepository('models\Entities\Product\Specification')->find($id);
        $specification->getStatus() ? $specification->setStatus(0) : $specification->setStatus(1);

        $this->em->flush();

        $this->output->set_output($specification->getStatus());
    }
	
	public function change_visibility( $id ) {
		
		$this->cache_manager->deleteCache('EcomCatalog_CatalogRepository_getCategoryMenus');
		$this->cache_manager->deleteCache('EcomCatalog_MenuRepository_getCategoryMenus');

        $specification = $this->em->getRepository('models\Entities\Product\Specification')->find($id);
        $specification->getVisibility() ? $specification->setVisibility(0) : $specification->setVisibility(1);

        $this->em->flush();

        $this->output->set_output($specification->getVisibility());
    }
	
	public function change_filter_visibility( $id ) {
		
		$this->cache_manager->deleteCache('EcomCatalog_CatalogRepository_getCategoryMenus');
		$this->cache_manager->deleteCache('EcomCatalog_MenuRepository_getCategoryMenus');

        $specification = $this->em->getRepository('models\Entities\Product\Specification')->find($id);
        $specification->getFilterVisibility() ? $specification->setFilterVisibility(0) : $specification->setFilterVisibility(1);

        $this->em->flush();

        $this->output->set_output($specification->getFilterVisibility());
    }

	public function change_bundle_visibility( $id ) {
		
		$this->cache_manager->deleteCache('EcomCatalog_CatalogRepository_getCategoryMenus');
		$this->cache_manager->deleteCache('EcomCatalog_MenuRepository_getCategoryMenus');

        $specification = $this->em->getRepository('models\Entities\Product\Specification')->find($id);
        $specification->getBundleVisibility() ? $specification->setBundleVisibility(0) : $specification->setBundleVisibility(1);

        $this->em->flush();

        $this->output->set_output($specification->getBundleVisibility());
    }
	
	public function change_techicon_visibility( $id ) {
		
		$this->cache_manager->deleteCache('EcomCatalog_CatalogRepository_getCategoryMenus');
		$this->cache_manager->deleteCache('EcomCatalog_MenuRepository_getCategoryMenus');
		
		$specification = $this->em->getRepository('models\Entities\Product\Specification')->find($id);
        $specification->getTechIconVisibility() ? $specification->setTechIconVisibility(0) : $specification->setTechIconVisibility(1);
		$this->em->flush();
		$this->output->set_output($specification->getTechIconVisibility());
	}

    public function details($specification_id, $message=NULL) {
     
        if ($data['specification'] = $this->em->getRepository('models\Entities\Product\Specification')->find($specification_id)) {
            $data['message']=$message;   
            $this->_render_view('product/specification/edit_specification', $data);
        } else
            show_404();    
    }
    
    public function edit($specification_id) {
    	
		 $this->cache_manager->deleteCache('EcomCatalog_CatalogRepository_getCategoryMenus');
		 $this->cache_manager->deleteCache('EcomCatalog_MenuRepository_getCategoryMenus');
           
         if($data['specification'] = $this->em->getRepository('models\Entities\Product\Specification')->find($specification_id)) {
               
            $data['specification']->setName( $this->input->post('name') );

            $specificationPosition = $this->input->post('position');			
			$kliritPosition        = $this->input->post('position_klirit');
			$infoPosition		   = $this->input->post('position_info');
			     
			$repo = $this->em->getRepository('models\Entities\Product\Specification');
			$specs = $repo->getSpecificationsBySubcategory($data['specification']->getSubcategory()->getID());
			$repo->enforceOrdinalProperty($specs, $data['specification'], $specificationPosition, 	'getPosition', 			'setPosition' );
			$repo->enforceOrdinalProperty($specs, $data['specification'], $kliritPosition,	  		'getPositionKlirit', 	'setPositionKlirit', TRUE);
			$repo->enforceOrdinalProperty($specs, $data['specification'], $infoPosition, 			'getPositionInfo', 		'setPositionInfo', TRUE);
			
			$this->em->flush(); // unneeded?
          
          	$data['message'] = '<p class="message_success"  style="width: 373px; padding: 8px 5px;">Sve izmene su uspešno sačuvane!</p>';  
            $this->_render_view('product/specification/edit_specification', $data);   
      
		} else show_404(); 
   }
}
 
 /* End of file specifications.php */
 /* Location: ./system/applications/_backend/controllers/product/specifications.php */