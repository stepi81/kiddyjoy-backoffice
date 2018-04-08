<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ...
 * @author Marko Stepanovic [ Codeion ]
 */

 use models\Entities\PostalCode; 
 
class Postal_Codes extends MY_Controller {

     public $gridParams=array(
        'id'                    => 'postalCodeGrid',
        'width'                 => 'auto',
        'height'                => 400,
        'rp'                    => 15,
        'rpOptions'             => '[10,15,20,25,40]',
        'blockOpacity'          => 0.5,
        'showTableToggleBtn'    => true
     );
     
     public function __construct() {
         
        parent::__construct();
        
        $this->load->helper('flexigrid');
        $this->load->library('Flexigrid');
        
        $this->resources['css']=array();
        $this->resources['js']=array();
     }
     
     public function listing() {
            
        $this->resources['css'][]='flexigrid';
        $this->resources['js'][]='flexigrid';
        
        $this->gridParams['title']='Lista poštanskih brojeva';
         
        $colModel['postal_code'] = array( 'Poštanski broj', 120, TRUE, 'center', 1 );
        $colModel['city'] = array( 'Grad', 120, TRUE, 'center', 1 );
        $colModel['longitude'] = array( 'Longitude', 120, TRUE, 'center', 1 );
        $colModel['latitude'] = array( 'Latitude', 120, TRUE, 'center', 1 );
        $colModel['actions'] = array('Detalji', 50, FALSE, 'center', 0);

        if( $this->input->post('page') ) $this->gridParams['newp'] = $this->input->post('page');
        $data['grid'] = build_grid_js('grid', site_url("settings/postal_codes/grid"), $colModel, 'id', 'DESC', $this->gridParams);

        $data['grid_title'] = "Poštanski brojevi";
        $this->_render_view( "master/grid_view", $data );
     }

     public function grid() {
          
         $valid_fields = array( 'postal_code', 'city', 'longitude', 'latitude');
         
         $this->flexigrid->validate_post($this->gridParams['id'], 'city', 'ASC', $valid_fields);
         $criteria = $this->flexigrid->get_criteria();
         $records = $this->em->getRepository('models\Entities\PostalCode')->getPostalCodes( $criteria );
         
         $this->session->unset_userdata('edit_visited');
         $this->output->set_header($this->config->item('json_header'));
         $this->output->set_output($this->flexigrid->json_build($records['record_count'],$records['record_items']));
     }
     
     public function details($postal_code_id){
         
         $data['edit_title'] = "Izmeni poštanski broj";
         
         $data['postal_code'] = $this->em->getRepository('models\Entities\PostalCode')->find($postal_code_id);
         
         $this->_render_view( 'settings/postal_code/edit_postal_code', $data );
     }

     public function edit($postal_code_id){
        
        if( $data['postal_code'] = $this->em->getRepository('models\Entities\PostalCode')->find($postal_code_id) ) {
                
            $data['postal_code']->setLongitude( $this->input->post('longitude') );
            $data['postal_code']->setLatitude( $this->input->post('latitude') );

            $this->em->persist($data['postal_code']);
            $this->em->flush();
            
            $data['message'] = '<p class="message_success">Sve izmene su uspešno izvršene!</p>';

            $this->_render_view( 'settings/postal_code/edit_postal_code', $data );
            
        } else show_404();
     }

     public function set_listing_page($page){
         
        $this->session->set_userdata('current_listing', $page);
        $this->session->set_userdata('current_controller', $this->controller);
        $this->session->unset_userdata('qtype');
        $this->session->unset_userdata('query');
    }
}
 /* End of file warranties.php */
 /* Location: ./system/applications/_backend/controllers/settings/warranties.php */