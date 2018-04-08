<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * ...
 * @author Marko Stepanovic [ Codeion ]
 */
 
 use models\Entities\Article\Category;
 
 class Article_Categories extends MY_Controller {
     
    public $gridParams = array(
        'id'                    => 'articleCategoriesGrid',
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
         
        $this->resources['css'] = array();
        $this->resources['js'] = array();
    }
     
    public function listing( $category_id = null ) {
            
        $this->resources['css'][] = 'flexigrid';
        $this->resources['js'][] = 'flexigrid';

		if( !$category_id ) {
			
			$this->gridParams['title'] = 'Pregled svih kategorija blogova';
			$data['grid_title'] = "Kategorije blogova";
			
			$colModel['position']  = array( 'Pozicija', 80, TRUE, 'center', 1 );
	        $colModel['name'] = array( 'Ime', 250, TRUE, 'center', 1 );
			$colModel['status'] = array( 'Status', 80, TRUE, 'center', 0 );
			$colModel['articles'] = array('Blogovi', 80, FALSE, 'center', 0);
			$colModel['highlights'] = array('Izdvajamo', 80, FALSE, 'center', 0);
			$colModel['subcategories'] = array('Podkategorije', 80, FALSE, 'center', 0);
	        $colModel['actions'] = array('Detalji', 80, FALSE, 'center', 0);
			
			$buttons[] = array('Dodaj kategoriju', 'add', 'grid_commands', site_url("article/article_categories/create/".$category_id));
	        $buttons[] = array('separator');
			$buttons[] = array('Obriši kategoriju', 'delete', 'grid_commands', site_url("article/article_categories/delete"));
		} else {
			
			$data['article_category'] = $this->em->getRepository('models\Entities\Article\Category')->find($category_id);
			
			$this->gridParams['title'] = 'Pregled svih podkategorija - '.$data['article_category']->getName();
			$data['grid_title'] = "Podkategorije - ".$data['article_category']->getName();
			
			$colModel['position']  = array( 'Pozicija', 80, TRUE, 'center', 1 );
	        $colModel['name'] = array( 'Ime', 250, TRUE, 'center', 1 );
			$colModel['status'] = array( 'Status', 80, TRUE, 'center', 0 );
			$colModel['articles'] = array('Blogovi', 80, FALSE, 'center', 0);
			$colModel['highlights'] = array('Izdvajamo', 80, FALSE, 'center', 0);
	        $colModel['actions'] = array('Detalji', 80, FALSE, 'center', 0);
			
			$buttons[] = array('Dodaj podkategoriju', 'add', 'grid_commands', site_url("article/article_categories/create/".$category_id));
	        $buttons[] = array('separator');
			$buttons[] = array('Obriši podkategoriju', 'delete', 'grid_commands', site_url("article/article_categories/delete"));
			
			$data['route'] = "article/article_categories/listing/";
        	$data['params_id'] = 'articleCategoriesGrid';	
		}
        $buttons[] = array('separator');
        $buttons[] = array('Izaberi sve', 'select_all', 'grid_commands', '/select');
        $buttons[] = array('separator');
        $buttons[] = array('Poništi izbor', 'deselect_all', 'grid_commands', '/desel');
        $buttons[] = array('separator');
 
        if( $this->input->post('page') ) $this->gridParams['newp'] = $this->input->post('page');
        $data['grid'] = build_grid_js('grid', site_url("article/article_categories/grid/".$category_id), $colModel, 'position', 'ASC', $this->gridParams, $buttons);

        $this->_render_view( "master/grid_view", $data );
    }
  
    public function grid( $category_id = null ) {
          
         $valid_fields = array( 'name', 'position', 'status' );
         
         $this->flexigrid->validate_post($this->gridParams['id'], 'position', 'ASC', $valid_fields);
         $criteria = $this->flexigrid->get_criteria();

         $records = $this->em->getRepository('models\Entities\Article\Category')->getCategories( $criteria, $category_id );
         
         $this->session->unset_userdata('edit_visited');
         $this->output->set_header($this->config->item('json_header'));
         $this->output->set_output($this->flexigrid->json_build($records['record_count'],$records['record_items']));
    }
	 
	public function create( $category_id = null ) {
	 	
		$this->resources['js'][] = 'checkbox';
		
		$data['category_id'] = $category_id;
		
		if( $category_id ) {		
			$data['article_category'] = $this->em->getRepository('models\Entities\Article\Category')->find($category_id);
		}

        $this->_render_view('article/create_category', $data); 
	}
	 
	public function save() {
	 	
	 	$this->resources['js'][] = 'checkbox';
		
		if( $this->input->post('category_id') ) {
			$category_id = $this->input->post('category_id');
		} else {
			$category_id = null;
		}
		$data['category_id'] = $category_id;

		$data['article_category'] = new Category(); 
			
        $position = $this->input->post('position');

        $maxCategory = $this->em->getRepository('models\Entities\Article\Category')->getMaxPosition( $category_id );
        $records = $this->em->getRepository('models\Entities\Article\Category')->getAllCategories( $category_id );

        $maxPosition = $maxCategory[0][1];

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

		$data['article_category']->setName( $this->input->post('name') );
		$data['article_category']->setStatus( $this->input->post('status') );
		$data['article_category']->setSeoTitle( $this->input->post('seo_title') );
		$data['article_category']->setSeoKeywords( $this->input->post('seo_keywords') );
        $data['article_category']->setPosition( $position );

		if( $thumb = $this->create_thumb() ) {
	    	$data['article_category']->setImage( $thumb );	
		}
		
		if($this->input->post('category_id')){
            $data['article_category']->setParent($this->em->getReference('models\Entities\Article\Category', $this->input->post('category_id')));
        }
	
		$this->em->persist($data['article_category']);
		$this->em->flush();
		
		$data['message'] = '<p class="message_success">Uspešno ste dodali novu kategoriju</p>';
		
		$this->_render_view('article/create_category', $data); ;		
	}

	public function details( $id ) {

        if( $data['article_category'] = $this->em->getRepository('models\Entities\Article\Category')->find($id) ) {
        	
			$this->resources['js'][] = 'checkbox';
			
        	$this->_render_view( 'article/edit_category', $data );
        }
		else show_404();
    }
	 
	public function edit($id) {
	 	
		$this->resources['js'][] = 'checkbox';
		
		if( $data['article_category'] = $this->em->getRepository('models\Entities\Article\Category')->find($id) ) {

			if( $data['article_category']->getParent() ) {
				$category_id = $data['article_category']->getParent()->getID();
			} else {
				$category_id = null;
			}

            $position = $this->input->post('position');
            $old_position = $data['article_category']->getPosition();

            $maxCategory = $this->em->getRepository('models\Entities\Article\Category')->getMaxPosition( $category_id );
        	$records = $this->em->getRepository('models\Entities\Article\Category')->getAllCategories( $category_id );

            $maxPosition = $maxCategory[0][1];

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

			$data['article_category']->setName( $this->input->post('name') );
			$data['article_category']->setStatus( $this->input->post('status') );
			$data['article_category']->setSeoTitle( $this->input->post('seo_title') );
			$data['article_category']->setSeoKeywords( $this->input->post('seo_keywords') );
	        $data['article_category']->setPosition( $position );
			
			if( $thumb = $this->create_thumb($data['article_category']->getImage()) ) {
				$data['article_category']->setImage( $thumb );
			}

			$this->em->persist($data['article_category']);
			$this->em->flush();
			
			$data['message'] = '<p class="message_success">Sve izmene su uspešno izvršene!</p>';
			
			$this->_render_view( 'article/edit_category', $data );
		
        } else show_404();
	}

    public function delete() {
		
		$id_list = explode( ',', $this->input->post('items') );
		
		foreach ($id_list as $id) {
            $category = $this->em->getRepository('models\Entities\Article\Category')->find($id);
			
			if( $category->getParent() ) {
				$category_id = $category->getParent()->getID();
			} else {
				$category_id = null;
			}
			
            $records = $this->em->getRepository('models\Entities\Article\Category')->getAllCategories($category_id);
            foreach ($records as $record) {
                $oldPosition = $record->getPosition();
                if ($category->getPosition() < $record->getPosition()) {
                    $record->setPosition($oldPosition - 1);
                    $this->em->persist($record);
                    $this->em->flush();
                }
            }
        }
		
		$this->em->getRepository('models\Entities\Article\Category')->deleteCategories($id_list);
		$this->output->set_output( TRUE );
	}

	public function change_status( $id ){
         
          $record = $this->em->getRepository('models\Entities\Article\Category')->find($id);
		  $record->getStatus() ? $record->setStatus(0) : $record->setStatus(1); 
          
          $this->em->flush();

          $this->output->set_output($record->getStatus());
    }

	private function create_thumb($thumb = NULL) {

        if(!$_FILES['thumb']['size']) return $thumb;

        $upload_config['encrypt_name'] = TRUE;
        $upload_config['upload_path'] = SERVER_IMAGE_PATH.'articles/categories/';
        $upload_config['allowed_types'] = 'gif|jpg|png';
        $upload_config['max_size'] = '2048';
        $upload_config['remove_spaces'] = TRUE;

        $this->load->library('upload');

        $this->upload->initialize($upload_config);

        if($this->upload->do_upload('thumb')) {

            $image_data = $this->upload->data();
                
            $this->load->library('Resizer_Librarie');

            $this->resizer_librarie->set($image_data['full_path']);
            $this->resizer_librarie->resize_image(170,110,'crop',SERVER_PATH . '/assets/img/articles/categories/'.$image_data['file_name']);

			if( $thumb ) {
				unlink(SERVER_PATH.'/assets/img/articles/categories/'.$thumb);
			}
			
            return $image_data['file_name'];
				
        } else return NULL;
    }

 }
 
 /* End of file article_categories.php */
 /* Location: ./system/applications/_backend/controllers/article/article_categories.php */