<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * ...
 * @author Marko Stepanovic [ Codeion ]
 */

use models\Entities\Product\Color;

class Colors extends MY_Controller {

    public $gridParams = array(
	    'id'                    => 'colorGrid',
	    'width' 				=> 'auto', 
	    'height' 				=> 400, 
	    'rp' 					=> 15, 
	    'rpOtions' 				=> '[10,15,20,25,40]', 
	    'pagestat' 				=> 'Prikaz: {from} do {to} / Ukupno: {total} boja.', 
	    'pagetext' 				=> 'Stranica', 
	    'outof' 				=> 'od', 
	    'findtext' 				=> 'Pronađi', 
	    'procmsg' 				=> 'Obrada u toku, molimo sačekajte...', 
	    'blockOpacity' 			=> 0.5, 
	    'showTableToggleBtn' 	=> true
	);

    public function __construct() {

        parent::__construct();

        $this->load->helper('flexigrid');
        $this->load->helper('upload');
        $this->load->helper('tinymce');

        $this->load->library('Flexigrid');

        $this->resources['css'] = array();
        $this->resources['js'] = array('checkbox');
    }

    public function listing() {

        $this->resources['css'][] = 'flexigrid';
        $this->resources['js'][] = 'flexigrid';
       
        $this->gridParams['title']='Pregled boja';
		
		$colModel['position'] = array('Pozicija', 50, TRUE, 'center', 1);
        $colModel['name'] = array('Ime', 120, TRUE, 'center', 1);
		$colModel['status'] = array('Status', 50, TRUE, 'center', 0);
        $colModel['actions'] = array('Detalji', 50, FALSE, 'center', 0);

        $buttons[] = array('Nova boja', 'add', 'grid_commands', site_url("settings/colors/create"));
        $buttons[] = array('separator');
        $buttons[] = array('Obriši boje', 'delete', 'grid_commands', site_url("settings/colors/delete"));
        $buttons[] = array('separator');
        $buttons[] = array('Izaberi sve', 'select_all', 'grid_commands', '/select');
		$buttons[] = array('separator');
        $buttons[] = array('Poništi izbor', 'deselect_all', 'grid_commands', '/desel');
        $buttons[] = array('separator');
        
        if( $this->input->post('page') ) $this->gridParams['newp'] = $this->input->post('page');
       
        $data['grid'] = build_grid_js('grid', site_url("settings/colors/grid"), $colModel, 'position', 'ASC', $this->gridParams, $buttons);

        $data['grid_title'] = "Boje";
        $this->_render_view("master/grid_view", $data);
    }

	public function grid() {

        $valid_fields = array('id', 'name', 'position', 'status');

        $this->flexigrid->validate_post($this->gridParams['id'], 'position', 'ASC', $valid_fields);
        $criteria = $this->flexigrid->get_criteria();
        $records = $this->em->getRepository('models\Entities\Product\Color')->getColors($criteria);

        $this->session->unset_userdata('edit_visited');
        $this->output->set_header($this->config->item('json_header'));
        $this->output->set_output($this->flexigrid->json_build($records['record_count'], $records['record_items']));
    }

	public function create() {

        $this->_render_view('settings/color/create_color');
    }

    public function save() {

		$data['color'] = new Color();

        $position = $this->input->post('position');

        $maxColor = $this->em->getRepository('models\Entities\Product\Color')->getMaxPosition();
        $colors = $this->em->getRepository('models\Entities\Product\Color')->getAllColors();

        $maxPosition = $maxColor[0][1];

        if ($position) {
            if ($position >= $maxPosition) {
                $position = $maxPosition + 1;
            } else {
                foreach ($colors as $color) {
                    $newPosition = $color->getPosition();
                    if ($position <= $adNowPosition) {
                        $color->setPosition($newPosition + 1);
                        $this->em->persist($color);
                        $this->em->flush();
                    }
                }
            }
        } else {
            $position = $maxPosition + 1;
        }
		
		$data['color']->setName( $this->input->post('title') );
		$data['color']->setCode( $this->input->post('code') );
		$data['color']->setStatus( $this->input->post('status') );
		$data['color']->setPosition( $position );

		$this->em->persist($data['color']);
		$this->em->flush();
		
		$data['message'] = '<p class="message_success">Nova boja je uspešno postavljena!</p>';

		$this->_render_view('settings/color/create_color', $data);
        
    }

	public function details($id) {

        if ($data['color'] = $this->em->getRepository('models\Entities\Product\Color')->find($id)) {
        	
            $this->_render_view('settings/color/edit_color', $data);
			
        } else show_404();
    }
    
    public function edit( $id ) {

        if ($data['color'] = $this->em->getRepository('models\Entities\Product\Color')->find($id)) {
        	
            $position = $this->input->post('position');
            $old_position = $data['color']->getPosition();

            $maxColor = $this->em->getRepository('models\Entities\Product\Color')->getMaxPosition();
            $colors = $this->em->getRepository('models\Entities\Product\Color')->getAllColors();

            $maxPosition = $maxColor[0][1];

            if ($position <= $old_position) {
                foreach ($colors as $color) {
                    if ($color->getPosition() >= $position && $color->getPosition() < $old_position)
                        $color->setPosition($color->getPosition() + 1);
	                    $this->em->persist($color);
	                    $this->em->flush();
                }
            } else {
                if ($position >= $maxPosition) {
                    $position = $maxPosition;
                }
                foreach ($colors as $color) {
                    if ($color->getPosition() <= $position && $color->getPosition() > $old_position)
                        $color->setPosition($color->getPosition() - 1);
	                    $this->em->persist($color);
	                    $this->em->flush();
                }
            }
				
			$data['color']->setName($this->input->post('name'));
			
			$data['color']->setName( $this->input->post('title') );
			$data['color']->setCode( $this->input->post('code') );
			$data['color']->setStatus( $this->input->post('status') );
			$data['color']->setPosition( $position );
			
			$this->em->persist($data['color']);
			$this->em->flush();
			
			$data['message'] = '<p class="message_success">Sve izmene su uspešno izvršene!</p>';

        	$this->_render_view('settings/color/edit_color', $data);
         }
         else show_404();
    }

	public function change_status( $id ) {

        $color = $this->em->getRepository('models\Entities\Product\Color')->find($id);
        $color->getStatus() ? $color->setStatus(0) : $color->setStatus(1);

        $this->em->flush();

        $this->output->set_output($color->getStatus());
    }

    public function delete() {
        
        $id_list = explode( ',', $this->input->post('items') );
		
		foreach ($id_list as $id) {
            $color = $this->em->getRepository('models\Entities\Product\Color')->find($id);
            $colors = $this->em->getRepository('models\Entities\Product\Color')->getAllColors();
            foreach ($colors as $color_data) {
                $oldPosition = $color_data->getPosition();
                if ($color->getPosition() < $color_data->getPosition()) {
                    $color_data->setPosition($oldPosition - 1);
                    $this->em->persist($color_data);
                    $this->em->flush();
                }
            }
        }
        
        $this->em->getRepository('models\Entities\Product\Color')->deleteColors($id_list);
        $this->output->set_output( TRUE );
    }

}

/* End of file stickers.php */
/* Location: ./system/applications/_backend/controllers/settings/sstickers.php */
