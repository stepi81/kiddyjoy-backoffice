<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * ...
 * @author Aleksandar Milas [ Codeion ]
 */

 use models\Entities\Vendor;
 use models\Entities\Vendor\Video;
 
 class Vendors extends MY_Controller {
     
     public $gridParams = array(
        'id'                    => 'productsGrid',
        'width'                 => 'auto',
        'height'                => 400,
        'rp'                    => 15,
        'rpOptions'             => '[10,15,20,25,40]',
        'pagestat'              => 'Prikaz: {from} do {to} Ukupno: {total} vendora.',
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
        
		$this->gridParams['title'] = 'Pregled svih vendora';
		
		//$colModel['image']  = array( 'Logo', 300, FALSE, 'center', 0 ); 
		$colModel['id']  = array( 'Naziv', 200, TRUE, 'center', 1 );
		//$colModel['actions']   = array( 'Detalji', 80, FALSE, 'center', 0 ); 
        
        $buttons[] = array('Novi vendor', 'add', 'grid_commands', site_url("vendors/create"));
		$buttons[] = array('separator');
        
        if( $this->input->post('page') ) $this->gridParams['newp'] = $this->input->post('page');
		$data['grid'] = build_grid_js('grid', site_url("vendors/grid"), $colModel, 'id', 'ASC', $this->gridParams, $buttons);

		$data['grid_title'] = "Vendori";
		$this->_render_view( "master/grid_view", $data );
	 }
     
	 public function listing_video() {
			
		$this->resources['css'][] = 'flexigrid';
		$this->resources['js'][] = 'flexigrid';
        
		$this->gridParams['title'] = 'Pregled svih video klipova';
		
		$colModel['position']  = array( 'Pozicija', 100, TRUE, 'center', 1 );
		$colModel['title']  = array( 'Naslov', 200, TRUE, 'center', 1 );
		$colModel['code']  = array( 'Kod', 200, TRUE, 'center', 1 );
		$colModel['vendor']  = array( 'Vendor', 200, TRUE, 'center', 1 );
		$colModel['actions']  = array( 'Detalji', 80, FALSE, 'center', 0 );
        
        $buttons[] = array('Novi video klip', 'add', 'grid_commands', site_url("vendors/create_video"));
		$buttons[] = array('separator');
		$buttons[] = array('Obriši video klip', 'delete', 'grid_commands', site_url("vendors/delete_video"));
        $buttons[] = array('separator');
        $buttons[] = array('Izaberi sve', 'select_all', 'grid_commands', '/select');
        $buttons[] = array('separator');
        $buttons[] = array('Poništi izbor', 'deselect_all', 'grid_commands', '/desel');
        $buttons[] = array('separator');
        
        if( $this->input->post('page') ) $this->gridParams['newp'] = $this->input->post('page');
		$data['grid'] = build_grid_js('grid', site_url("vendors/grid_video"), $colModel, 'id', 'ASC', $this->gridParams, $buttons);

		$data['grid_title'] = "Video klipovi";
		$this->_render_view( "master/grid_view", $data );
	 }

	 
	 public function details( $id ) {

        if( $data['brand'] = $this->em->getRepository('models\Entities\Product\Brand')->find($id) ) {
        	$this->_render_view( 'brand/edit_brand', $data );
        }
		else show_404();
     }

	public function video_details( $id ) {

        if( $data['video'] = $this->em->getRepository('models\Entities\Vendor\Video')->find($id) ) {
        	$this->_render_view( 'vendor/edit_video', $data );
        }
		else show_404();
     }
     
	 public function create() {
    	$this->_render_view( 'vendor/new_vendor', NULL );
     }
	 
	 public function create_video() {
    	$this->_render_view( 'vendor/new_video', NULL );
     }
	 
	 public function save() {
	 	
		$vendor = new Vendor;
		$vendor->setID( $this->input->post('vendor_name') );
		$this->em->persist($vendor);
        $this->em->flush();
		
		$data['message'] = 'Uspešno je dodat novi vendor.';
    	$this->_render_view( 'vendor/new_vendor', $data );
     }
	 
	 public function save_video() {
	 	
		$position = $this->input->post('video_position');
		$maxVideo = $this->em->getRepository('models\Entities\Vendor\Video')->getMaxVideoPosition();
        $videos = $this->em->getRepository('models\Entities\Vendor\Video')->getAllVendorVideos();

        $maxPosition = $maxVideo[0][1];
        if ($position) {
            if ($position >= $maxPosition) {
                $position = $maxPosition + 1;
            } else {
                foreach ($videos as $video) {
                    $videoNowPosition = $video->getPosition();
                    if ($position <= $videoNowPosition) {
                        $video->setPosition($videoNowPosition + 1);
                        $this->em->persist($video);
                        $this->em->flush();
                    }
                }
            }
        } else {
            $position = $maxPosition + 1;
        }
		
		$video = new Video;
		$video->setPosition( $position );
		$video->setTitle( $this->input->post('video_title') );
		$video->setCode( $this->input->post('video_code') );		
		$video->setVendor( $this->em->getReference('models\Entities\Vendor', $this->session->userdata('application_id') ) );
		
		$this->em->persist($video);
        $this->em->flush();
		
		$data['message'] = 'Uspešno je dodat novi vendor video klip.';
    	$this->_render_view( 'vendor/new_video', $data );
     }

	public function edit_video( $id ) {
	 	
		if ($data['video'] = $this->em->getRepository('models\Entities\Vendor\Video')->find($id)) {
			$data['video']->setTitle( $this->input->post('video_title') );
			$data['video']->setCode( $this->input->post('video_code') );		
			$data['video']->setVendor( $this->em->getReference('models\Entities\Vendor', $this->session->userdata('application_id') ) );
			
			$position = $this->input->post('video_position');
            $old_position = $this->input->post('old_position');

			$maxVideo = $this->em->getRepository('models\Entities\Vendor\Video')->getMaxVideoPosition();
		    $videos = $this->em->getRepository('models\Entities\Vendor\Video')->getAllVendorVideos();

            $maxPosition = $maxVideo[0][1];

            if ($position <= $old_position) {
                foreach ($videos as $video) {
                    if ($video->getPosition() >= $position && $video->getPosition() < $old_position)
                        $video->setPosition($video->getPosition() + 1);
                    $this->em->persist($video);
                    $this->em->flush();
                }
            } else {
                if ($position >= $maxPosition) {
                    $position=$maxPosition;
                }
                foreach ($videos as $video) {
                    if ($video->getPosition() <= $position && $video->getPosition() > $old_position)
                        $video->setPosition($video->getPosition() - 1);
                    $this->em->persist($video);
                    $this->em->flush();
                }
            }
			
			$data['video']->setPosition( $position );
			
			$this->em->persist($data['video']);
	        $this->em->flush();
			
			$data['message'] = 'Vendor video klip je uspesno izmenjen.';
	    	$this->_render_view( 'vendor/edit_video', $data );
    	} else {
            show_404();
        }
     }
	 
	 public function delete_video() {

        $id_list=explode(',', $this->input->post('items'));

        foreach ($id_list as $id) {
            $video = $this->em->getRepository('models\Entities\Vendor\Video')->find($id);
            $videos = $this->em->getRepository('models\Entities\Vendor\Video')->getAllVendorVideos();
            foreach ($videos as $video_data) {
                $oldPosition = $video_data->getPosition();
                if ($video->getPosition() < $video_data->getPosition()) {
                    $video_data->setPosition($oldPosition - 1);
                    $this->em->persist($video_data);
                    $this->em->flush();
                }
            }
        }
        $this->em->getRepository('models\Entities\Vendor\Video')->deleteVideo($id_list);
        $this->output->set_output(TRUE);
    }

     public function grid() {
     	 
		$valid_fields = array('id');
         
 		$this->flexigrid->validate_post($this->gridParams['id'], 'id', 'ASC', $valid_fields);
 		$criteria = $this->flexigrid->get_criteria();
 		$records = $this->em->getRepository('models\Entities\Product\Brand')->getVendors( $criteria );
         
 		$this->session->unset_userdata('edit_visited');
 		$this->output->set_header($this->config->item('json_header'));
 		$this->output->set_output($this->flexigrid->json_build($records['record_count'],$records['record_items']));
     }
	 
	 public function grid_video() {
     	 
		$valid_fields = array('position', 'title', 'code', 'vendor');
         
 		$this->flexigrid->validate_post($this->gridParams['id'], 'position', 'ASC', $valid_fields);
 		$criteria = $this->flexigrid->get_criteria();
 		$records = $this->em->getRepository('models\Entities\Vendor\Video')->getVideos( $criteria, $this->session->userdata('application_id') );
         
 		$this->session->unset_userdata('edit_visited');
 		$this->output->set_header($this->config->item('json_header'));
 		$this->output->set_output($this->flexigrid->json_build($records['record_count'],$records['record_items']));
     }
	 
}
 
 /* End of file vendors.php */
 /* Location: ./system/applications/_backend/controllers/vendors.php */