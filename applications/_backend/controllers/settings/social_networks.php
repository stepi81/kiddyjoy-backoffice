<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * ...
 * @author Marko Stepanovic [ Codeion ]
 */

use models\Entities\SocialNetwork;

class Social_Networks extends MY_Controller {

    public $gridParams=array(
    'id'                    => 'socialGrid',
    'width' => 'auto', 
    'height' => 400, 
    'rp' => 15, 
    'rpOptions' => '[10,15,20,25,40]', 
    'pagestat' => 'Prikaz: {from} do {to} / Ukupno: {total} rekorda.', 
    'pagetext' => 'Stranica', 
    'outof' => 'od', 
    'findtext' => 'Pronađi', 
    'procmsg' => 'Obrada u toku, molimo sačekajte...', 
    'blockOpacity' => 0.5, 
    'showTableToggleBtn' => true);

    public function __construct() {

        parent::__construct();

        $this->load->helper('flexigrid');
        $this->load->helper('upload');
        $this->load->helper('tinymce');

        $this->load->library('Flexigrid');

        $this->resources['css']=array();
        $this->resources['js']=array();
    }

    public function listing() {

        $this->resources['css'][]='flexigrid';
        $this->resources['js'][]='flexigrid';
       
        $this->gridParams['title']='Pregled socialnih mreža';

        $colModel['image'] = array('Thumb', 261, FALSE, 'center', 0);
        $colModel['name'] = array('Ime', 120, TRUE, 'center', 1);
		$colModel['status'] = array('Status', 50, TRUE, 'center', 1);
        $colModel['actions'] = array('Detalji', 50, FALSE, 'center', 0);

        $buttons[] = array('Novi socialna mreža', 'add', 'grid_commands', site_url("settings/social_networks/create"));
        $buttons[] = array('separator');
        $buttons[] = array('Obriši socialnu mrežu', 'delete', 'grid_commands', site_url("settings/social_networks/delete"));
        $buttons[] = array('separator');
        $buttons[] = array('Izaberi sve', 'select_all', 'grid_commands', '/select');
		$buttons[] = array('separator');
        $buttons[] = array('Poništi izbor', 'deselect_all', 'grid_commands', '/desel');
        $buttons[] = array('separator');
        
        if( $this->input->post('page') ) $this->gridParams['newp'] = $this->input->post('page');
       
        $data['grid'] = build_grid_js('grid', site_url("settings/social_networks/grid"), $colModel, 'id', 'DESC', $this->gridParams, $buttons);

        $data['grid_title'] = "Socialne mreže";
        $this->_render_view("master/grid_view", $data);
    }

    public function details($id) {
    	
		$this->resources['js'][] = 'checkbox';

        if ($data['social_network'] = $this->em->getRepository('models\Entities\SocialNetwork')->find($id)) {
            $this->_render_view('settings/social_networks/edit_network', $data);
        } else
            show_404();
    }

    public function create() {
    	
		$this->resources['js'][] = 'checkbox';

        $this->_render_view('settings/social_networks/create_network');
    }

    public function save() {

		$this->resources['js'][] = 'checkbox';

		if( $thumb = $this->create_thumb() ) {
			
			$data['social_network'] = new SocialNetwork();
			
			$data['social_network']->setName( $this->input->post('title') );
			$data['social_network']->setImage( $thumb );
			$data['social_network']->setStatus( $this->input->post('status') );
			$data['social_network']->setSocialURL($this->input->post('social_url'));
		
			$this->em->persist($data['social_network']);
			$this->em->flush();
			
			$data['message'] = '<p class="message_success">Nova socialna mreža je uspešno postavljena!</p>';
		}
		else {
			$data['message'] = '<p class="message_error">Došlo je do greške! Molimo Vas proverite unete parametre.</p>';
		}
		$this->_render_view('settings/social_networks/create_network', $data);
        
    }
    
