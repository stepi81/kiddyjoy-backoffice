<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ...
 * @author Ivan Despic [ Codeion ]
 */

 use models\Entities\Product\Warranty; 
 
class Warranties extends MY_Controller {

     public $gridParams=array(
        'id'                    => 'warrantiesGrid',
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
        
        $this->gridParams['title']='Lista garancija';
         
        $colModel['duration'] = array( 'Trajanje', 120, TRUE, 'center', 1 );
        $colModel['position'] = array( 'Pozicija', 120, TRUE, 'center', 1 );
		$colModel['actions'] = array('Detalji', 50, FALSE, 'center', 0);

        $buttons[] = array( 'Nova garancija', 'add','grid_commands', site_url("settings/warranties/create"));
        $buttons[] = array('separator');
        $buttons[] = array( 'Obriši garanciju', 'delete','grid_commands', site_url("settings/warranties/delete"));
        $buttons[] = array('separator');
        $buttons[] = array('Izaberi sve', 'select_all', 'grid_commands', '/select');
		$buttons[] = array('separator');
        $buttons[] = array('Poništi izbor', 'deselect_all', 'grid_commands', '/desel');
        $buttons[] = array('separator');

        if( $this->input->post('page') ) $this->gridParams['newp'] = $this->input->post('page');
        $data['grid'] = build_grid_js('grid', site_url("settings/warranties/grid"), $colModel, 'id', 'DESC', $this->gridParams, $buttons);

        $data['grid_title'] = "Garancije";
        $this->_render_view( "master/grid_view", $data );
     }

     public function grid() {
          
         $valid_fields = array('position', 'duration');
         
         $this->flexigrid->validate_post($this->gridParams['id'], 'position', 'ASC', $valid_fields);
         $criteria = $this->flexigrid->get_criteria();
         $records = $this->em->getRepository('models\Entities\Product\Warranty')->getWarranties( $criteria );
         
         $this->session->unset_userdata('edit_visited');
         $this->output->set_header($this->config->item('json_header'));
         $this->output->set_output($this->flexigrid->json_build($records['record_count'],$records['record_items']));
     }
	 
	 public function create() { 
        
        $this->_render_view( 'settings/warranty/create_warranty' );
     }
     
     public function save(){
        
        $position = $this->input->post('position');

        $answers = $this->em->getRepository('models\Entities\Product\Warranty')->findAll();
        $max = $this->em->getRepository('models\Entities\Product\Warranty')->getMaxWarrantyPosition(); 
         
        $maxPosition = $max[0][1];

        if ($position) {
            if ($position >= $maxPosition) {
                $position = $maxPosition + 1;
            } else {
                foreach ($answers as $ad) {
                    $adNowPosition = $ad->getPosition();
                    if ($position <= $adNowPosition) {
                        $ad->setPosition($adNowPosition + 1);
                        $this->em->persist($ad);
                        $this->em->flush();
                    }
                }
            }
        } else {
            $position=$maxPosition + 1;
        }
         
        $answer = new Warranty();
        $answer->setName($this->input->post('duration'));
        $answer->setPosition($position);
        
        $this->em->persist($answer);
        $this->em->flush();
        
        $data['message']='Done';

        redirect( 'settings/warranties/listing' );
     }
     
     public function delete() {
        
     $id_list=explode(',', $this->input->post('items'));

        foreach ($id_list as $id) {
            $ad = $this->em->getRepository('models\Entities\Product\Warranty')->find($id);
            $ads = $this->em->getRepository('models\Entities\Product\Warranty')->findAll();//->getAnswersByQuestion($ad->getAnswer()->getID());
            foreach ($ads as $ad_data) {
                $oldPosition=$ad_data->getPosition();
                if ($ad->getPosition() < $ad_data->getPosition()) {
                    $ad_data->setPosition($oldPosition - 1);
                    $this->em->persist($ad_data);
                    $this->em->flush();
                }
            }
        }
        $this->em->getRepository('models\Entities\Product\Warranty')->deleteWarranties($id_list);
        $this->output->set_output(TRUE);
    }
     
    public function details($warranty_id){
         
        $data['edit_title'] = "Izmeni garanciju";

        $data['warranty'] = $this->em->getRepository('models\Entities\Product\Warranty')->find($warranty_id);

        $this->_render_view( 'settings/warranty/edit_warranty', $data );
    }

    public function edit($warranty_id){

        if( $data['warranty'] = $this->em->getRepository('models\Entities\Product\Warranty')->find($warranty_id) ) {
                  
            $data['warranty']->setName( $this->input->post('duration') );
             
            $warranty = $this->em->getRepository('models\Entities\Product\Warranty')->findAll();
            $max = $this->em->getRepository('models\Entities\Product\Warranty')->getMaxWarrantyPosition();  
                              
            $old_position = $data['warranty']->getPosition();  
            $position = $this->input->post('position');
            $maxPosition=$max[0][1];

            if ($position <= $old_position) {
                foreach ($warranty as $ad) {
                    if ($ad->getPosition() >= $position && $ad->getPosition() < $old_position)
                        $ad->setPosition($ad->getPosition() + 1);
                    $this->em->persist($ad);
                    $this->em->flush();
                }
            } else {
                if ($position >= $maxPosition) {
                    $position=$maxPosition;
                }
                foreach ($warranty as $ad) {
                    if ($ad->getPosition() <= $position && $ad->getPosition() > $old_position)
                        $ad->setPosition($ad->getPosition() - 1);
                    $this->em->persist($ad);
                    $this->em->flush();
                }
            }
            $data['warranty']->setPosition($position);
            $this->em->persist($data['warranty']);
            $this->em->flush();
            
            $data['message'] = '<p class="message_success">Sve izmene su uspešno izvršene!</p>';

            $this->_render_view( 'settings/warranty/edit_warranty', $data );
            
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