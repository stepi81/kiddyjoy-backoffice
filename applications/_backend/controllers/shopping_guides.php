<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * ...
 * @author Marko Stepanovic [ Codeion ]
 */

 use models\Entities\ShoppingGuide\Guide;
 
 class Shopping_Guides extends MY_Controller {
     
     public $gridParams = array(
        'id'                   => 'productsGrid',
        'width'                 => 'auto',
        'height'                => 400,
        'rp'                    => 15,
        'rpOptions'             => '[10,15,20,25,40]',
        'pagestat'              => 'Prikaz: {from} do {to} / Ukupno: {total} vodiča.',
        'pagetext'                => 'Stranica',
        'outof'                    => 'od',
        'findtext'              => 'Pronađi',
        'procmsg'                => 'Obrada u toku, molimo sačekajte...',
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

        $this->gridParams['title'] = 'Pregled svih vesti';

        $colModel['title']  = array( 'Vodič', 200, TRUE, 'center', 1 );
        $colModel['status'] = array( 'Status', 50, TRUE, 'center', 0 );
        $colModel['actions'] = array( 'Detalji', 50, FALSE, 'center', 0 );

        $buttons[] = array('Novi vodič', 'add', 'grid_commands', site_url("shopping_guides/create"));
        $buttons[] = array('separator');
        $buttons[] = array('Obriši vodič', 'delete', 'grid_commands', site_url("shopping_guides/delete"));
        $buttons[] = array('separator');
        $buttons[] = array('Izaberi sve', 'select_all', 'grid_commands', '/select');
        $buttons[] = array('separator');
        $buttons[] = array('Poništi izbor', 'deselect_all', 'grid_commands', '/desel');
        $buttons[] = array('separator');

        if( $this->input->post('page') ) $this->gridParams['newp'] = $this->input->post('page');
        $data['grid'] = build_grid_js('grid', site_url("shopping_guides/grid"), $colModel, 'id', 'ASC', $this->gridParams, $buttons);

        $data['grid_title'] = "Vodiči";
        $this->_render_view( "master/grid_view", $data );
     }
     
     public function create() {
        
        $this->resources['js'][] = 'checkbox';
        $this->resources['css'][]='datepicker';
         
        $this->_render_view( 'shopping_guide/create_guide' );
     }
     
     public function details( $id ) {

        if( $data['guide'] = $this->em->getRepository('models\Entities\ShoppingGuide\Guide')->find($id) ) {
            
            $this->resources['css'][] = 'plupload';
            
            $this->resources['js'][] = 'checkbox';
            $this->resources['js'][] = 'tiny_mce';
            $this->resources['js'][] = 'plupload_full';
            $this->resources['js'][] = 'plupload_queue';
            
            $data['plupload'] = build_plupload_js( site_url('upload/shopping_guide/'.$data['guide']->getID()) );
            $data['tinymce'] = build_tinymce_js('description', 600, 700, site_url('proxy/get_shopping_guide_images/'.$id));
            
            $this->_render_view( 'shopping_guide/edit_guide', $data );
        }
        else show_404();
     }
     
     public function save() {
         
        $this->resources['js'][] = 'checkbox';
        // TODO server validation
        
        $data['guide'] = new Guide();

        $data['guide']->setTitle( $this->input->post('title') );
    
        $this->em->persist($data['guide']);
        $this->em->flush();
        
        $this->resources['css'][] = 'plupload';
        
        $this->resources['js'][] = 'tiny_mce';
        $this->resources['js'][] = 'plupload_full';
        $this->resources['js'][] = 'plupload_queue';
        
        $data['plupload'] = build_plupload_js( site_url('upload/shopping_guide/'.$data['guide']->getID()) );
        $data['tinymce'] = build_tinymce_js('description', 600, 700, site_url('proxy/get_shopping_guide_images/'.$data['guide']->getID()));
        
        $this->_render_view( 'shopping_guide/edit_guide', $data );
     }
     
     public function edit( $id ) {

        if( $data['guide'] = $this->em->getRepository('models\Entities\ShoppingGuide\Guide')->find($id) ) {
            
            // TODO server validation

            $data['guide']->setStatus( $this->input->post('status') );
            $data['guide']->setTitle( $this->input->post('title') );
            $data['guide']->setDescription( $this->input->post('description') );
            
            $this->em->persist($data['guide']);
            $this->em->flush();
            
            $data['message'] = '<p class="message_success">Sve izmene su uspešno izvršene!</p>';
            
            $this->resources['css'][] = 'plupload';

            $this->resources['js'][] = 'checkbox';
            $this->resources['js'][] = 'tiny_mce';
            $this->resources['js'][] = 'plupload_full';
            $this->resources['js'][] = 'plupload_queue';
            
            $data['plupload'] = build_plupload_js( site_url('upload/shopping_guide/'.$data['guide']->getID()) );
            $data['tinymce'] = build_tinymce_js('description', 600, 700, site_url('proxy/get_shopping_guide_images/'.$id));
            
            $this->_render_view( 'shopping_guide/edit_guide', $data );
        }
        else show_404();
     }

     public function delete() {
        
        $id_list = explode( ',', $this->input->post('items') );
        
        $this->em->getRepository('models\Entities\ShoppingGuide\Guide')->deleteGuides($id_list);
        $this->output->set_output( TRUE );
     }
     
     public function grid() {
          
        $valid_fields = array('id', 'title', 'status');
         
        $this->flexigrid->validate_post($this->gridParams['id'], 'id', 'DESC', $valid_fields);
        $criteria = $this->flexigrid->get_criteria();
        $records = $this->em->getRepository('models\Entities\ShoppingGuide\Guide')->getGuides( $criteria );
         
        $this->session->unset_userdata('edit_visited');
        $this->output->set_header($this->config->item('json_header'));
        $this->output->set_output($this->flexigrid->json_build($records['record_count'],$records['record_items']));
     }
     
     public function change_status( $id ){
         
          $guide = $this->em->getRepository('models\Entities\ShoppingGuide\Guide')->find($id);
          $guide->getStatus() ? $guide->setStatus(0) : $guide->setStatus(1); 
          
          $this->em->flush();
          $this->output->set_output($guide->getStatus());
     }
 }
 
 /* End of file shopping_guides.php */
 /* Location: ./system/applications/_backend/controllers/shopping_guides.php */