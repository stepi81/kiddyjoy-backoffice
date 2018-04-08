<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * ...
 * @author Aleksandar Milas [ Codeion ]
 */

 class Preorders extends MY_Controller {
     
     public $gridParams = array(
        'id'                    => 'productsGrid',
        'width'                 => 'auto',
        'height'                => 400,
        'rp'                    => 15,
        'rpOptions'             => '[10,15,20,25,40]',
        'pagestat'              => 'Prikaz: {from} do {to} Ukupno: {total} rezervacija.',
        'blockOpacity'          => 0.5,
        'pagetext'              => 'Stranica', 
        'outof'                 => 'od', 
        'showTableToggleBtn'    => true
     );
	 
     public function __construct() {

         parent::__construct();
		 
		 $this->load->helper('flexigrid');
		 $this->load->library('Flexigrid');
		 
		 $this->resources['css'] = array();
         $this->resources['js'] = array();
     }
	 
	 public function listing() {
			
		$this->resources['css'][] = 'flexigrid';
		$this->resources['js'][] = 'flexigrid';
        
		$this->gridParams['title'] = 'Pregled svih rezervacija';
		
		//$colModel['image']  = array( 'Logo', 300, FALSE, 'center', 0 ); 
		$colModel['id']   = array( 'Rezervacija', 150, TRUE, 'center', 1 );
		$colModel['id2']  = array( 'Ime proizvoda', 200, TRUE, 'center', 1 );
		$colModel['id3']  = array( 'Kvantitet', 50, TRUE, 'center', 1 );
		$colModel['id4']  = array( 'Radnja', 60, TRUE, 'center', 1 );
		$colModel['id5']  = array( 'ID korisnika', 70, TRUE, 'center', 1 );
		$colModel['id6']  = array( 'Tip korisnika', 100, TRUE, 'center', 1 );
		$colModel['id7']  = array( 'Ime i prezime / Ime firme', 150, TRUE, 'center', 1 );
		$colModel['id8']  = array( 'Kontakt osoba', 150, TRUE, 'center', 1 );
		$colModel['id9']  = array( 'Telefon', 150, TRUE, 'center', 1 );
		$colModel['id10']  = array( 'Adresa', 150, TRUE, 'center', 1 );
		$colModel['id11']  = array( 'Poštanski broj', 100, TRUE, 'center', 1 );
		$colModel['id12']  = array( 'Datum registracije', 120, TRUE, 'center', 1 );
		//$colModel['actions']   = array( 'Detalji', 80, FALSE, 'center', 0 ); 
        
        //$buttons[] = array('Novi vendor', 'add', 'grid_commands', site_url("vendors/create"));
		//$buttons[] = array('separator');
        
        if( $this->input->post('page') ) $this->gridParams['newp'] = $this->input->post('page');
		$data['grid'] = build_grid_js('grid', site_url("preorders/grid"), $colModel, 'id', 'ASC', $this->gridParams, NULL /*$buttons*/);

		$data['grid_title'] = "Rezervacije";
		$this->_render_view( "master/grid_view", $data );
	 }
     

	 
	 public function details( $id ) {

        if( $data['brand'] = $this->em->getRepository('models\Entities\Product\Brand')->find($id) ) {
        	$this->_render_view( 'brand/edit_brand', $data );
        }
		else show_404();
     }

	 public function create() {
    	$this->_render_view( 'vendor/new_vendor', NULL );
     }
	 
	 public function save() {
	 	
		$vendor = new Vendor;
		$vendor->setID( $this->input->post('vendor_name') );
		$this->em->persist($vendor);
        $this->em->flush();
		
		$data['message'] = 'Uspešno je dodat novi vendor.';
    	$this->_render_view( 'vendor/new_vendor', $data );
     }
	 

     public function grid() {
     	 
		$valid_fields = array('id');
         
 		$this->flexigrid->validate_post($this->gridParams['id'], 'id', 'ASC', $valid_fields);
 		$criteria = $this->flexigrid->get_criteria();
 		$records = $this->em->getRepository('models\Entities\PreorderUser')->getPreorders( $criteria );
         
 		$this->session->unset_userdata('edit_visited');
 		$this->output->set_header($this->config->item('json_header'));
 		$this->output->set_output($this->flexigrid->json_build($records['record_count'],$records['record_items']));
     }
	 
}
 
 /* End of file vendors.php */
 /* Location: ./system/applications/_backend/controllers/vendors.php */