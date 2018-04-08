<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * ...
 * @author Ivan Despic [ Codeion ]
 */

use models\Entities\Review;
use models\Entities\Product;
use models\Entities\Product\Review\ReviewSpecification;
use models\Entities\User\Customer;

class Reviews extends MY_Controller {

    public $gridParams = array(
        'id'                 => 'productsGrid',
        'width'              => 'auto', 
        'height'             => 400, 
        'rp'                 => 40, 
        'rpOptions'          => '[10,15,20,25,40]', 
        'pagestat'           => 'Prikaz: {from} do {to} / Ukupno: {total} utisaka.', 
        'pagetext'           => 'Stranica', 
        'outof'              => 'od', 
        'findtext'           => 'Pronađi', 
        'procmsg'            => 'Obrada u toku, molimo sačekajte...', 
        'blockOpacity'       => 0.5, 
        'showTableToggleBtn' => true);

    public function __construct() {

        parent::__construct();

        $this->load->helper(array('form', 'url'));

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

        $this->gridParams['title'] = 'Pregled pristiglih utisaka';

        $colModel['date'] = array('Datum', 80, TRUE, 'center', 1);
        $colModel['type_id'] = array('Tip kupovine', 80, TRUE, 'center', 1);
        $colModel['user_id'] = array('Korisnik', 200, TRUE, 'center', 1);
        $colModel['points'] = array('Broj bodova', 80, TRUE, 'center', 1);
        $colModel['product_id'] = array('Proizvod', 350, TRUE, 'center', 1);
        $colModel['actions'] = array('Detalji', 50, FALSE, 'center', 0);
		
		$buttons[] = array('Obriši utisak', 'delete', 'grid_commands', site_url("reviews/delete_reviews"));
        $buttons[] = array('separator');
        $buttons[] = array('Izaberi sve', 'select_all', 'grid_commands', '/select');
        $buttons[] = array('separator');
        $buttons[] = array('Poništi izbor', 'deselect_all', 'grid_commands', '/desel');
        $buttons[] = array('separator');
        
        if( $this->input->post('page') ) $this->gridParams['newp'] = $this->input->post('page');
        $data['grid'] = build_grid_js('grid', site_url("reviews/grid"), $colModel, 'id', 'DESC', $this->gridParams, $buttons);

        $data['grid_title'] = "Novi utisci";
        $this->_render_view("master/grid_view", $data);
    }
    
	public function grid() {

        $valid_fields = array('user_id', 'points', 'product_id', 'date', 'negative', 'positive', 'status', 'rating' );

        $this->flexigrid->validate_post($this->gridParams['id'], 'id', 'DESC', $valid_fields);
        $criteria = $this->flexigrid->get_criteria();
        $records = $this->em->getRepository('models\Entities\Review')->getReview($criteria);

        $this->session->unset_userdata('edit_visited');
        $this->output->set_header($this->config->item('json_header'));
        $this->output->set_output($this->flexigrid->json_build($records['record_count'], $records['record_items']));
    }
    
    public function history() {
    	
    	$this->resources['css'][] = 'flexigrid';
        $this->resources['js'][] = 'flexigrid';

        $this->gridParams['title'] = 'Pregled obrađenih utisaka';

        $colModel['date'] = array('Datum', 80, TRUE, 'center', 1);
        $colModel['type_id'] = array('Tip kupovine', 80, TRUE, 'center', 1);
        $colModel['user_id'] = array('Korisnik', 200, TRUE, 'center', 1);
        $colModel['points'] = array('Broj bodova', 80, TRUE, 'center', 1);
        $colModel['product_id'] = array('Proizvod', 350, TRUE, 'center', 1);
        $colModel['positive'] = array('Pozitivna ocena', 78, TRUE, 'center', 0);
        $colModel['negative'] = array('Negativna ocena', 78, TRUE, 'center', 0);
        $colModel['rating'] = array('Rejting', 50, TRUE, 'center', 0);
        $colModel['actions'] = array('Detalji', 50, FALSE, 'center', 0);
        $colModel['status'] = array('Status', 50, TRUE, 'center', 0);
		
		$buttons[] = array('Obriši utisak', 'delete', 'grid_commands', site_url("reviews/delete_reviews"));
        $buttons[] = array('separator');
        $buttons[] = array('Izaberi sve', 'select_all', 'grid_commands', '/select');
        $buttons[] = array('separator');
        $buttons[] = array('Poništi izbor', 'deselect_all', 'grid_commands', '/desel');
        $buttons[] = array('separator');
        
        if( $this->input->post('page') ) $this->gridParams['newp'] = $this->input->post('page');
        $data['grid'] = build_grid_js('grid', site_url("reviews/history_grid"), $colModel, 'id', 'DESC', $this->gridParams, $buttons);

        $data['grid_title'] = "Istorijat utisaka";
        $this->_render_view("master/grid_view", $data);
    }

