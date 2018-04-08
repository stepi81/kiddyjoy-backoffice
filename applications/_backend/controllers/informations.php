<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * ...
 * @author Ivan Despic [ Codeion ]
 */

use models\Entities\Information;
use models\Entities\InfoDesk;

class Informations extends MY_Controller {

    public $gridParams = array(
       'id'                 => 'productsGrid',
       'width'              => 'auto', 
       'height'             => 400, 
       'rp'                 => 15, 
       'rpOptions'          => '[10,15,20,25,40]', 
       'pagestat'           => 'Prikaz: {from} do {to} / Ukupno: {total} stranica.', 
       'pagetext'           => 'Stranica', 
       'outof'              => 'od', 
       'findtext'           => 'Pronađi', 
       'procmsg'            => 'Obrada u toku, molimo sačekajte...', 
       'blockOpacity'       => 0.5, 
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

    public function listing( $section ) {

        $this->resources['css'][]='flexigrid';
        $this->resources['js'][]='flexigrid';

        $this->gridParams['title']='Pregled stranica';
		
		$colModel['position'] = array('Pozicija', 120, TRUE, 'center', 1);
        $colModel['title'] = array('Naslov', 261, TRUE, 'center', 1);
        $colModel['status'] = array('Status', 50, TRUE, 'center', 1);
        $colModel['actions'] = array('Detalji', 50, FALSE, 'center', 0);

        if( $this->input->post('page') ) $this->gridParams['newp'] = $this->input->post('page');

		$data['page'] = $this->em->getRepository('models\Entities\InfoSection')->find($section);

        $buttons[] = array('Nova stranica', 'add', 'grid_commands', site_url("informations/create/".$data['page']->getID()));
        $buttons[] = array('separator');
        $buttons[] = array('Obriši stranice', 'delete', 'grid_commands', site_url("informations/delete"));
        $buttons[] = array('separator');
        $buttons[] = array('Izaberi sve', 'select_all', 'grid_commands', '/select');
		$buttons[] = array('separator');
        $buttons[] = array('Poništi izbor', 'deselect_all', 'grid_commands', '/desel');
        $buttons[] = array('separator');

        $data['grid'] = build_grid_js('grid', site_url("informations/grid/".$section), $colModel, 'position', 'ASC', $this->gridParams, $buttons);
		
		if( $data['page'] ) {
			$data['grid_title']="Info Desk - ".$data['page']->getName();	
		} else {
			$data['grid_title']="Info Desk";	
		}
        
        $this->_render_view("master/grid_view", $data);
    }

    public function details($id) {

        if ($data['page']=$this->em->getRepository('models\Entities\InfoDesk')->find($id)) {
            
            $this->resources['css'][] = 'plupload';
            $this->resources['js'][] = 'checkbox';
            $this->resources['js'][] = 'tiny_mce';
            $this->resources['js'][] = 'plupload_full';
            $this->resources['js'][] = 'plupload_queue';

            $data['plupload'] = build_plupload_js( site_url('upload/informations/'.$data['page']->getID()) );
            $data['tinymce'] = build_tinymce_js('page', 925, 700, site_url('proxy/get_informations_images/'.$data['page']->getID()));

            $this->_render_view('information/edit_information', $data);
        } else
            show_404();
    }

    public function edit($id) {

        if ($data['page']=$this->em->getRepository('models\Entities\InfoDesk')->find($id)) {
            
            $this->resources['css'][] = 'plupload'; 
        
            $this->resources['js'][] = 'checkbox';
            $this->resources['js'][] = 'tiny_mce';
            $this->resources['js'][] = 'plupload_full';
            $this->resources['js'][] = 'plupload_queue';

            $data['plupload'] = build_plupload_js( site_url('upload/informations/'.$data['page']->getID()) );
            $data['tinymce']=build_tinymce_js('page', 925, 700, site_url('proxy/get_informations_images/'.$data['page']->getID()));
            
            // TODO server validation
            
            $data['page']->setName($this->input->post('name'));
			$data['page']->setTitle($this->input->post('name'));
			$data['page']->setFeatured($this->input->post('featured'));
            $data['page']->setStatus($this->input->post('status'));
            $data['page']->setContent($this->input->post('page'));

            $oldPosition = $data['page']->getPosition();
            $sectionPosition = $this->input->post('position');
                    
            $maxPos = $this->em->getRepository('models\Entities\InfoDesk')->getMaxSectionPosition($data['page']->getSection()->getID());
            $information = $this->em->getRepository('models\Entities\InfoDesk')->getInfoBySection($data['page']->getSection()->getID());       
                
            $maxPosition = $maxPos[0][1];
            
            if ($sectionPosition <= $oldPosition) {
                foreach ($information as $info_data) {
                    if ($info_data->getPosition() >= $sectionPosition && $info_data->getPosition() < $oldPosition)
                        $info_data->setPosition($info_data->getPosition() + 1);
                    $this->em->persist($info_data);
                    $this->em->flush();
                }
            } else {
                if ($sectionPosition >= $maxPosition) {
                    $sectionPosition = $maxPosition;
                }
                foreach ($information as $info_data) {
                    if ($info_data->getPosition() <= $sectionPosition && $info_data->getPosition() > $oldPosition)
                        $info_data->setPosition($info_data->getPosition() - 1);
                    $this->em->persist($info_data);
                    $this->em->flush();
                }
            }
              $data['page']->setPosition($sectionPosition); 

			$icon = $this->create_thumb($data['page']->getIcon());
			$data['page']->setIcon( $icon );

            $this->em->persist($data['page']);
            $this->em->flush();
	    
			//$cacheDriver = new \Doctrine\Common\Cache\ApcCache();
			//$cacheDriver->delete('section_repo_init');

            $data['message']='<p class="message_success">Sve izmene su uspešno izvršene!</p>';

            $this->_render_view('information/edit_information', $data);
        } else
            show_404();
    }

     public function create($section) {
		
		$this->resources['js'][] = 'checkbox';

        $data['section'] = $section; 
		
        $this->_render_view( 'information/create_information', $data);
     }

    public function save($section) {

		$this->resources['js'][] = 'checkbox';
        
        $data['page'] = new InfoDesk();
		
		$data['page']->setName($this->input->post('name'));
		$data['page']->setTitle($this->input->post('name'));
		$data['page']->setFeatured($this->input->post('featured'));
        $data['page']->setStatus($this->input->post('status'));
        	
    	$maxPos = $this->em->getRepository('models\Entities\InfoDesk')->getMaxSectionPosition($section);
        $information = $this->em->getRepository('models\Entities\InfoDesk')->getInfoBySection($section);
         
        $position = $this->input->post('position');
        $maxPosition = $maxPos[0][1];
        
        if ($position) {
            if ($position > $maxPosition) {
                $position = $maxPosition + 1;
            } else {
                foreach ($information as $info_data) {
                    $infoDataNowPosition = $info_data->getPosition();
                    if ($position <= $infoDataNowPosition) {
                        $info_data->setPosition($infoDataNowPosition + 1);
                        $this->em->persist($info_data);
                        $this->em->flush();
                    }
                }
            }
        } else {
            $position=$maxPosition + 1;
        }
		 
        $data['page']->setPosition($position);
		$data['page']->setSection($this->em->getRepository('models\Entities\InfoSection')->find($section));
	
		$this->em->persist($data['page']);
		$this->em->flush();
		
		$this->resources['css'][] = 'plupload';
		
		$this->resources['js'][] = 'tiny_mce';
		$this->resources['js'][] = 'plupload_full';
		$this->resources['js'][] = 'plupload_queue';
		
		$data['plupload'] = build_plupload_js( site_url('upload/informations/'.$data['page']->getID()) );
        $data['tinymce'] = build_tinymce_js('page', 925, 700, site_url('proxy/get_informations_images/'.$data['page']->getID()));
		
		$this->_render_view('information/edit_information', $data);
    }

    public function grid($section) {

        $valid_fields = array('position', 'title', 'status');

        $this->flexigrid->validate_post($this->gridParams['id'], 'position', 'ASC', $valid_fields);
        $criteria = $this->flexigrid->get_criteria();
        $records = $this->em->getRepository('models\Entities\InfoDesk')->getPages($criteria, $section);

        $this->session->unset_userdata('edit_visited');
        $this->output->set_header($this->config->item('json_header'));
        $this->output->set_output($this->flexigrid->json_build($records['record_count'], $records['record_items']));
    }

    public function change_status($id) {

        $page = $this->em->getRepository('models\Entities\InfoDesk')->find($id);
        $page->getStatus() ? $page->setStatus(0) : $page->setStatus(1);
        $this->em->flush();
        $this->output->set_output($page->getStatus());
    }

     public function delete() {
		
        $id_list = explode(',', $this->input->post('items'));

        foreach ($id_list as $id) {
            $info = $this->em->getRepository('models\Entities\InfoDesk')->find($id);
            
            $information = $this->em->getRepository('models\Entities\InfoDesk')->getInfoBySection($info->getSection()->getID());
            foreach ($information as $info_data) {
                $oldPosition = $info_data->getPosition();
                if ($info->getPosition() < $info_data->getPosition()) {
                    $info_data->setPosition($oldPosition - 1);
                    $this->em->persist($info_data);
                    $this->em->flush();
                }
            }
        }
		$info = $this->em->getRepository('models\Entities\InfoDesk')->deleteInfo($id_list);
        $this->output->set_output(TRUE);
	 }

	 private function create_thumb( $thumb = NULL ) {
	 	
		if( !$_FILES['icon']['size'] ) return $thumb;
		
		$upload_config['encrypt_name'] 		= TRUE;
        $upload_config['upload_path'] 		= SERVER_IMAGE_PATH.'icons/pages/';
        $upload_config['allowed_types'] 	= 'gif|jpg|png';
        $upload_config['max_size']			= '2048';
        $upload_config['remove_spaces'] 	= TRUE;
		
		$this->load->library('upload');
        
        $this->upload->initialize($upload_config);
		
		if( $this->upload->do_upload('icon') ) {
			
            $image_data = $this->upload->data();
			
			$resize_config['image_library'] 	= 'gd2';
			$resize_config['source_image']		= $image_data['full_path'];
			$resize_config['width']				= 135;
			$resize_config['height'] 			= 124;
			$resize_config['maintain_ratio']	= TRUE;
			$resize_config['master_dim']		= $image_data['image_width']/$image_data['image_height'] < $resize_config['width']/$resize_config['height'] ? 'width' : 'height';
			
			$this->load->library('image_lib', $resize_config);
			
			if ( $this->image_lib->resize() ) {
				
				if( $thumb ) unlink( SERVER_IMAGE_PATH.'icons/pages/'.$thumb );
				
				$this->image_lib->clear();
				
				$crop_config['image_library']	= 'gd2';
				$crop_config['source_image']	= $image_data['full_path'];
				$crop_config['width']			= 135;
				$crop_config['height'] 			= 124;
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

/* End of file informations.php */
/* Location: ./system/applications/_backend/controllers/informations.php */
