<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * ...
 * @author Marko Stepanovic [ Codeion ]
 */

 use models\Entities\Benchmark;
 
 class Benchmarks extends MY_Controller {
     
     public $gridParams = array(
        'id'                    => 'benchmarksGrid',
        'width'                 => 'auto',
        'height'                => 400,
        'rp'                    => 15,
        'rpOptions'             => '[10,15,20,25,40]',
        'pagestat'              => 'Prikaz: {from} do {to} / Ukupno: {total} testova.',
        'pagetext'              => 'Stranica',
        'outof'                 => 'od',
        'findtext'              => 'Pronađi',
        'procmsg'               => 'Obrada u toku, molimo sačekajte...',
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

        $this->gridParams['title'] = 'Pregled svih testova';
        
        $colModel['benchmark_date']  = array( 'Datum testiranja', 120, FALSE, 'center', 0 );
        $colModel['title']  = array( 'Naziv', 200, TRUE, 'center', 1 );
        $colModel['category']  = array( 'Kategorija', 200, TRUE, 'center', 1 );
        $colModel['status'] = array( 'Status', 50, TRUE, 'center', 0 );
        $colModel['actions'] = array( 'Detalji', 50, FALSE, 'center', 0 );

        $buttons[] = array('Novi test', 'add', 'grid_commands', site_url("benchmarks/create"));
        $buttons[] = array('separator');
        $buttons[] = array('Obriši test', 'delete', 'grid_commands', site_url("benchmarks/delete"));
        $buttons[] = array('separator');
        $buttons[] = array('Izaberi sve', 'select_all', 'grid_commands', '/select');
        $buttons[] = array('separator');
        $buttons[] = array('Poništi izbor', 'deselect_all', 'grid_commands', '/desel');
        $buttons[] = array('separator');

        if( $this->input->post('description') ) $this->gridParams['newp'] = $this->input->post('description');
        $data['grid'] = build_grid_js('grid', site_url("benchmarks/grid"), $colModel, 'id', 'ASC', $this->gridParams, $buttons);

        $data['grid_title'] = "Testovi";
        $this->_render_view( "master/grid_view", $data );
     }
     
     public function create() {
        
        $this->resources['js'][] = 'checkbox';
        $data['categories'] = $this->em->getRepository('models\Entities\Benchmark\Category')->findAll();
        //$this->resources['css'][] = 'datepicker';
         
        $this->_render_view( 'benchmark/create_benchmark', $data );
     }
     
     public function details( $id ) {

        if( $data['benchmark'] = $this->em->getRepository('models\Entities\Benchmark')->find($id) ) {
            $data['categories'] = $this->em->getRepository('models\Entities\Benchmark\Category')->findAll();
            $this->resources['css'][] = 'plupload';
            
            $this->resources['js'][] = 'checkbox';
            $this->resources['js'][] = 'tiny_mce';
            $this->resources['js'][] = 'plupload_full';
            $this->resources['js'][] = 'plupload_queue';
            
            $data['plupload'] = build_plupload_js( site_url('upload/benchmark/'.$data['benchmark']->getID()) );
            $data['tinymce'] = build_tinymce_js('description', 600, 700, site_url('proxy/get_benchmark_images/'.$id));
            
            $this->_render_view( 'benchmark/edit_benchmark', $data );
        }
        else show_404();
     }
     
     public function save() {
         
        $this->resources['js'][] = 'checkbox';
       
        // TODO server validation
        
        $data['benchmark'] = new Benchmark();
        
        if( $thumb = $this->create_thumb()) {
            if ($this->input->post('product_id') != '') {
                if ($this->em->getRepository('models\Entities\Product')->findOneBy(array('id' => $this->input->post('product_id')))){
                    $data['benchmark']->setProduct($this->em->getReference('models\Entities\Product', $this->input->post('product_id')));
                } else {
                    $data['message'] = '<p class="message_error">Došlo je do greške! Proizvod ne postoji u bazi.</p>';
                }
            } 
            $data['benchmark']->setDate('temp');
            $data['benchmark']->setCategory( $this->em->getReference('models\Entities\Benchmark\Category', $this->input->post('test_category')));
            $data['benchmark']->setTitle( $this->input->post('title') );
            $data['benchmark']->setShortInfo( $this->input->post('short_info') );
            $data['benchmark']->setThumb( $thumb );
            $data['benchmark']->setStatus( $this->input->post('status'));
            
            $this->em->persist($data['benchmark']);
            $this->em->flush();
            
            $this->resources['css'][] = 'plupload';
            
            $this->resources['js'][] = 'tiny_mce';
            $this->resources['js'][] = 'plupload_full';
            $this->resources['js'][] = 'plupload_queue';
            
            $data['plupload'] = build_plupload_js( site_url('upload/benchmark/'.$data['benchmark']->getID()) );
            $data['tinymce'] = build_tinymce_js('description', 600, 700, site_url('proxy/get_benchmark_images/'.$data['benchmark']->getID()));
            $data['categories'] = $this->em->getRepository('models\Entities\Benchmark\Category')->findAll();
            $this->_render_view( 'benchmark/edit_benchmark', $data );
        }
        else {
            $data['message'] = '<p class="message_error">Došlo je do greške! Molimo Vas proverite unete parametre.</p>';
            $data['categories'] = $this->em->getRepository('models\Entities\Benchmark\Category')->findAll();
            $this->_render_view( 'benchmark/create_benchmark', $data );
        }
     }
     
     public function edit( $id ) {

        if( $data['benchmark'] = $this->em->getRepository('models\Entities\Benchmark')->find($id) ) {
            
            // TODO server validation
            
            if( $thumb = $this->create_thumb($data['benchmark']->getThumb()) ) {
                
                if ($this->input->post('product_id') != '') {
                    if ($this->em->getRepository('models\Entities\Product')->findOneBy(array('id' => $this->input->post('product_id')))){
                        $data['benchmark']->setProduct($this->em->getReference('models\Entities\Product', $this->input->post('product_id')));
                        $data['message'] = '<p class="message_success">Proizvod je vezan za test!';
                    } else{
                        $data['message'] = '<p class="message_success">Uneti ID proizvoda ne postoji u bazi!';
                    }
                } else {
                    $data['benchmark']->setProduct(NULL);
                    $data['message'] = '<p class="message_success">Nijedan proizvod nije vezan za test.';
                }
                
                $data['benchmark']->setCategory( $this->em->getReference('models\Entities\Benchmark\Category', $this->input->post('test_category')));
                $data['benchmark']->setTitle( $this->input->post('title') );
                $data['benchmark']->setShortInfo( $this->input->post('short_info') );
                $data['benchmark']->setStatus( $this->input->post('status'));
                $data['benchmark']->setDescription( $this->input->post('description'));
                $data['benchmark']->setThumb( $thumb );
                
                $this->em->persist($data['benchmark']);
                $this->em->flush();
                
                $data['message'] .= ' Ostale izmene su uspešno izvršene!</p>';
            }
            else {
                $data['message'] = '<p class="message_error">Došlo je do greške! Molimo Vas proverite unete parametre.</p>';
            }
            
            $this->resources['css'][] = 'plupload';
            $this->resources['js'][] = 'checkbox';
            $this->resources['js'][] = 'tiny_mce';
            $this->resources['js'][] = 'plupload_full';
            $this->resources['js'][] = 'plupload_queue';
            
            $data['plupload'] = build_plupload_js( site_url('upload/benchmark/'.$data['benchmark']->getID()) );
            $data['tinymce'] = build_tinymce_js('description', 600, 700, site_url('proxy/get_benchmark_images/'.$id));
            $data['categories'] = $this->em->getRepository('models\Entities\Benchmark\Category')->findAll();
            
            $this->_render_view( 'benchmark/edit_benchmark', $data );
        }
        else show_404();
     }

     public function delete() {
        
        $id_list = explode( ',', $this->input->post('items') );
        
        $this->em->getRepository('models\Entities\Benchmark')->deleteBenchmark($id_list);
        $this->output->set_output( TRUE );
     }
     
     public function grid() {
          
        $valid_fields = array('benchmark_date', 'title', 'status');
         
         $this->flexigrid->validate_post($this->gridParams['id'], 'benchmark_date', 'DESC', $valid_fields);
         $criteria = $this->flexigrid->get_criteria();
         $records = $this->em->getRepository('models\Entities\Benchmark')->getBenchmarks( $criteria );
         
         $this->session->unset_userdata('edit_visited');
         $this->output->set_header($this->config->item('json_header'));
         $this->output->set_output($this->flexigrid->json_build($records['record_count'],$records['record_items']));
     }
     
     public function change_status( $id ){
         
          $benchmark = $this->em->getRepository('models\Entities\Benchmark')->find($id);
          $benchmark->getStatus() ? $benchmark->setStatus(0) : $benchmark->setStatus(1); 
          
          $this->em->flush();

          $this->output->set_output($benchmark->getStatus());
     }
     
     private function create_thumb( $thumb = NULL ) {
         
        if( !$_FILES['thumb']['size'] ) return $thumb;
        
        $upload_config['encrypt_name']      = TRUE;
        $upload_config['upload_path']       = SERVER_IMAGE_PATH.'benchmark/';
        $upload_config['allowed_types']     = 'gif|jpg|png';
        $upload_config['max_size']          = '2048';
        $upload_config['remove_spaces']     = TRUE;
        
        $this->load->library('upload');
        
        $this->upload->initialize($upload_config);
        
        if( $this->upload->do_upload('thumb') ) {
            
            $image_data = $this->upload->data();
            
            $resize_config['image_library']     = 'gd2';
            $resize_config['source_image']      = $image_data['full_path'];
            $resize_config['width']             = 212;
            $resize_config['height']            = 100;
            $resize_config['maintain_ratio']    = TRUE;
            $resize_config['master_dim']        = $image_data['image_width']/$image_data['image_height'] < $resize_config['width']/$resize_config['height'] ? 'width' : 'height';
            
            $this->load->library('image_lib', $resize_config);
            
            if ( $this->image_lib->resize() ) {
                
                if( $thumb ) unlink( SERVER_IMAGE_PATH.'benchmark/'.$thumb );
                
                $this->image_lib->clear();
                
                $crop_config['image_library']    = 'gd2';
                $crop_config['source_image']     = $image_data['full_path'];
                $crop_config['width']            = 212;
                $crop_config['height']           = 100;
                $crop_config['maintain_ratio']   = FALSE;
                
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