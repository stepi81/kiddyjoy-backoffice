<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */

 use models\Entities\Product\Brand;
 use models\Entities\Images\BrandImage;
 
 class Brands extends MY_Controller {
     
     public $gridParams = array(
        'id'                    => 'brandGrid',
        'width'                 => 'auto',
        'height'                => 400,
        'rp'                    => 15,
        'rpOptions'             => '[10,15,20,25,40]',
        'pagestat'              => 'Prikaz: {from} do {to} Ukupno: {total} proizvođača.',
        'blockOpacity'          => 0.5,
        'pagetext'              => 'Stranica', 
        'outof'                 => 'od', 
        'showTableToggleBtn'    => true
     );
     
     public function __construct() {
         
         parent::__construct();
		 
		 $this->load->helper('flexigrid');
		 
		 $this->load->library('Flexigrid');
		 $this->load->library('Cache_Manager');
		 
		 $this->resources['css'] = array();
         $this->resources['js'] = array();
     }
	 
	 public function listing() {
			
		$this->resources['css'][] = 'flexigrid';
		$this->resources['js'][] = 'flexigrid';
        
		$this->gridParams['title'] = 'Pregled svih proizvođača';
		
		$colModel['id'] = array( 'ID', 50, TRUE, 'center', 1 ); 
		$colModel['position'] = array( 'Pozicija', 50, TRUE, 'center', 1 ); 
		$colModel['image'] = array( 'Logo', 300, FALSE, 'center', 0 ); 
		$colModel['name'] = array( 'Naziv', 200, TRUE, 'center', 1 );
		$colModel['featured'] = array('Popularno', 50, TRUE, 'center', 0);
		$colModel['status'] = array('Status', 50, TRUE, 'center', 0);
		$colModel['actions'] = array( 'Detalji', 80, FALSE, 'center', 0 ); 
		
		$buttons[] = array('Novi brend', 'add', 'grid_commands', site_url("brands/create"));
        $buttons[] = array('separator');
        $buttons[] = array('Obriši brend', 'delete', 'grid_commands', site_url("brands/delete"));
        $buttons[] = array('separator');
        $buttons[] = array('Izaberi sve', 'select_all', 'grid_commands', '/select');
        $buttons[] = array('separator');
        $buttons[] = array('Poništi izbor', 'deselect_all', 'grid_commands', '/desel');
        $buttons[] = array('separator');
        
        if( $this->input->post('page') ) $this->gridParams['newp'] = $this->input->post('page');
		$data['grid'] = build_grid_js('grid', site_url("brands/grid"), $colModel, 'position', 'ASC', $this->gridParams, $buttons);

		$data['grid_title'] = "Proizvođači";
		$this->_render_view( "master/grid_view", $data );
	 }
     
	 public function grid() {
     	 
		$valid_fields = array('name', 'id', 'position');
         
 		$this->flexigrid->validate_post($this->gridParams['id'], 'position', 'ASC', $valid_fields);
 		$criteria = $this->flexigrid->get_criteria();
 		$records = $this->em->getRepository('models\Entities\Product\Brand')->getBrands( $criteria );
         
 		$this->session->unset_userdata('edit_visited');
 		$this->output->set_header($this->config->item('json_header'));
 		$this->output->set_output($this->flexigrid->json_build($records['record_count'],$records['record_items']));
     }

	 public function create() {
	 	
		$brands = $this->em->getRepository('models\Entities\Product\Brand')->findAll();
		$position = 1;
		
		foreach( $brands as $brand ) {
			$brand->setPosition( $position );
			$position = $position + 1;
			$this->em->persist($brand);
		}
		$this->em->flush();

        $this->_render_view( 'brand/new_brand' );
     }
	 
	 public function save() {
        
        // TODO server validation 
        
        $data['brand'] = new Brand(); 
		
		if ($_FILES["logo"]["size"] > 0) {
			
			$upload_config['upload_path'] = SERVER_PATH . 'assets/img/brands/';
            $upload_config['allowed_types'] = 'png|jpg';
            $upload_config['encrypt_name']=TRUE;

            $this->load->library('upload'); 
        
            $this->upload->initialize($upload_config);
			
            $data['brand']->setName( $this->input->post('brand_name') );
			$data['brand']->setPosition( $this->input->post('position') );
			
			$position = $this->input->post('position');
			
			$maxRecord = $this->em->getRepository('models\Entities\Product\Brand')->getMaxRecord();
	        $records = $this->em->getRepository('models\Entities\Product\Brand')->findAll();
	
	        $maxPosition = $maxRecord[0][1];
	
	        if ($position) {
	            if ($position >= $maxPosition) {
	                $position = $maxPosition + 1;
	            } else {
	                foreach ($records as $record) {
	                    if ($position <= $record->getPosition()) {
	                        $record->setPosition($record->getPosition() + 1);
	                        $this->em->persist($record);
	                        $this->em->flush();
	                    }
	                }
	            }
	        } else {
	            $position = $maxPosition + 1;
	        }
			
			$data['brand']->setPosition( $position );

			if ($this->upload->do_upload('logo')) {
                $image = $this->upload->data();
                $this->resize($image);
				$data['brand']->setImage( $image['file_name'] );
            } else {
           		$data['message'] = '<p class="message_error">Došlo je do greške! Molimo Vas proverite unete parametre.</p>';
            }
		
			$this->em->persist($data['brand']);
			$this->em->flush();
			
			$data['message'] = '<p class="message_success">Nov brend je kreiran!</p>';
		}
		else {
			$data['message'] = '<p class="message_error">Došlo je do greške! Molimo Vas proverite unete parametre.</p>';
		}
		
		$this->_render_view( 'brand/new_brand', $data );
	 }

	 
	 public function details( $id ) {

        if( $data['brand'] = $this->em->getRepository('models\Entities\Product\Brand')->find($id) ) {
        	$this->_render_view( 'brand/edit_brand', $data );
        }
		else show_404();
     }
     
	 public function edit( $id ) {

        if( $data['brand'] = $this->em->getRepository('models\Entities\Product\Brand')->find($id) ) {
        	
			$data['brand']->setName( $this->input->post('brand_name') );
            
           // TODO server validation
           if ($_FILES["logo"]["size"] > 0) {
       
	           $upload_config['upload_path'] = SERVER_PATH . 'assets/img/brands/';
	           $upload_config['allowed_types'] = 'png|jpg';
	           $upload_config['encrypt_name']=TRUE;
	
	           $this->load->library('upload'); 
	        
	           $this->upload->initialize($upload_config);   
	             
	           if ($this->upload->do_upload('logo')) {
	                $image = $this->upload->data();
				   
                    $old_image_name = $data['brand']->getImage();    
                    unlink(SERVER_PATH . 'assets/img/brands/' . $old_image_name);
					
					$this->resize($image);
					
                    $data['brand']->setImage($image['file_name']);

	                $data['message'] = '<p class="message_success">Sve izmene su uspešno izvršene!</p>';
	           } else {
	           		$data['message'] = '<p class="message_error">Došlo je do greške! Molimo Vas proverite unete parametre.</p>';
	           } 
   
           } 

           $position = $this->input->post('position');
           $old_position = $data['brand']->getPosition();

           $maxRecord = $this->em->getRepository('models\Entities\Product\Brand')->getMaxRecord();
	       $records = $this->em->getRepository('models\Entities\Product\Brand')->findAll();

           $maxPosition = $maxRecord[0][1];

           if ($position <= $old_position) {
                foreach ($records as $record) {
                    if ($record->getPosition() >= $position && $record->getPosition() < $old_position)
                        $record->setPosition($record->getPosition() + 1);
	                    $this->em->persist($record);
	                    $this->em->flush();
                }
           } else {
                if ($position >= $maxPosition) {
                    $position = $maxPosition;
                }
                foreach ($records as $record) {
                    if ($record->getPosition() <= $position && $record->getPosition() > $old_position)
                        $record->setPosition($record->getPosition() - 1);
	                    $this->em->persist($record);
	                    $this->em->flush();
                }
           }
		   
		   $data['brand']->setPosition( $position );
           
           $this->em->persist($data['brand']);
	       $this->em->flush();
			   
           redirect("brands/details/$id");
        
		} else show_404();
     }

	 public function change_status( $id ) {

        $brand = $this->em->getRepository('models\Entities\Product\Brand')->find($id);
        $brand->getStatus() ? $brand->setStatus(0) : $brand->setStatus(1);

        $this->em->flush();

        $this->output->set_output($brand->getStatus());
     }
	 
	 public function change_featured( $id ) {

        $brand = $this->em->getRepository('models\Entities\Product\Brand')->find($id);
        $brand->getFeatured() ? $brand->setFeatured(0) : $brand->setFeatured(1);

        $this->em->flush();

        $this->output->set_output($brand->getFeatured());
     }
	 
	 public function delete() {
	 	
		$id_list = explode(',', $this->input->post('items'));
		
		foreach ($id_list as $id) {
            $record = $this->em->getRepository('models\Entities\Product\Brand')->find($id);
            $records = $this->em->getRepository('models\Entities\Product\Brand')->findAll();
            foreach ($records as $record_data) {
                if ($record->getPosition() < $record_data->getPosition()) {
                    $record_data->setPosition($record_data->getPosition() - 1);
                    $this->em->persist($record_data);
                    $this->em->flush();
                }
            }
        }

        $this->em->getRepository('models\Entities\Product\Brand')->deleteBrands($id_list);
        $this->output->set_output(TRUE);
	 }
	 
     public function resize($image = NULL) {

        $img_config['image_library']    = 'gd2';
        $img_config['source_image']     = $image['full_path'];
        $img_config['width']            = 150;
        $img_config['height']           = 120;
        $img_config['master_dim']       = $image['image_width']/$image['image_height'] < $img_config['width']/$img_config['height'] ? 'width' : 'height';
        
        $this->load->library('image_lib', $img_config); 

       if ($this->image_lib->resize()){
        
        $this->image_lib->clear();
        
        $crop_config['image_library']    = 'gd2';
        $crop_config['source_image']     = $image['full_path'];
        $crop_config['width']            = 150;
        $crop_config['height']           = 120;
        $crop_config['maintain_ratio']   = FALSE;           
        
        $imageSize = $this->image_lib->get_image_properties($image['full_path'], TRUE);
        
        switch( $img_config['master_dim'] ) {
                case 'width':
                    $crop_config['y_axis'] = ($imageSize['height'] - $crop_config['height']) / 2;
                    break;
                case 'height':
                    $crop_config['x_axis'] = ($imageSize['width'] - $crop_config['width']) / 2;
                    break;
            }
        $this->image_lib->initialize($crop_config);
        $this->image_lib->crop(); 
       }
     } 
}
 
 /* End of file brands.php */
 /* Location: ./system/applications/_backend/controllers/brands.php */