    public function edit( $id ) {
    	
		$this->resources['js'][] = 'checkbox';

        if ($data['social_network'] = $this->em->getRepository('models\Entities\SocialNetwork')->find($id)) {
			
			if( $thumb = $this->create_thumb($data['social_network']->getImage()) ) {
				
				$data['social_network']->setName($this->input->post('title'));
				$data['social_network']->setImage( $thumb );
				$data['social_network']->setStatus($this->input->post('status'));
				$data['social_network']->setSocialURL($this->input->post('social_url'));
				
				$this->em->persist($data['social_network']);
				$this->em->flush();
				
				$data['message'] = '<p class="message_success">Sve izmene su uspešno izvršene!</p>';
			}
			else {
				$data['message'] = '<p class="message_error">Došlo je do greške! Molimo Vas proverite unete parametre.</p>';
			}

        	$this->_render_view('settings/social_networks/edit_network', $data);
         }
         else show_404();
    }

    public function grid() {

        $valid_fields=array('id', 'name', 'status');

        $this->flexigrid->validate_post($this->gridParams['id'], 'id', 'DESC', $valid_fields);
        $criteria=$this->flexigrid->get_criteria();
        $records=$this->em->getRepository('models\Entities\SocialNetwork')->getSocialNetworks($criteria);

        $this->session->unset_userdata('edit_visited');
        $this->output->set_header($this->config->item('json_header'));
        $this->output->set_output($this->flexigrid->json_build($records['record_count'], $records['record_items']));
    }
  
     public function delete() {
        
        $id_list = explode( ',', $this->input->post('items') );
        
        $this->em->getRepository('models\Entities\SocialNetwork')->deleteSocialNetworks($id_list);
        $this->output->set_output( TRUE );
     }
	 
	 private function create_thumb( $thumb = NULL ) {
	 	
		if( !$_FILES['thumb']['size'] ) return $thumb;
		
		$upload_config['encrypt_name'] 		= TRUE;
        $upload_config['upload_path'] 		= SERVER_IMAGE_PATH.'icons/social/';
        $upload_config['allowed_types'] 	= 'gif|jpg|png';
        $upload_config['max_size']			= '2048';
        $upload_config['remove_spaces'] 	= TRUE;
		
		$this->load->library('upload');
        
        $this->upload->initialize($upload_config);
		
		if( $this->upload->do_upload('thumb') ) {
			
            $image_data = $this->upload->data();
			
			$resize_config['image_library'] 	= 'gd2';
			$resize_config['source_image']		= $image_data['full_path'];
			$resize_config['width']				= 27;
			$resize_config['height'] 			= 27;
			$resize_config['maintain_ratio']	= TRUE;
			$resize_config['master_dim']		= $image_data['image_width']/$image_data['image_height'] < $resize_config['width']/$resize_config['height'] ? 'width' : 'height';
			
			$this->load->library('image_lib', $resize_config);
			
			if ( $this->image_lib->resize() ) {
				
				if( $thumb ) unlink( SERVER_IMAGE_PATH.'icons/social/'.$thumb );
				
				$this->image_lib->clear();
				
				$crop_config['image_library']	= 'gd2';
				$crop_config['source_image']	= $image_data['full_path'];
				$crop_config['width']			= 27;
				$crop_config['height'] 			= 27;
				$crop_config['maintain_ratio'] 	= FALSE;
				
				$imageSize = $this->image_lib->get_image_properties($image_data['full_path'], TRUE);
				
				switch( $resize_config['master_dim'] ) {
					case 'width':
						$crop_config['y_axis'] = ($imageSize['height'] - $crop_config['height']) / 2;
						break;
					case 'height':
						$crop_config['x_axis'] = ($imageSize['width'] - $crop_config['width']) / 2;
						break;
				}
				
				$this->image_lib->initialize($crop_config);
				
				if ( $this->image_lib->crop() ) {
					$this->image_lib->clear();
				}
				
				return $image_data['file_name'];
			}
			else return NULL;
        }
		else return NULL;
	 }

}

/* End of file social_networks.php */
/* Location: ./system/applications/_backend/controllers/settings/social_networks.php */
