<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * ...
 * @author Andrej The Savic [ ABC Design ]
 */
 
 class Admin_Activities extends MY_Controller {

 	public $gridParams = array(
        'id'                 => 'productsGrid',
        'width'              => 'auto',
        'height'             => 400,
        'rp'                 => 40,
        'rpOptions'          => '[10,15,20,25,40]',
        'pagestat'           => 'Prikaz: {from} do {to} / Ukupno: {total} aktivnosti.',
        'pagetext'           => 'Stranica',
        'outof'              => 'od',
        'findtext'           => 'Pronađi',
        'procmsg'            => 'Obrada u toku, molimo sačekajte...',
        'blockOpacity'       => 0.5,
        'showTableToggleBtn' => true);
 	
 	 	
    public function __construct() {
    	
    	parent::__construct();
    	
    	$this->load->helper('flexigrid');

        $this->load->library('Flexigrid');

        $this->resources['css'] = array();
        $this->resources['js'] = array();
    }
    
	public function index() {
		
		$this->resources['css'][] = 'flexigrid';
        $this->resources['js'][] = 'flexigrid';
		 	
		$this->gridParams['title'] = 'Pregled Aktivnosti Administratora';

        $colModel['date'] = array('Datum', 120, TRUE, 'center', 1);
        $colModel['user_id'] = array('Administrator', 200, TRUE, 'center', 1);
        $colModel['group_id'] = array('Grupa', 200, TRUE, 'center', 1);
        $colModel['operation_id'] = array('Aktivnost', 300, TRUE, 'center', 0);
        $colModel['process_id'] = array('Postupak', 160, TRUE, 'center', 0);
        $colModel['record_id'] = array('Zapis', 400, FALSE, 'center', 0);
        
        if( $this->input->post('page') ) $this->gridParams['newp'] = $this->input->post('page');
        $data['grid'] = build_grid_js('grid', site_url("users/admin_activities/grid"), $colModel, 'date', 'DESC', $this->gridParams);

        $data['grid_title'] = "Aktivnosti";
        $this->_render_view("master/grid_view", $data);
	}
	
 	public function grid() {

        $valid_fields = array('date', 'user_id', 'group_id', 'operation_id', 'process_id');

        $this->flexigrid->validate_post($this->gridParams['id'], 'id', 'DESC', $valid_fields);
        $criteria = $this->flexigrid->get_criteria();
        $records = $this->em->getRepository('models\Entities\User\Admin')->getAdminActivities($criteria);

        $this->output->set_header($this->config->item('json_header'));
        $this->output->set_output($this->flexigrid->json_build($records['record_count'], $records['record_items']));
    }
 }
 
 /* End of file admin_activities.php */
 /* Location: ./system/applications/_backend/controllers/users/admin_activities.php */