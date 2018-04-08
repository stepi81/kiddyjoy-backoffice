<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * ...
 * @author Marko Stepanovic [ Codeion ]
 */

 use models\Entities\Background;
 use models\Entities\Images\BackgroundImage;
 
 class Backgrounds extends MY_Controller {
     
     public $gridParams = array(
        'id'                    => 'productsGrid',
        'width'                 => 'auto',
        'height'                => 400,
        'rp'                    => 15,
        'rpOptions'             => '[10,15,20,25,40]',
        'pagestat'              => 'Prikaz: {from} do {to} Ukupno: {total} pozadina.',
        'blockOpacity'          => 0.5,
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
        
        $this->gridParams['title'] = 'Pregled svih pozadina';
         
        $colModel['name']  = array( 'Naziv', 400, TRUE, 'center', 1 );
		$colModel['vendor']  = array( 'Vendor', 100, FALSE, 'center', 0 );
        $colModel['status']  = array( 'Status', 50, TRUE, 'center', 0 );
        $colModel['actions']   = array( 'Detalji', 50, FALSE, 'center', 0 ); 

        $buttons[] = array('Nova pozadina', 'add', 'grid_commands', site_url("settings/backgrounds/create"));
        $buttons[] = array('separator');
        $buttons[] = array('Obriši pozadinu', 'delete', 'grid_commands', site_url("settings/backgrounds/delete"));
        $buttons[] = array('separator');
        $buttons[] = array('Izaberi sve', 'select_all', 'grid_commands', '/select');
        $buttons[] = array('separator');
        $buttons[] = array('Poništi izbor', 'deselect_all', 'grid_commands', '/desel');
        $buttons[] = array('separator');

        if( $this->input->post('page') ) $this->gridParams['newp'] = $this->input->post('page');
        $data['grid'] = build_grid_js('grid', site_url("settings/backgrounds/grid"), $colModel, 'id', 'DESC', $this->gridParams, $buttons);

        $data['grid_title'] = "Pozadine";
        $this->_render_view( "master/grid_view", $data );
     }
     
     public function create() {
         
        $this->resources['js'][] = 'checkbox';
		$data['vendors'] = $this->em->getRepository('models\Entities\Vendor')->findAll();
		$data['categories'] = $this->em->getRepository('models\Entities\Product\Category')->findAll(); 
		
        $this->_render_view( 'settings/background/create_background', $data );
     }
     
     public function details( $id ) {

        if( $data['background'] = $this->em->getRepository('models\Entities\Background')->find($id) ) {
        	
        	$data['vendors'] = $this->em->getRepository('models\Entities\Vendor')->findAll();
			$data['categories'] = $this->em->getRepository('models\Entities\Product\Category')->findAll(); 
			$data['subcategories'] = $this->em->getRepository('models\Entities\Product\Subcategory')->findAll(); 
			
			$background_images = $data['background']->getImages();
			
			$data['background_url'] = '';
			$data['left_url'] = '';
			$data['right_url'] = '';
			
			foreach( $background_images as $b_image ) {
						
				if( $b_image->getPosition() == 1 ) {
					$data['background_url'] = $b_image->getURL();
				} else if( $b_image->getPosition() == 2 ) {
					$data['left_url'] = $b_image->getURL();
				} else if( $b_image->getPosition() == 3 ) {
					$data['right_url'] = $b_image->getURL();
				}
				
			} 
			
			if( $data['background']->getObjectClass() == 'EcomCatalog\Entity\CatalogSubcategory' ) {
				$data['subcategory_id'] = $data['background']->getObjectID();
				$data['object_subcategory'] = $this->em->getRepository('models\Entities\Product\Subcategory')->find($data['subcategory_id']);
				$data['category_id'] = $data['object_subcategory']->getCategory()->getID();	
				$data['object_category'] = $this->em->getRepository('models\Entities\Product\Category')->find($data['category_id']);
			} else if( $data['background']->getObjectClass() == 'EcomCatalog\Entity\CatalogCategory' ) {
				$data['category_id'] = $data['background']->getObjectID();
				$data['object_category'] = $this->em->getRepository('models\Entities\Product\Category')->find($data['category_id']);
			}
			
            $this->resources['js'][] = 'checkbox';
            $this->_render_view( 'settings/background/edit_background', $data );
        }
        else show_404();
     }
     
     public function save() {

        // TODO server validation
        
        $this->resources['js'][] = 'checkbox';
		
		$object_id = null;
		
		if( $object_id = $this->input->post('subcategory') ) {
			$subcategory = $this->em->getRepository('models\Entities\Product\Subcategory')->find($object_id);
			//$object_class = get_class($subcategory);	
			$object_class = 'EcomCatalog\Entity\CatalogSubcategory';
		} else if( $object_id = $this->input->post('group') ) {
			$subcategory = $this->em->getRepository('models\Entities\Product\Subcategory')->find($object_id);
			//$object_class = get_class($subcategory);	
			$object_class = 'EcomCatalog\Entity\CatalogSubcategory';
		} else if( $object_id = $this->input->post('product_category') ) {
			$category = $this->em->getRepository('models\Entities\Product\Category')->find($object_id);
			//$object_class = get_class($category);
			$object_class = 'EcomCatalog\Entity\CatalogCategory';
		}
        
        $data['background'] = new Background();
        
        //if( ($background_image = $this->create_background_image()) && ($slideshow_image = $this->create_slideshow_image()) ) {
            
        $data['background']->setName( $this->input->post('background_name') );
        $data['background']->setStatus( $this->input->post('status') );
		
		if( $object_id ) {
			$data['background']->setObjectClass( $object_class );
			$data['background']->setObjectID( $object_id );
		} else {
			$data['background']->setObjectClass(NULL);
			$data['background']->setObjectID(NULL);
		}
		
		if($this->session->userdata('application_id'))
			$data['background']->setVendor($this->em->getReference('models\Entities\Vendor', $this->session->userdata('application_id')));
		else {
			if ($this->input->post('vendor_id'))
            	$data['background']->setVendor($this->em->getReference('models\Entities\Vendor', $this->input->post('vendor_id')));
    		else
            	$data['background']->setVendor(NULL);
		}
        $this->em->persist($data['background']);
        $this->em->flush();
		
		$background_id = $data['background']->getID();
		
		if( $background_image = $this->create_background_image() ) {
			$data['background_image'] = new BackgroundImage();
			$data['background_image']->setName($background_image);
			$data['background_image']->setPosition(1);
			$data['background_image']->setURL($this->input->post('background_url'));
			$data['background_image']->setBackground($this->em->getReference('models\Entities\Background', $background_id));
			$this->em->persist($data['background_image']);
		}
		
		if( $left_background_image = $this->create_left_background_image() ) {
			$data['left_background_image'] = new BackgroundImage();
			$data['left_background_image']->setName($left_background_image);
			$data['left_background_image']->setPosition(2);
			$data['left_background_image']->setURL($this->input->post('left_url'));
			$data['left_background_image']->setBackground($this->em->getReference('models\Entities\Background', $background_id));
			$this->em->persist($data['left_background_image']);
		}
    	
		if( $right_background_image = $this->create_right_background_image() ) {
			$data['right_background_image'] = new BackgroundImage();
			$data['right_background_image']->setName($right_background_image);
			$data['right_background_image']->setPosition(3);
			$data['right_background_image']->setURL($this->input->post('right_url'));
			$data['right_background_image']->setBackground($this->em->getReference('models\Entities\Background', $background_id));
			$this->em->persist($data['right_background_image']);
		}
		
		$this->em->flush();
		
		
   		//$cacheDriver = new \Doctrine\Common\Cache\ApcCache();
		//$cacheDriver->delete('location_bgr_repo'); 
		
		//$this->cache_manager->deleteCache('Application_BackgroundRepository_getBackgrounds');
    
        $data['message'] = '<p class="message_success">Nova pozadina je uspešno sačuvana!</p>';
        //}
        //else {
        //    $data['message'] = '<p class="message_error">Došlo je do greške! Molimo Vas proverite unete parametre.</p>';
        //}
        
        $this->_render_view( 'settings/background/create_background', $data );
     }
     
     public function edit( $id ) {

        if( $data['background'] = $this->em->getRepository('models\Entities\Background')->find($id) ) {

			if( $object_id = $this->input->post('subcategory') ) {
				$subcategory = $this->em->getRepository('models\Entities\Product\Subcategory')->find($object_id);
				//$object_class = get_class($subcategory);	
				$object_class = 'EcomCatalog\Entity\CatalogSubcategory';
			} else if( $object_id = $this->input->post('group') ) {
				$subcategory = $this->em->getRepository('models\Entities\Product\Subcategory')->find($object_id);
				//$object_class = get_class($subcategory);	
				$object_class = 'EcomCatalog\Entity\CatalogSubcategory';
			} else if( $object_id = $this->input->post('product_category') ) {
				$category = $this->em->getRepository('models\Entities\Product\Category')->find($object_id);
				//$object_class = get_class($category);
				$object_class = 'EcomCatalog\Entity\CatalogCategory';
			}

            $data['background']->setName( $this->input->post('background_name') );
            $data['background']->setStatus( $this->input->post('status') );
			
			if( $object_id ) {
				$data['background']->setObjectClass( $object_class );
				$data['background']->setObjectID( $object_id );
			} else {
				$data['background']->setObjectClass(NULL);
				$data['background']->setObjectID(NULL);
			}
            
			if($this->session->userdata('application_id'))
				$data['background']->setVendor($this->em->getReference('models\Entities\Vendor', $this->session->userdata('application_id')));
			else {
				if ($this->input->post('vendor_id'))
                	$data['background']->setVendor($this->em->getReference('models\Entities\Vendor', $this->input->post('vendor_id')));
        		else
                	$data['background']->setVendor(NULL);
			}
			
            $this->em->persist($data['background']);
            $this->em->flush();
			
			$background_id = $data['background']->getID();
			
			foreach( $data['background']->getImages() as $b_image ) {
				if( $b_image->getPosition() == 1  ) {
					$old_background_image = $b_image->getName();
					if( $_FILES['background_image']['size'] ) {
						unlink( SERVER_IMAGE_PATH.'backgrounds/'.$old_background_image );
						$this->em->remove($b_image);
					}
					if( $background_image = $this->create_background_image() ) {
						$data['background_image'] = new BackgroundImage();
						$data['background_image']->setName($background_image);
						$data['background_image']->setPosition(1);
						$data['background_image']->setURL($this->input->post('background_url'));
						$data['background_image']->setBackground($this->em->getReference('models\Entities\Background', $background_id));
						$this->em->persist($data['background_image']);
					}
				} else if( $b_image->getPosition() == 2  ) {
					$old_left_image = $b_image->getName();	
					if( $_FILES['left_background_image']['size'] ) {
						unlink( SERVER_IMAGE_PATH.'backgrounds/'.$old_left_image );
						$this->em->remove($b_image);
					}				
					if( $left_background_image = $this->create_left_background_image() ) {
						$data['left_background_image'] = new BackgroundImage();
						$data['left_background_image']->setName($left_background_image);
						$data['left_background_image']->setPosition(2);
						$data['left_background_image']->setURL($this->input->post('left_url'));
						$data['left_background_image']->setBackground($this->em->getReference('models\Entities\Background', $background_id));
						$this->em->persist($data['left_background_image']);
					}
				} else if( $b_image->getPosition() == 3  ) {
					$old_right_image = $b_image->getName();	
					if( $_FILES['right_background_image']['size'] ) {
						unlink( SERVER_IMAGE_PATH.'backgrounds/'.$old_right_image );
						$this->em->remove($b_image);
					}					
					if( $right_background_image = $this->create_right_background_image() ) {
						$data['right_background_image'] = new BackgroundImage();
						$data['right_background_image']->setName($right_background_image);
						$data['right_background_image']->setPosition(3);
						$data['right_background_image']->setURL($this->input->post('right_url'));
						$data['right_background_image']->setBackground($this->em->getReference('models\Entities\Background', $background_id));
						$this->em->persist($data['right_background_image']);
					}
				}
			}

			$this->em->flush();
            
            $data['message'] = '<p class="message_success">Sve izmene su uspešno izvršene!</p>';
            
            $this->resources['js'][] = 'checkbox';
            
            //$this->_render_view( 'settings/background/edit_background', $data );
			redirect("settings/backgrounds/details/$id");
        }
        else show_404();
     }
     
     public function grid() {
          
        $valid_fields = array('id', 'status', 'name');
         
         $this->flexigrid->validate_post($this->gridParams['id'],'id', 'DESC', $valid_fields);
         $criteria = $this->flexigrid->get_criteria();
         $records = $this->em->getRepository('models\Entities\Background')->getBackgrounds( $criteria );
         
         $this->session->unset_userdata('edit_visited');
         $this->output->set_header($this->config->item('json_header'));
         $this->output->set_output($this->flexigrid->json_build($records['record_count'],$records['record_items']));
     }
     
     public function delete() {
        
        $id_list = explode( ',', $this->input->post('items') );
        $this->em->getRepository('models\Entities\Background')->deleteBackgrounds($id_list);
		
		//$this->cache_manager->deleteCache('Application_BackgroundRepository_getBackgrounds');
		
        $this->output->set_output( TRUE );
     }
     
     public function change_status( $id ){
         
        $background = $this->em->getRepository('models\Entities\Background')->find($id);
        $background->getStatus() ? $background->setStatus(0) : $background->setStatus(1); 
         
        $this->em->flush();
		
		$this->cache_manager->deleteCache('Application_BackgroundRepository_getBackgrounds');
		
        $this->output->set_output($background->getStatus());
     }
     
    private function create_background_image( $background_image = NULL ) {
         
        if( !$_FILES['background_image']['size'] ) return $background_image;
        
        $upload_config['encrypt_name']   = TRUE;
        $upload_config['upload_path']    = SERVER_IMAGE_PATH.'backgrounds/';
        $upload_config['allowed_types']  = 'gif|jpg|png';
        $upload_config['max_size']       = '2048';
        $upload_config['remove_spaces']  = TRUE;
        
        $this->load->library('upload');
        
        $this->upload->initialize($upload_config);
        
        if( $this->upload->do_upload('background_image') ) {
            
            $image_data = $this->upload->data();
            
            $img_config['image_library']     = 'gd2';
            $img_config['source_image']     = $image_data['full_path'];
            
            $this->load->library('image_lib', $img_config);
            
            if ( $this->image_lib->resize() ) {
                if( $background_image ) unlink( SERVER_IMAGE_PATH.'backgrounds/'.$background_image );
                return $image_data['file_name'];
            }
            else return NULL;
        }
        else return NULL;
    }
	
	private function create_left_background_image( $background_image = NULL ) {
         
        if( !$_FILES['left_background_image']['size'] ) return $background_image;
        
        $upload_config['encrypt_name']   = TRUE;
        $upload_config['upload_path']    = SERVER_IMAGE_PATH.'backgrounds/';
        $upload_config['allowed_types']  = 'gif|jpg|png';
        $upload_config['max_size']       = '2048';
        $upload_config['remove_spaces']  = TRUE;
        
        $this->load->library('upload');
        
        $this->upload->initialize($upload_config);
        
        if( $this->upload->do_upload('left_background_image') ) {
            
            $image_data = $this->upload->data();
            
            $img_config['image_library']     = 'gd2';
            $img_config['source_image']     = $image_data['full_path'];
            
            $this->load->library('image_lib', $img_config);
            
            if ( $this->image_lib->resize() ) {
                if( $background_image ) unlink( SERVER_IMAGE_PATH.'backgrounds/'.$background_image );
                return $image_data['file_name'];
            }
            else return NULL;
        }
        else return NULL;
    }

	private function create_right_background_image( $background_image = NULL ) {
         
        if( !$_FILES['right_background_image']['size'] ) return $background_image;
        
        $upload_config['encrypt_name']   = TRUE;
        $upload_config['upload_path']    = SERVER_IMAGE_PATH.'backgrounds/';
        $upload_config['allowed_types']  = 'gif|jpg|png';
        $upload_config['max_size']       = '2048';
        $upload_config['remove_spaces']  = TRUE;
        
        $this->load->library('upload');
        
        $this->upload->initialize($upload_config);
        
        if( $this->upload->do_upload('right_background_image') ) {
            
            $image_data = $this->upload->data();
            
            $img_config['image_library']     = 'gd2';
            $img_config['source_image']     = $image_data['full_path'];
            
            $this->load->library('image_lib', $img_config);
            
            if ( $this->image_lib->resize() ) {
                if( $background_image ) unlink( SERVER_IMAGE_PATH.'backgrounds/'.$background_image );
                return $image_data['file_name'];
            }
            else return NULL;
        }
        else return NULL;
    }

 }
 
 /* End of file backgrounds.php */
 /* Location: ./system/applications/_backend/controllers/settings/backgrounds.php */