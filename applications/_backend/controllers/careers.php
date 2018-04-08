<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * ...
 * @author Aleksanda Milas [ Codeion ]
 */

 use models\Entities\CareerRecord;
 use models\Entities\CareerJob;
 use models\Entities\CareerAd;
 
 class Careers extends MY_Controller {
     
     public $gridParams = array(
        'id'                   => 'productsGrid',
        'width'                 => 'auto',
        'height'                => 400,
        'rp'                    => 15,
        'rpOptions'             => '[10,15,20,25,40]',
        'pagestat'              => 'Prikaz: {from} do {to} / Ukupno: {total} vesti.',
        'pagetext'				=> 'Stranica',
		'outof'					=> 'od',
        'findtext'              => 'Pronađi',
        'procmsg'				=> 'Obrada u toku, molimo sačekajte...',
        'blockOpacity'          => 0.5,
        'showTableToggleBtn'    => true
     );
     
     public function __construct() {
         
         parent::__construct();
		 
		 $this->load->helper('flexigrid');
		 $this->load->helper('upload');
		 $this->load->helper('tinymce');
		 
		 $this->load->library('Flexigrid');
		 
		 $this->resources['css'] = array();
         $this->resources['js'] = array();
     }
	 
	 public function listing() {
			
		$this->resources['css'][] = 'flexigrid';
		$this->resources['js'][] = 'flexigrid';

		$this->gridParams['title'] = 'Pregled svih prijava';
		
        $colModel['registration_date']  = array( 'Datum prijave', 120, TRUE, 'center', 1 ); 
        $colModel['career_job_id']  = array( 'Naziv posla', 200, TRUE, 'center', 1 ); 
        $colModel['name']  = array( 'Ime', 150, TRUE, 'center', 1 );
        $colModel['email']  = array( 'E-mail', 120, TRUE, 'center', 1 );
        $colModel['phone']  = array( 'Telefon', 120, TRUE, 'center', 1 );
        $colModel['cv']  = array( 'CV', 120, FALSE, 'center', 0 ); 
        $colModel['actions']   = array( 'Detalji', 80, FALSE, 'center', 0 );

		$buttons[] = array('Obriši prijavu', 'delete', 'grid_commands', site_url("careers/delete"));
		$buttons[] = array('separator');
		$buttons[] = array('Izaberi sve', 'select_all', 'grid_commands', '/select');
		$buttons[] = array('separator');
		$buttons[] = array('Poništi izbor', 'deselect_all', 'grid_commands', '/desel');
		$buttons[] = array('separator');

        if( $this->input->post('page') ) $this->gridParams['newp'] = $this->input->post('page');
		$data['grid'] = build_grid_js('grid', site_url("careers/grid"), $colModel, 'id', 'ASC', $this->gridParams, $buttons);

		$data['grid_title'] = "Prijave";
		$this->_render_view( "master/grid_view", $data );
	 }
     
	 public function jobs_listing() {
			
		$this->resources['css'][] = 'flexigrid';
		$this->resources['js'][] = 'flexigrid';

		$this->gridParams['title'] = 'Pregled svih poslova';
		
        $colModel['name']  = array( 'Naziv posla', 300, TRUE, 'center', 1 ); 
        $colModel['actions']   = array( 'Detalji', 80, FALSE, 'center', 0 );

        $buttons[] = array('Dodaj posao', 'add','grid_commands', site_url("careers/create_job"));
        $buttons[] = array('separator');
		$buttons[] = array('Obriši posao', 'delete', 'grid_commands', site_url("careers/delete_job"));
		$buttons[] = array('separator');
		$buttons[] = array('Izaberi sve', 'select_all', 'grid_commands', '/select');
		$buttons[] = array('separator');
		$buttons[] = array('Poništi izbor', 'deselect_all', 'grid_commands', '/desel');
		$buttons[] = array('separator');

        if( $this->input->post('page') ) $this->gridParams['newp'] = $this->input->post('page');
		$data['grid'] = build_grid_js('grid', site_url("careers/jobs_grid"), $colModel, 'id', 'ASC', $this->gridParams, $buttons);

		$data['grid_title'] = "Poslovi";
		$this->_render_view( "master/grid_view", $data );
	 }


	 public function ads_listing() {
			
		$this->resources['css'][] = 'flexigrid';
		$this->resources['js'][] = 'flexigrid';

		$this->gridParams['title'] = 'Pregled svih oglasa';
		
        $colModel['career_job_id']  = array( 'Naziv posla', 300, TRUE, 'center', 1 );
        $colModel['active']  = array( 'Aktivan', 80, TRUE, 'center', 1 ); 
        $colModel['actions']   = array( 'Detalji', 80, FALSE, 'center', 0 );

        $buttons[] = array('Dodaj oglas', 'add','grid_commands', site_url("careers/create_ad"));
        $buttons[] = array('separator');
		$buttons[] = array('Obriši oglas', 'delete', 'grid_commands', site_url("careers/delete_ad"));
		$buttons[] = array('separator');
		$buttons[] = array('Izaberi sve', 'select_all', 'grid_commands', '/select');
		$buttons[] = array('separator');
		$buttons[] = array('Poništi izbor', 'deselect_all', 'grid_commands', '/desel');
		$buttons[] = array('separator');

        if( $this->input->post('page') ) $this->gridParams['newp'] = $this->input->post('page');
		$data['grid'] = build_grid_js('grid', site_url("careers/ads_grid"), $colModel, 'id', 'ASC', $this->gridParams, $buttons);

		$data['grid_title'] = "Oglasi";
		$this->_render_view( "master/grid_view", $data );
	 }


     public function create() {
		
		$this->resources['js'][] = 'checkbox';
		$this->resources['css'][]='datepicker';
         
        $this->_render_view( 'news/create_info' );
     }
	 
     public function create_job () {
     	
        $this->_render_view( 'career/new_job' );
     }
	 
     public function create_ad () {

		$this->resources['js'][] = 'tiny_mce';
		$this->resources['js'][] = 'checkbox';
		
     	$data['jobs'] = $this->em->getRepository('models\Entities\CareerJob')->findAll();
		$data['tinymce'] = build_tinymce_js('page', 600, 700, NULL);
        $this->_render_view( 'career/new_ad', $data );
     }	 
	 
	 public function details( $id ) {

        if( $data['career'] = $this->em->getRepository('models\Entities\CareerRecord')->find($id) ) {
        	$data['job_name'] = $this->em->getRepository('models\Entities\CareerJob')->find($data['career']->getCareerJobID())->getName();
			
        	$this->_render_view( 'career/career_details', $data );
        }
		else show_404();
     }
     
	 public function job_details( $id ) {

        if( $data['job'] = $this->em->getRepository('models\Entities\CareerJob')->find($id) ) {

        	$this->_render_view( 'career/edit_job', $data );
        }
		else show_404();
     }

	 public function ad_details( $id ) {

        if( $data['ad'] = $this->em->getRepository('models\Entities\CareerAd')->find($id) ) {
        	$data['job_name'] = $this->em->getRepository('models\Entities\CareerJob')->find($data['ad']->getCareerJobID())->getName();
			$data['jobs'] = $this->em->getRepository('models\Entities\CareerJob')->findAll();
        	
			$data['tinymce'] = build_tinymce_js('page', 600, 700, NULL);
			$this->resources['js'][] = 'tiny_mce';
			$this->resources['js'][] = 'checkbox';
        	$this->_render_view( 'career/edit_ad', $data );
        }
		else show_404();
     }

     public function save() {
     	
		$this->resources['js'][] = 'checkbox';
        $this->resources['css'][]='datepicker';
        // TODO server validation
        
        $data['news'] = new Info();
		
		if( $thumb = $this->create_thumb() ) {
			
			$data['news']->setDate(new \DateTime(date( 'Y-m-d', strtotime( $this->input->post('send_date')))));
			$data['news']->setTitle( $this->input->post('title') );
            $data['news']->setSummary( $this->input->post('summary') );
			$data['news']->setThumb( $thumb );
		
			$this->em->persist($data['news']);
			$this->em->flush();
			
			$this->resources['css'][] = 'plupload';
			
			$this->resources['js'][] = 'tiny_mce';
			$this->resources['js'][] = 'plupload_full';
			$this->resources['js'][] = 'plupload_queue';
			
			$data['plupload'] = build_plupload_js( site_url('upload/news/'.$data['news']->getID()) );
			$data['tinymce'] = build_tinymce_js('page', 600, 700, site_url('proxy/get_news_images/'.$data['news']->getID()));
			
			$this->_render_view( 'news/edit_info', $data );
		}
		else {
			$data['message'] = '<p class="message_error">Došlo je do greške! Molimo Vas proverite unete parametre.</p>';
			$this->_render_view( 'news/create_info', $data );
		}
     }
		
	 public function save_job() {
     	
        // TODO server validation
        
        	$data['job'] = new CareerJob();

			$data['job']->setName( $this->input->post('name') );
		
			$this->em->persist($data['job']);
			$this->em->flush();
			
			$data['message'] = '<p class="message_success">Posao je uspešno sačuvan!</p>';
			$this->_render_view( 'career/new_job', $data );
			
     }

	 public function save_ad() {
     	
        // TODO server validation
        
        	$data['ad'] = new CareerAd();

			$data['ad']->setCareerJobID( $this->input->post('career_job_id') );
			$data['ad']->setText( $this->input->post('page') );
			$data['ad']->setStatus( $this->input->post('active') );
		
			$this->em->persist($data['ad']);
			$this->em->flush();
			
			$this->resources['js'][] = 'tiny_mce';
			$this->resources['js'][] = 'checkbox';
			
	     	$data['jobs'] = $this->em->getRepository('models\Entities\CareerJob')->findAll();
			$data['tinymce'] = build_tinymce_js('page', 600, 700, NULL);
			
			$data['message'] = '<p class="message_success">Oglas je uspešno sačuvan!</p>';
			$this->_render_view( 'career/new_ad', $data );
			
     }

	 public function edit( $id ) {

        if( $data['news'] = $this->em->getRepository('models\Entities\News\Info')->find($id) ) {
			
			// TODO server validation
			
			if( $thumb = $this->create_thumb($data['news']->getThumb()) ) {
				
                $data['news']->setDate(new \DateTime(date( 'Y-m-d', strtotime( $this->input->post('send_date')))));
				$data['news']->setStatus( $this->input->post('active') );
				$data['news']->setTitle( $this->input->post('title') );
				$data['news']->setPage( $this->input->post('page') );
                $data['news']->setSummary( $this->input->post('summary') );
				$data['news']->setThumb( $thumb );
				
				$this->em->persist($data['news']);
				$this->em->flush();
				
				$data['message'] = '<p class="message_success">Sve izmene su uspešno izvršene!</p>';
			}
			else {
				$data['message'] = '<p class="message_error">Došlo je do greške! Molimo Vas proverite unete parametre.</p>';
			}
			
			$this->resources['css'][] = 'plupload';
		    $this->resources['css'][] = 'datepicker';
			$this->resources['js'][] = 'checkbox';
			$this->resources['js'][] = 'tiny_mce';
			$this->resources['js'][] = 'plupload_full';
			$this->resources['js'][] = 'plupload_queue';
			
			$data['plupload'] = build_plupload_js( site_url('upload/news/'.$data['news']->getID()) );
			$data['tinymce'] = build_tinymce_js('page', 600, 700, site_url('proxy/get_news_images/'.$id));
			
        	$this->_render_view( 'news/edit_info', $data );
        }
		else show_404();
     }


	 public function edit_job( $id ) {

        if( $data['job'] = $this->em->getRepository('models\Entities\CareerJob')->find($id) ) {
			
			// TODO server validation
			
			$data['job']->setName( $this->input->post('name') );
			
			$this->em->persist($data['job']);
			$this->em->flush();
			
			$data['message'] = '<p class="message_success">Sve izmene su uspešno izvršene!</p>';
			
        	$this->_render_view( 'career/edit_job', $data );
        }
		else show_404();
     }

	 public function edit_ad( $id ) {

        if( $data['ad'] = $this->em->getRepository('models\Entities\CareerAd')->find($id) ) {
			
			// TODO server validation

			$data['ad']->setCareerJobID( $this->input->post('career_job_id') );
			$data['ad']->setText( $this->input->post('page') );
			$data['ad']->setStatus( $this->input->post('active') );
			
			$this->em->persist($data['ad']);
			$this->em->flush();
			
			$data['message'] = '<p class="message_success">Sve izmene su uspešno izvršene!</p>';

			$data['job_name'] = $this->em->getRepository('models\Entities\CareerJob')->find($data['ad']->getCareerJobID())->getName();
			$data['jobs'] = $this->em->getRepository('models\Entities\CareerJob')->findAll();
			
			$data['tinymce'] = build_tinymce_js('page', 600, 700, NULL);
			$this->resources['js'][] = 'tiny_mce';
			$this->resources['js'][] = 'checkbox';
        	$this->_render_view( 'career/edit_ad', $data );
        }
		else show_404();
     }

     public function delete() {
		
		$id_list = explode( ',', $this->input->post('items') );
		
		$this->em->getRepository('models\Entities\CareerRecord')->deleteCareers($id_list);
		$this->output->set_output( TRUE );
	 }

	 public function delete_job() {
		
		$id_list = explode( ',', $this->input->post('items') );
		
		$this->em->getRepository('models\Entities\CareerJob')->deleteJobs($id_list);
		$this->output->set_output( TRUE );
	 }	 
	 
	 public function delete_ad() {
		
		$id_list = explode( ',', $this->input->post('items') );
		
		$this->em->getRepository('models\Entities\CareerAd')->deleteAds($id_list);
		$this->output->set_output( TRUE );
	 }
	      
     public function grid() {
     	 
		$valid_fields = array( 'registration_date', 'career_job_id', 'name', 'email', 'phone' );
         
 		$this->flexigrid->validate_post($this->gridParams['id'], 'registration_date', 'DESC', $valid_fields);
 		$criteria = $this->flexigrid->get_criteria();
 		$records = $this->em->getRepository('models\Entities\CareerRecord')->getCareers( $criteria );
         
 		$this->session->unset_userdata('edit_visited');
 		$this->output->set_header($this->config->item('json_header'));
 		$this->output->set_output($this->flexigrid->json_build($records['record_count'],$records['record_items']));
 		
     }
	 
	 public function jobs_grid() {
     	 
		$valid_fields = array( 'name' );
 		$this->flexigrid->validate_post($this->gridParams['id'], 'name', 'ASC', $valid_fields);
 		$criteria = $this->flexigrid->get_criteria();
 		$records = $this->em->getRepository('models\Entities\CareerJob')->getJobs( $criteria );
 		$this->session->unset_userdata('edit_visited');
 		$this->output->set_header($this->config->item('json_header'));
 		$this->output->set_output($this->flexigrid->json_build($records['record_count'],$records['record_items']));
     }

	 public function ads_grid() {
     	 
		$valid_fields = array( 'career_job_id', 'active' );
 		$this->flexigrid->validate_post($this->gridParams['id'], 'career_job_id', 'ASC', $valid_fields);
 		$criteria = $this->flexigrid->get_criteria();
 		$records = $this->em->getRepository('models\Entities\CareerAd')->getAds( $criteria );
 		$this->session->unset_userdata('edit_visited');
 		$this->output->set_header($this->config->item('json_header'));
 		$this->output->set_output($this->flexigrid->json_build($records['record_count'],$records['record_items']));
     }

	 public function change_status( $id ){
         
          $ad = $this->em->getRepository('models\Entities\CareerAd')->find($id);
		  $ad->getStatus() ? $ad->setStatus(0) : $ad->setStatus(1); 
          $this->em->persist($ad);
          $this->em->flush();

          $this->output->set_output($ad->getStatus());
     }
	 
	 private function create_thumb( $thumb = NULL ) {
	 	
		if( !$_FILES['thumb']['size'] ) return $thumb;
		
		$upload_config['encrypt_name'] 		= TRUE;
        $upload_config['upload_path'] 		= SERVER_IMAGE_PATH.'news/';
        $upload_config['allowed_types'] 	= 'gif|jpg|png';
        $upload_config['max_size']			= '2048';
        $upload_config['remove_spaces'] 	= TRUE;
		
		$this->load->library('upload');
        
        $this->upload->initialize($upload_config);
		
		if( $this->upload->do_upload('thumb') ) {
			
            $image_data = $this->upload->data();
			
			$resize_config['image_library'] 	= 'gd2';
			$resize_config['source_image']		= $image_data['full_path'];
			$resize_config['width']				= 212;
			$resize_config['height'] 			= 100;
			$resize_config['maintain_ratio']	= TRUE;
			$resize_config['master_dim']		= $image_data['image_width']/$image_data['image_height'] < $resize_config['width']/$resize_config['height'] ? 'width' : 'height';
			
			$this->load->library('image_lib', $resize_config);
			
			if ( $this->image_lib->resize() ) {
				
				if( $thumb ) unlink( SERVER_IMAGE_PATH.'news/'.$thumb );
				
				$this->image_lib->clear();
				
				$crop_config['image_library']	= 'gd2';
				$crop_config['source_image']	= $image_data['full_path'];
				$crop_config['width']			= 212;
				$crop_config['height'] 			= 100;
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
 
 /* End of file news.php */
 /* Location: ./system/applications/_backend/controllers/news.php */