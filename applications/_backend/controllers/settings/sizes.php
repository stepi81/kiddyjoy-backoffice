<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * ...
 * @author Marko Stepanovic [ Codeion ]
 */

use models\Entities\Product\Size;

class Sizes extends MY_Controller {

    public $gridParams = array(
	    'id'                    => 'sizeGrid',
	    'width' 				=> 'auto', 
	    'height' 				=> 400, 
	    'rp' 					=> 15, 
	    'rpOtions' 				=> '[10,15,20,25,40]', 
	    'pagestat' 				=> 'Prikaz: {from} do {to} / Ukupno: {total} velicina.', 
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

    public function listing($subcategory_id) {

        $this->resources['css'][] = 'flexigrid';
        $this->resources['js'][] = 'flexigrid';
       
        $this->gridParams['title']='Pregled veličina';
		
		$colModel['position'] = array('Pozicija', 50, TRUE, 'center', 1);
        $colModel['name'] = array('Ime', 120, TRUE, 'center', 1);
		$colModel['status'] = array('Status', 50, TRUE, 'center', 0);
        $colModel['actions'] = array('Detalji', 50, FALSE, 'center', 0);

        $buttons[] = array('Nova veličina', 'add', 'grid_commands', site_url("settings/sizes/create/".$subcategory_id));
        $buttons[] = array('separator');
        $buttons[] = array('Obriši veličine', 'delete', 'grid_commands', site_url("settings/sizes/delete"));
        $buttons[] = array('separator');
        $buttons[] = array('Izaberi sve', 'select_all', 'grid_commands', '/select');
		$buttons[] = array('separator');
        $buttons[] = array('Poništi izbor', 'deselect_all', 'grid_commands', '/desel');
        $buttons[] = array('separator');
        
        if( $this->input->post('page') ) $this->gridParams['newp'] = $this->input->post('page');
       
        $data['grid'] = build_grid_js('grid', site_url("settings/sizes/grid/".$subcategory_id), $colModel, 'position', 'ASC', $this->gridParams, $buttons);

        $data['grid_title'] = "Veličine";

        $this->_render_view("master/grid_view", $data);
    }

	public function grid($subcategory_id) {

        $valid_fields = array('id', 'name', 'position', 'status');

        $this->flexigrid->validate_post($this->gridParams['id'], 'position', 'ASC', $valid_fields);
        $criteria = $this->flexigrid->get_criteria();
        $records = $this->em->getRepository('models\Entities\Product\Size')->getSizes($criteria, $subcategory_id);

        $this->session->unset_userdata('edit_visited');
        $this->output->set_header($this->config->item('json_header'));
        $this->output->set_output($this->flexigrid->json_build($records['record_count'], $records['record_items']));
    }

	public function create($subcategory_id) {
		
		$data['subcategory_id'] = $subcategory_id;

        $this->_render_view('settings/size/create_size', $data);
    }

    public function save() {

		$data['size'] = new Size();
		$data['subcategory_id'] = $this->input->post('subcategory_id');

        $position = $this->input->post('position');

        $maxSize = $this->em->getRepository('models\Entities\Product\Size')->getMaxPosition($this->input->post('subcategory_id'));
        $sizes = $this->em->getRepository('models\Entities\Product\Size')->getAllSizesBySubcategory($this->input->post('subcategory_id'));

        $maxPosition = $maxSize[0][1];

        if ($position) {
            if ($position >= $maxPosition) {
                $position = $maxPosition + 1;
            } else {
                foreach ($sizes as $size) {
                    $newPosition = $size->getPosition();
                    if ($position <= $adNowPosition) {
                        $size->setPosition($newPosition + 1);
                        $this->em->persist($size);
                        $this->em->flush();
                    }
                }
            }
        } else {
            $position = $maxPosition + 1;
        }
		
		$data['size']->setSubcategory($this->em->getReference('models\Entities\Product\Subcategory', $this->input->post('subcategory_id')));
		
		$data['size']->setName( $this->input->post('title') );
		$data['size']->setStatus( $this->input->post('status') );
		$data['size']->setPosition( $position );

		$this->em->persist($data['size']);
		$this->em->flush();
		
		$data['message'] = '<p class="message_success">Nova veličina je uspešno postavljena!</p>';

		$this->_render_view('settings/size/create_size', $data);
        
    }

	public function details($id) {

        if ($data['size'] = $this->em->getRepository('models\Entities\Product\Size')->find($id)) {
        	
            $this->_render_view('settings/size/edit_size', $data);
			
        } else show_404();
    }
    
    public function edit( $id ) {

        if ($data['size'] = $this->em->getRepository('models\Entities\Product\Size')->find($id)) {
        	
            $position = $this->input->post('position');
            $old_position = $data['size']->getPosition();

            $maxSize = $this->em->getRepository('models\Entities\Product\Size')->getMaxPosition($data['size']->getSubcategory()->getID());
        	$sizes = $this->em->getRepository('models\Entities\Product\Size')->getAllSizesBySubcategory($data['size']->getSubcategory()->getID());

            $maxPosition = $maxSize[0][1];

            if ($position <= $old_position) {
                foreach ($sizes as $size) {
                    if ($size->getPosition() >= $position && $size->getPosition() < $old_position)
                        $size->setPosition($size->getPosition() + 1);
	                    $this->em->persist($size);
	                    $this->em->flush();
                }
            } else {
                if ($position >= $maxPosition) {
                    $position = $maxPosition;
                }
                foreach ($sizes as $size) {
                    if ($size->getPosition() <= $position && $size->getPosition() > $old_position)
                        $size->setPosition($size->getPosition() - 1);
	                    $this->em->persist($size);
	                    $this->em->flush();
                }
            }
				
			$data['size']->setName($this->input->post('name'));
			
			$data['size']->setName( $this->input->post('title') );
			$data['size']->setStatus( $this->input->post('status') );
			$data['size']->setPosition( $position );
			
			$this->em->persist($data['size']);
			$this->em->flush();
			
			$data['message'] = '<p class="message_success">Sve izmene su uspešno izvršene!</p>';

        	$this->_render_view('settings/size/edit_size', $data);
         }
         else show_404();
    }

	public function change_status( $id ) {

        $size = $this->em->getRepository('models\Entities\Product\Size')->find($id);
        $size->getStatus() ? $size->setStatus(0) : $size->setStatus(1);

        $this->em->flush();

        $this->output->set_output($size->getStatus());
    }

    public function delete() {
        
        $id_list = explode( ',', $this->input->post('items') );
		
		foreach ($id_list as $id) {
            $size = $this->em->getRepository('models\Entities\Product\Size')->find($id);
            $sizes = $this->em->getRepository('models\Entities\Product\Size')->getAllSizesBySubcategory($size->getSubcategory()->getID());
			
            foreach ($sizes as $size_data) {
                $oldPosition = $size_data->getPosition();
                if ($size->getPosition() < $size_data->getPosition()) {
                    $size_data->setPosition($oldPosition - 1);
                    $this->em->persist($size_data);
                    $this->em->flush();
                }
            }
        }
        
        $this->em->getRepository('models\Entities\Product\Size')->deleteSizes($id_list);
        $this->output->set_output( TRUE );
    }

}

/* End of file stickers.php */
/* Location: ./system/applications/_backend/controllers/settings/sstickers.php */