    public function history_grid() {

        $valid_fields = array('user_id', 'points', 'product_id', 'date', 'negative', 'positive', 'status', 'rating' );

        $this->flexigrid->validate_post($this->gridParams['id'], 'id', 'DESC', $valid_fields);
        $criteria = $this->flexigrid->get_criteria();
        $records = $this->em->getRepository('models\Entities\Review')->getReview($criteria, TRUE);

        $this->session->unset_userdata('edit_visited');
        $this->output->set_header($this->config->item('json_header'));
        $this->output->set_output($this->flexigrid->json_build($records['record_count'], $records['record_items']));
    }
	
	public function delete_reviews() {
    	
    	$id_list = explode( ',', $this->input->post('items') );
        $this->em->getRepository('models\Entities\Review')->deleteReview($id_list);
        $this->output->set_output( TRUE );
    }
    
    public function specifications( $subcategory_id, $message = NULL ) {
    	
    	$this->resources['css'][] = 'flexigrid';
        $this->resources['js'][] = 'flexigrid';

        $this->gridParams['title'] = 'Pregled';
        $this->gridParams['width'] = 480;

        $colModel['position'] 	= array('Pozicija', 80, TRUE, 'center', 1);
        $colModel['name'] 		= array('Naziv', 280, TRUE, 'center', 1);
        $colModel['actions'] 	= array('Detalji', 50, FALSE, 'center', 0);

        $buttons[] = array('Obriši specifikaciju', 'delete', 'grid_commands', site_url("reviews/delete_specification"));
        $buttons[] = array('separator');
        $buttons[] = array('Izaberi sve', 'select_all', 'grid_commands', '/select');
        $buttons[] = array('separator');
        $buttons[] = array('Poništi izbor', 'deselect_all', 'grid_commands', '/desel');
        $buttons[] = array('separator');
        
        if( $this->input->post('page') ) $this->gridParams['newp'] = $this->input->post('page');
        $data['grid'] = build_grid_js('grid', site_url("reviews/specifications_grid/".$subcategory_id), $colModel, 'position', 'ASC', $this->gridParams, $buttons);

        $data['grid_title'] = "Specifikacije utisaka";
        $data['message'] = $message;
        
        $this->_render_view("product/review/create_specification", $data);
    }
    
    public function specifications_grid( $subcategory_id ) {
    	
    	$valid_fields = array('position', 'name');

        $this->flexigrid->validate_post($this->gridParams['id'], 'position', 'ASC', $valid_fields);
        $criteria = $this->flexigrid->get_criteria();
        $records = $this->em->getRepository('models\Entities\Review')->getReviewSpecifications($criteria, $subcategory_id);

        $this->session->unset_userdata('edit_visited');
        $this->output->set_header($this->config->item('json_header'));
        $this->output->set_output($this->flexigrid->json_build($records['record_count'], $records['record_items']));
    }

    public function details( $id ) {

        if ($data['review'] = $this->em->getRepository('models\Entities\Review')->find($id)) {
           
            switch( get_class($data['review'] ->getUserId()) ) {
                case 'models\Entities\User\Customer\Personal':
                     $data['user_name'] = $data['review']->getUserId()->getNickname();
                     if ($data['user_name'] == NULL){ $data['user_name'] = $data['review']->getUserId()->getFirstName();}
                     break;
                case 'models\Entities\User\Customer\Business': 
                      $data['user_name'] = $data['review']->getUserId()->getContactPerson();
                      break;
            }
            
            $this->resources['js'][] = 'checkbox';
            $this->_render_view('public_relations/edit_review', $data);
        } 
        else show_404();
    }
    
