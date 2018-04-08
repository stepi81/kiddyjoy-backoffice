<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * ...
 * @author Marko Stepanovic [ Codeion ]
 */

 use models\Entities\Location;
 use models\Entities\LocationData;
 use models\Entities\Images\LocationImage;
 
 class Locations extends MY_Controller {
     
     public $gridParams=array(
        'id'                   => 'productsGrid',
        'width'                 => 'auto',
        'height'                => 400,
        'rp'                    => 15,
        'rpOptions'             => '[10,15,20,25,40]',
        'pagetext'              => 'Stranica', 
        'outof'                 => 'od', 
        'findtext'              => 'Pronađi', 
        'procmsg'               => 'Obrada u toku, molimo sačekajte...', 
        'pagestat'              => 'Prikaz: {from} do {to} Ukupno: {total} lokacija.',
        'blockOpacity'          => 0.5,
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
       
        
        $this->gridParams['title']='Pregled svih lokacija';
        
        $colModel['icon'] =array( 'Icon', 250, FALSE, 'center', 0 ); 
        //$colModel['name'] =array( 'Naziv', 300, TRUE, 'center', 1 );
        $colModel['alias'] =array( 'Alias', 300, TRUE, 'center', 1 );
        $colModel['latitude'] =array( 'Latitude', 120, FALSE, 'center', 0 );
        $colModel['longitude'] =array( 'Longitude', 120, FALSE, 'center', 0 );
        $colModel['actions']  =array( 'Detalji', 50, FALSE, 'center', 0 ); 
		
        if( $this->input->post('page') ) $this->gridParams['newp'] = $this->input->post('page');
        $data['grid'] = build_grid_js('grid', site_url("settings/locations/grid"), $colModel, 'id', 'DESC', $this->gridParams);

        $data['grid_title'] = "Lokacije";
        $this->_render_view( "master/grid_view", $data );
     }

     public function details( $id ) {

        if( $data['location'] = $this->em->getRepository('models\Entities\LocationData')->find($id) ) {
			$data['test'] =  $data['location']->getID();
			$this->resources['js'][] = 'checkbox';
            $this->_render_view( 'settings/location/edit_location', $data );
        }
        else show_404();
     }
     
     public function edit( $id ) {

        if( $data['location'] = $this->em->getRepository('models\Entities\LocationData')->find($id) ) {
			
            if(!( $icon_data = $data['location']->getIcon() )) $icon_data = NULL;

			$data['location']->setAlias( $this->input->post('alias') );
			$data['location']->setEmail( $this->input->post('email') );
		    $data['location']->setLatitude( $this->input->post('latitude') );
		    $data['location']->setLongitude( $this->input->post('longitude') );
            $data['location']->setAddress( $this->input->post('address') );
            $data['location']->setPhones( $this->input->post('phones') );
            $data['location']->setInfo( $this->input->post('info') );
			$data['location']->setPublic( $this->input->post('public') );
    
			$atLeastOneImage = FALSE; 
			
            if($icon = $this->create_icon( $id, $icon_data )) { $atLeastOneImage = TRUE; $goodIcon = $icon; } 
            if($icon = $this->create_icon_small( $id, $icon_data )) { $atLeastOneImage = TRUE; $goodIcon = $icon; }
            if($icon = $this->create_icon_mobile( $id, $icon_data )) { $atLeastOneImage = TRUE; $goodIcon = $icon; }

            if($atLeastOneImage) $data['location']->setIcon( $goodIcon );
	    
	    	//$cacheDriver = new \Doctrine\Common\Cache\ApcCache();
            //$cacheDriver->delete('location_repo_init'); 
	    
            $this->em->persist($data['location']);
            $this->em->flush();
            
            $data['message'] = '<p class="message_success">Sve izmene su uspešno izvršene!</p>';

            $this->resources['js'][] = 'checkbox';
            $this->_render_view( 'settings/location/edit_location', $data ); 
        }
        else show_404();
     }
     
     public function grid() {
          
        $valid_fields = array('id', 'name', 'alias');
         
         $this->flexigrid->validate_post($this->gridParams['id'], 'id', 'DESC', $valid_fields);                                              
         $criteria = $this->flexigrid->get_criteria();
         $records = $this->em->getRepository('models\Entities\Location')->getLocations( $criteria );
         
         $this->session->unset_userdata('edit_visited');
         $this->output->set_header($this->config->item('json_header'));
         $this->output->set_output($this->flexigrid->json_build($records['record_count'],$records['record_items']));
     }
     
     public function gallery($location_id){

        $this->resources['css'][] = 'gallery';
        $this->resources['css'][] = 'fancybox';
        $this->resources['css'][] = 'uploadify';

        $this->resources['js'][] = 'gallery';
        $this->resources['js'][] = 'fancybox';
        $this->resources['js'][] = 'swfobject';
        $this->resources['js'][] = 'uploadify';
        $this->resources['js'][] = 'mouse';
        $this->resources['js'][] = 'sortable';

        $this->session->set_userdata('location_image_uri', $this->uri->uri_string()); 
        
        $data['images'] = $this->em->getRepository('models\Entities\Location')->getImagesByLocation($location_id);
        $data['location'] = $this->em->getRepository('models\Entities\Location')->find($location_id);

        $this->_render_view('settings/location/gallery_view', $data);
     }
     
     public function save_position(){

        $id = $this->input->post('id');

        $this->em->getRepository('models\Entities\LocationData')->setImagesPosition($id);

    }
    
    public function delete_image($id){

        $image = $this->em->getRepository('models\Entities\Images\LocationImage')->find($id);

        /* Deleting images from server*/
        unlink( SERVER_IMAGE_PATH.'locations/'.$image->getName() );//large
        unlink( SERVER_IMAGE_PATH.'locations/thumb/'.$image->getName() );//thumbnails
        
        $this->em->remove($image);
        $this->em->flush();
        
        $items = $this->em->getRepository('models\Entities\Location')->getImagesAfterDelete($image->getLocation()->getID(), $image->getPosition());
        
        foreach( $items as $item ) {
            $item->setPosition( $item->getPosition() - 1 );
            $this->em->flush();     
        }

        redirect($this->session->userdata('location_image_uri'));
    }
    
    private function create_icon( $id, $icon = NULL ) {
         
        if( !$_FILES['icon']['size'] ) return $icon;
        
        $upload_config['encrypt_name']      = FALSE;
		$upload_config['file_name'] 		= $id.'.png';
		
        $upload_config['upload_path']       = SERVER_IMAGE_PATH.'icons/locations/large/';
        $upload_config['allowed_types']     = 'png';
        $upload_config['max_size']          = '2048';
        $upload_config['remove_spaces']     = TRUE;
        
        $this->load->library('upload');
        
        $this->upload->initialize($upload_config);
        
        if( $this->upload->do_upload('icon') ) {
            
            $image_data = $this->upload->data();
            
            if ( $image_data['image_width'] == 78 && $image_data['image_height'] == 78 ) {
                if( $icon && file_exists( SERVER_IMAGE_PATH.'icons/locations/large/'.$icon ) && $icon != $id.'.png' ) unlink( SERVER_IMAGE_PATH.'icons/locations/large/'.$icon );
				if( file_exists( SERVER_IMAGE_PATH.'icons/locations/large/'.$image_data['file_name'] ) ) rename( SERVER_IMAGE_PATH.'icons/locations/large/'.$image_data['file_name'] , SERVER_IMAGE_PATH.'icons/locations/large/'.$id.'.png' );
				return $id.'.png';
                //return $image_data['file_name'];
            }
            else {
                unlink( SERVER_IMAGE_PATH.'icons/locations/large/'.$image_data['file_name'] );    
                return NULL;  
            }
        }
        else {
            return NULL;
        }
     }

     private function create_icon_small( $id, $icon = NULL ) {
         
        if( !$_FILES['icon_small']['size'] ) return $icon;
        
        $upload_config['encrypt_name']      = FALSE;
		$upload_config['file_name'] 		= $id.'.png';

        $upload_config['upload_path']       = SERVER_IMAGE_PATH.'icons/locations/small/';
        $upload_config['allowed_types']     = 'png';
        $upload_config['max_size']          = '2048';
        $upload_config['remove_spaces']     = TRUE;
        
        $this->load->library('upload');
        
        $this->upload->initialize($upload_config);
        
        if( $this->upload->do_upload('icon_small') ) {
            
            $image_data = $this->upload->data();
            
            if ( $image_data['image_width'] == 46 && $image_data['image_height'] == 94 ) {
            	if( $icon && file_exists( SERVER_IMAGE_PATH.'icons/locations/small/'.$icon ) && $icon != $id.'.png' ) unlink( SERVER_IMAGE_PATH.'icons/locations/small/'.$icon );
				if( file_exists( SERVER_IMAGE_PATH.'icons/locations/small/'.$image_data['file_name'] ) ) rename( SERVER_IMAGE_PATH.'icons/locations/small/'.$image_data['file_name'] , SERVER_IMAGE_PATH.'icons/locations/small/'.$id.'.png' );
				return $id.'.png';
                //return $image_data['file_name'];
            }
            else {
                unlink( SERVER_IMAGE_PATH.'icons/locations/small/'.$image_data['file_name'] );    
                return NULL;  
            }
        }
        else {
            return NULL;
        }
     }

     private function create_icon_mobile( $id, $icon = NULL ) {
        if( !$_FILES['icon_mobile']['size'] ) return $icon;
        
        $upload_config['encrypt_name']      = FALSE;
		$upload_config['file_name'] 		= $id.'.png';
		
        $upload_config['upload_path']       = SERVER_IMAGE_PATH.'icons/locations/mobile/';
        $upload_config['allowed_types']     = 'png';
        $upload_config['max_size']          = '2048';
        $upload_config['remove_spaces']     = TRUE;
        
        $this->load->library('upload');
        
        $this->upload->initialize($upload_config);
        
        if( $this->upload->do_upload('icon_mobile') ) {
            $image_data = $this->upload->data();
            
            if ( $image_data['image_width'] == 35 && $image_data['image_height'] == 33 ) {
            	if( $icon && file_exists( SERVER_IMAGE_PATH.'icons/locations/mobile/'.$icon ) && $icon != $id.'.png' ) unlink( SERVER_IMAGE_PATH.'icons/locations/mobile/'.$icon );
				if( file_exists( SERVER_IMAGE_PATH.'icons/locations/mobile/'.$image_data['file_name'] ) ) rename( SERVER_IMAGE_PATH.'icons/locations/mobile/'.$image_data['file_name'] , SERVER_IMAGE_PATH.'icons/locations/mobile/'.$id.'.png' );
                return $id.'.png';
                //return $image_data['file_name'];
            }
            else {
                unlink( SERVER_IMAGE_PATH.'icons/locations/mobile/'.$image_data['file_name'] );
                return NULL;
            }
        }
        else {
            return NULL;
        }
     }

 }
 
 /* End of file locations.php */
 /* Location: ./system/applications/_backend/controllers/settings/locations.php */