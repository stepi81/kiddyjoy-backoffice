<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */

 use models\Entities\DailyPromotion;
 use models\Entities\Product;
 
 class Daily_Promotions extends MY_Controller {
     
     public $gridParams = array(
        'id'                   => 'productsGrid',
        'width'                 => 'auto',
        'height'                => 400,
        'rp'                    => 15,
        'rpOptions'             => '[10,15,20,25,40]',
        'pagestat'              => 'Prikaz: {from} do {to} / Ukupno: {total} dnevnih proizvoda.',
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

        $this->gridParams['title'] = 'Pregled svih dnevnih proizvoda';
        
        $colModel['product']  = array( 'Proizvod', 234, FALSE, 'center', 0 );
        $colModel['start_date']  = array( 'Početak', 100, TRUE, 'center', 1 );
        $colModel['end_date']  = array( 'Kraj', 100, TRUE, 'center', 1 );
        $colModel['price']  = array( 'Cena', 100, TRUE, 'center', 1 ); 
        $colModel['status'] = array( 'Status', 50, TRUE, 'center', 0 );
        $colModel['actions'] = array( 'Detalji', 50, FALSE, 'center', 0 );

        $buttons[] = array('Nov proizvod', 'add', 'grid_commands', site_url("daily_promotions/create"));
        $buttons[] = array('separator');
        $buttons[] = array('Obriši proizvod', 'delete', 'grid_commands', site_url("daily_promotions/delete"));
        $buttons[] = array('separator');
        $buttons[] = array('Izaberi sve', 'select_all', 'grid_commands', '/select');
        $buttons[] = array('separator');
        $buttons[] = array('Poništi izbor', 'deselect_all', 'grid_commands', '/desel');
        $buttons[] = array('separator');

        if( $this->input->post('page') ) $this->gridParams['newp'] = $this->input->post('page');
        $data['grid'] = build_grid_js('grid', site_url("daily_promotions/grid"), $colModel, 'id', 'ASC', $this->gridParams, $buttons);

        $data['grid_title'] = "Dnevni proizvodi";
        $this->_render_view( "master/grid_view", $data );
     }
     
     public function create() {
        
        $this->resources['js'][] = 'checkbox';
        $this->resources['css'][]='datepicker';
         
        $this->_render_view( 'promotion/create_daily_promotion' );
     }
     
     public function details( $id ) {

        if( $data['promotion'] = $this->em->getRepository('models\Entities\DailyPromotion')->find($id) ) {

            $this->resources['css'][]='datepicker';
            
            $this->resources['js'][] = 'checkbox';
            
            $this->_render_view( 'promotion/edit_daily_promotion', $data );
        }
        else show_404();
     }
     
     public function save() {
         
        $this->resources['js'][] = 'checkbox';
        $this->resources['css'][]='datepicker';
        // TODO server validation
        
        $data['promotion'] = new DailyPromotion();
        
        if( $this->em->getRepository('models\Entities\Product')->find( $this->input->post('product') )  ) {
            $data['promotion']->setProduct($this->em->getReference('models\Entities\Product', $this->input->post('product')));    
            $data['promotion']->setStartDate(new \DateTime(date( 'Y-m-d', strtotime( $this->input->post('start_date')))));
            $data['promotion']->setEndDate(new \DateTime(date( 'Y-m-d', strtotime( $this->input->post('end_date')))));
            $data['promotion']->setStatus( $this->input->post('status') );
        
            $this->em->persist($data['promotion']);
            $this->em->flush();
            
            $data['message'] = '<p class="message_success">Dnevna promocija je uspešno snimljena!</p>'; 
        } else {
            $data['message'] = '<p class="message_error">Proizvod kloji sto odabrali nije u bazi!</p>';    
        }
        $this->_render_view( 'promotion/create_daily_promotion', $data );
     }
     
     public function edit( $id ) {

        if( $data['promotion'] = $this->em->getRepository('models\Entities\DailyPromotion')->find($id) ) {
            
            // TODO server validation
            if( $this->em->getRepository('models\Entities\Product')->find( $this->input->post('product') )  ) {
                $data['promotion']->setProduct($this->em->getReference('models\Entities\Product', $this->input->post('product')));    
                $data['promotion']->setStartDate(new \DateTime(date( 'Y-m-d', strtotime( $this->input->post('start_date')))));
                $data['promotion']->setEndDate(new \DateTime(date( 'Y-m-d', strtotime( $this->input->post('end_date')))));
                $data['promotion']->setStatus( $this->input->post('status') );
                
                $this->em->persist($data['promotion']);
                $this->em->flush();
                
                $data['message'] = '<p class="message_success">Sve izmene su uspešno izvršene!</p>';    
            } else {
                $data['message'] = '<p class="message_error">Proizvod kloji sto odabrali nije u bazi!</p>';    
            }
 
            $this->resources['css'][]='datepicker';
            $this->resources['js'][] = 'checkbox';
            
            $this->_render_view( 'promotion/edit_daily_promotion', $data );
        }
        else show_404();
     }

     public function delete() {
        
        $id_list = explode( ',', $this->input->post('items') );
        
        $this->em->getRepository('models\Entities\DailyPromotion')->deletePromotions($id_list);
        $this->output->set_output( TRUE );
     }
     
     public function grid() {
          
        $valid_fields = array('product', 'start_date', 'end_date', 'price', 'status');
         
        $this->flexigrid->validate_post($this->gridParams['id'], 'start_date', 'DESC', $valid_fields);
        $criteria = $this->flexigrid->get_criteria();
        $records = $this->em->getRepository('models\Entities\DailyPromotion')->getPromotions( $criteria );
         
        $this->session->unset_userdata('edit_visited');
        $this->output->set_header($this->config->item('json_header'));
        $this->output->set_output($this->flexigrid->json_build($records['record_count'],$records['record_items']));
     }
     
     public function change_status( $id ){
         
        $promotion = $this->em->getRepository('models\Entities\DailyPromotion')->find($id);
        $promotion->getStatus() ? $promotion->setStatus(0) : $promotion->setStatus(1); 
          
        $this->em->flush();
        $this->output->set_output($promotion->getStatus());
     }
 }
 
 /* End of file daily_promotions.php */
 /* Location: ./system/applications/_backend/controllers/daily_promotions.php */