    public function submit( $status, $id ) {
    	
    	if( $review = $this->em->getRepository('models\Entities\Review')->find($id) ) {
    		
    		$this->load->library('Notification_Manager');
    		
    		  if( $status ) {
                $review->setStatus(1);
                $review->setTextAdvantage( $this->input->post('textAdvantage') );
                $review->setTextAgainst( $this->input->post('textAgainst') );
                $review->getUserId()->setPoints(5);
                $this->em->flush();
                $data['review'] = $review;
                $data['review']->comment = $this->input->post('comment');
                $this->notification_manager->send_review_award( $review );
            }
            else {
                $review->setStatus(2);
                $this->em->flush();
                $data['review'] = $review;
                $data['review']->comment = $this->input->post('comment');
                $this->notification_manager->send_review_rejection( $review );
            }
            redirect('reviews/listing');
    	}
    	else show_404();
    }
    
    public function save_specification( $subcategory_id ) {
    	
    	// TODO validation
    	
    	$specifications = $this->em->getRepository('models\Entities\Review')->getReviewSpecificationsByPosition( $subcategory_id );
    	
    	$specification = new ReviewSpecification();
    	$specification->setName( $this->input->post('name') );
    	$specification->setSubcategory( $this->em->getReference('models\Entities\Product\Subcategory', $subcategory_id) );
    	$specification->initRatings();
    	
    	// TODO izracunati poziciju ako je veci broj od postojecih elemenata i ako nema ni jedan postaviti na prvu poziciju
    	array_splice($specifications, $this->input->post('position')-1, 0, array($specification));
    	foreach( $specifications as $key => $item ) $item->setPosition($key+1);

    	$this->em->persist($specification);
    	$this->em->flush();
    	
    	$message='<p class="message_success"  style="width: 250px; padding: 8px 5px;">Nova specifikacija je uspešno dodata!</p>';
        $this->specifications( $subcategory_id, $message );
    }
    
    public function edit_specification( $id ) {
    	
    	// TODO validation
    	// not happy with this solution
    	
    	$data['specification'] = $this->em->getRepository('models\Entities\Review')->getReviewSpecificationByID($id);
    	
    	if( $this->input->post('name') && $data['specification']->getName() != $this->input->post('name') ) {
    		$data['specification']->setName( $this->input->post('name') );
    		$update = TRUE;
    	}
    	
    	if( $this->input->post('position') && $data['specification']->getPosition() != $this->input->post('position') ) {
    		$specifications = $this->em->getRepository('models\Entities\Review')->getReviewSpecificationsByPosition( $data['specification']->getSubcategory()->getID() );
    		unset( $specifications[array_search($data['specification'], $specifications)] );
    		array_splice($specifications, $this->input->post('position')-1, 0, array($data['specification']));
    		foreach( $specifications as $key => $item ) $item->setPosition($key+1);
    		$update = TRUE;
    	}
    	
    	if( isset($update) ) {
    		$data['message'] = '<p class="message_success"  style="width: 250px; padding: 8px 5px;">Sve promene su uspešno izvršene!</p>';
    		$this->em->flush();
    	}
    	
    	$this->_render_view('product/review/edit_specification', $data);
    }
    
    public function delete_specification() {
    	
    	$id_list = explode( ',', $this->input->post('items') );
        $this->em->getRepository('models\Entities\Review')->deleteReviewSpecification($id_list);
        $this->output->set_output( TRUE );
    }
    
    private function reordering( $specification ) {
    	
    	$specifications = $this->em->getRepository('models\Entities\Review')->getReviewSpecificationsByPosition( $specification->getSubcategory()->getID() );
    	
    	// TODO izracunati poziciju ako je veci broj od postojecih elemenata i ako nema ni jedan postaviti na prvu poziciju
    	array_splice($specifications, $this->input->post('position')-1, 0, array($specification));
    	foreach( $specifications as $key => $item ) $item->setPosition($key+1);
    }
}

/* End of file reviews.php */
/* Location: ./system/applications/_backend/controllers/reviews.php */