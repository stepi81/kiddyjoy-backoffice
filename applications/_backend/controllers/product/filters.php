<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * ...
 * @author Ivan Despic [ Codeion ]
 */
 
 use models\Entities\Product\Filter;
 
 class Filters extends MY_Controller {
     
     public $gridParams = array(
        'id'                    => 'filtersGrid',
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

     public function listing( $specification_id, $message = NULL ) {

        $this->resources['css'][] = 'flexigrid';
        $this->resources['js'][] = 'flexigrid';

        $this->gridParams['title']='Pregled svih filtera';

        $colModel['position'] = array( 'Pozicija', 50, TRUE, 'center', 1 );
		$colModel['id'] = array( 'ID', 50, TRUE, 'center', 1 );
        $colModel['name'] = array( 'Ime', 200, TRUE, 'center', 1 );  
		$colModel['status'] = array('Status', 50, FALSE, 'center', 0); 
        $colModel['actions'] = array('Detalji', 50, FALSE, 'center', 0);

        $buttons[] = array('Obriši filter', 'delete', 'grid_commands', site_url("product/filters/filt_del"));
        $buttons[] = array('separator');
        $buttons[] = array('Izaberi sve', 'select_all', 'grid_commands', '/select');
		$buttons[] = array('separator');
        $buttons[] = array('Poništi izbor', 'deselect_all', 'grid_commands', '/desel');
        $buttons[] = array('separator');

        if( $this->input->post('page') ) $this->gridParams['newp'] = $this->input->post('page');

        $data['grid'] = build_grid_js('grid', site_url("product/filters/grid/" . $specification_id), $colModel, 'position', 'ASC', $this->gridParams, $buttons);
        $data['specification'] = $this->em->getRepository('models\Entities\Product\Specification')->find($specification_id);
        $data['technologies'] = $this->em->getRepository('models\Entities\Technology')->findAll();
        $data['grid_title'] = 'Filteri specifikacije::' . $data['specification']->getName() ;
        If (isset($message)){$data['message'] = $message;}
        $this->_render_view("product/filter/create_filter", $data);
     }
     
     public function grid( $specification_id ){
       
         $valid_fields = array( 'name', 'technology', 'position', 'id' );
         $this->flexigrid->validate_post($this->gridParams['id'], 'position', 'ASC', $valid_fields);
         $criteria = $this->flexigrid->get_criteria();
         $records = $this->em->getRepository('models\Entities\Product\Filter')->getSpecificationFilters($criteria, $specification_id);

         $this->session->unset_userdata('edit_visited');
         $this->output->set_header($this->config->item('json_header'));
         $this->output->set_output($this->flexigrid->json_build($records['record_count'],$records['record_items']));
     }
     
     public function filt_del() {
     	
		$this->cache_manager->deleteCache('EcomCatalog_CatalogRepository_getCategoryMenus');
		$this->cache_manager->deleteCache('EcomCatalog_MenuRepository_getCategoryMenus');
     
        $id_list = explode(',', $this->input->post('items'));

        foreach ($id_list as $id) {
            $ad = $this->em->getRepository('models\Entities\Product\Filter')->find($id);
            
            $ads = $this->em->getRepository('models\Entities\Product\Filter')->getFiltersBySpecification($ad->getSpecification()->getID());
            foreach ($ads as $ad_data) {
                $oldPosition = $ad_data->getPosition();
                if ($ad->getPosition() < $ad_data->getPosition()) {
                    $ad_data->setPosition($oldPosition - 1);
                    $this->em->persist($ad_data);
                    $this->em->flush();
                }
            }
        }
        $this->em->getRepository('models\Entities\Product\Filter')->deleteSpecificationFilters($id_list);
        $this->output->set_output(TRUE);
    }

     public function save( $specification_id ){
      
         $filter = new Filter();
         
         $filter->setName($this->input->post('name'));
         $this->input->post('filter_technology') ? $filter->setTechnology($this->em->getReference('models\Entities\Technology', $this->input->post('filter_technology'))) : $filter->setTechnology(NULL); 
         $filter->setSpecification( $this->em->getReference('models\Entities\Product\Specification', $specification_id));
         
         $maxPos = $this->em->getRepository('models\Entities\Product\Filter')->getMaxFilterPosition($specification_id);
         $filters = $this->em->getRepository('models\Entities\Product\Filter')->getFiltersBySpecification($specification_id);
         
         $position = $this->input->post('position');
         $maxPosition = $maxPos[0][1];
        
         if ($position) {
             if ($position > $maxPosition) {
                $position = $maxPosition + 1;
             } else {
                foreach ($filters as $ad) {
                    $adNowPosition = $ad->getPosition();
                    if ($position <= $adNowPosition) {
                        $ad->setPosition($adNowPosition + 1);
                        $this->em->persist($ad);
                        $this->em->flush();
                    }
                }
             }
         } else {
             $position = $maxPosition + 1;
         }
         $filter->setPosition($position);

         $this->em->persist($filter);
         $this->em->flush();
         $message='<p class="message_success"  style="width: 250px; padding: 8px 5px;">Novi filter je dodat!</p>';

		 $this->cache_manager->deleteCache('EcomCatalog_CatalogRepository_getCategoryMenus');
		 $this->cache_manager->deleteCache('EcomCatalog_MenuRepository_getCategoryMenus');

         $this->listing( $specification_id, $message );
     }

     public function details($filter_id, $message=NULL) {
     
         if ($data['filter'] = $this->em->getRepository('models\Entities\Product\Filter')->find($filter_id)) {
             $data['message'] = $message;   
             $this->_render_view('product/filter/edit_filter', $data);
         } else show_404();    
     }
    
     public function edit($filter_id) {
     	
		 $this->cache_manager->deleteCache('EcomCatalog_CatalogRepository_getCategoryMenus');
		 $this->cache_manager->deleteCache('EcomCatalog_MenuRepository_getCategoryMenus');
           
         if($data['filter'] = $this->em->getRepository('models\Entities\Product\Filter')->find($filter_id)) {
               
            $data['filter']->setName( $this->input->post('name') );
            $this->input->post('filter_technology') ? $data['filter']->setTechnology($this->em->getReference('models\Entities\Technology', $this->input->post('filter_technology'))) : $data['filter']->setTechnology(NULL);
            
            $oldPosition = $data['filter']->getPosition();
            $filterPosition = $this->input->post('position');
                    
            $maxPos = $this->em->getRepository('models\Entities\Product\Filter')->getMaxFilterPosition($data['filter']->getSpecification()->getID());
            $filters = $this->em->getRepository('models\Entities\Product\Filter')->getFiltersBySpecification($data['filter']->getSpecification()->getID());       
                
            $maxPosition = $maxPos[0][1];
            
            if ($filterPosition <= $oldPosition) {
                foreach ($filters as $ad) {
                    if ($ad->getPosition() >= $filterPosition && $ad->getPosition() < $oldPosition)
                        $ad->setPosition($ad->getPosition() + 1);
                        $this->em->persist($ad);
                        $this->em->flush();
                }
            } else {
                if ($filterPosition >= $maxPosition) {
                    $filterPosition = $maxPosition;
                }
                foreach ($filters as $ad) {
                    if ($ad->getPosition() <= $filterPosition && $ad->getPosition() > $oldPosition)
                        $ad->setPosition($ad->getPosition() - 1);
                        $this->em->persist($ad);
                        $this->em->flush();
                }
            }
              $data['filter']->setPosition($filterPosition); 

              $this->em->persist($data['filter']);
              $this->em->flush();
              $data['technologies'] = $this->em->getRepository('models\Entities\Technology')->findAll();
              $data['message'] = '<p class="message_success"  style="width: 373px; padding: 8px 5px;">Sve izmene su uspešno sačuvane!</p>';  
              $this->_render_view('product/filter/edit_filter', $data);   
         } else show_404(); 
    }

	public function change_status( $id ) {

        $record = $this->em->getRepository('models\Entities\Product\Filter')->find($id);
        $record->getStatus() ? $record->setStatus(0) : $record->setStatus(1);
		
		$this->cache_manager->deleteCache('EcomCatalog_CatalogRepository_getCategoryMenus');
		$this->cache_manager->deleteCache('EcomCatalog_MenuRepository_getCategoryMenus');
		
		//$this->cache_manager->deleteAllCache();

        $this->em->flush();

        $this->output->set_output($record->getStatus());
    }
 }
 
 /* End of file filters.php */
 /* Location: ./system/applications/_backend/controllers/product/filters.php */