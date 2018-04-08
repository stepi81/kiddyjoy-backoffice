<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * ...
 * @author Marko Stepanovic [ Codeion SA ]
 */
 use models\Entities\AskCustomer;
 
 class Customer_Questions extends MY_Controller {
     
     public $gridParams = array(
        'id'                    => 'customerQuestionsGrid',
        'width'                 => 'auto',
        'height'                => 400,
        'rp'                    => 15,
        'rpOptions'             => '[10,15,20,25,40]',
        'pagestat'              => 'Prikaz: {from} do {to} / Ukupno: {total} pitanja.',
        'pagetext'              => 'Stranica', 
        'outof'                 => 'od', 
        'findtext'              => 'Pronađi', 
        'procmsg'               => 'Obrada u toku, molimo sačekajte...', 
        'blockOpacity'          => 0.5,
        'showTableToggleBtn'    => true
     );
	 
	 public $answerGridParams = array(
        'id'                    => 'customerAnswerGrid',
        'width'                 => 'auto',
        'height'                => 400,
        'rp'                    => 15,
        'rpOptions'             => '[10,15,20,25,40]',
        'pagestat'              => 'Prikaz: {from} do {to} / Ukupno: {total} odgovora.',
        'pagetext'              => 'Stranica', 
        'outof'                 => 'od', 
        'findtext'              => 'Pronađi', 
        'procmsg'               => 'Obrada u toku, molimo sačekajte...', 
        'blockOpacity'          => 0.5,
        'showTableToggleBtn'    => true
     );
     
     function __construct() {
         
        parent::__construct();

        $this->load->helper(array('form', 'url'));

        $this->load->helper('flexigrid');
        $this->load->helper('upload');
        $this->load->helper('tinymce');

        $this->load->library('Flexigrid');

        $this->resources['css']=array();
        $this->resources['js']=array();
     }
     
     public function listing(){
         
        $this->resources['css'][] = 'flexigrid';
        $this->resources['js'][] = 'flexigrid';

        $this->gridParams['title'] = 'Pregled svih pitanja';
		
		$colModel['date'] = array( 'Datum', 140, TRUE, 'center', 1 );
		$colModel['user'] = array( 'Korisnik', 250, TRUE, 'center', 1 );
		$colModel['product'] = array( 'Proizvod', 250, TRUE, 'center', 1 );
        $colModel['question'] = array( 'Pitanje', 500, FALSE, 'center', 0 ); 
        $colModel['status'] = array( 'Status', 50, TRUE, 'center', 0 );
		$colModel['answers'] = array( 'Odgovori', 50, FALSE, 'center', 0 );
        $colModel['details'] = array( 'Detalji', 50, FALSE, 'center', 0 );

        $buttons[] = array('Obriši pitanje', 'delete', 'grid_commands', site_url("customer_questions/delete_question"));
        $buttons[] = array('separator');
        $buttons[] = array('Izaberi sve', 'select_all', 'grid_commands', '/select');
		$buttons[] = array('separator');
        $buttons[] = array('Poništi izbor', 'deselect_all', 'grid_commands', '/desel');
        $buttons[] = array('separator');

        if( $this->input->post('page') ) $this->gridParams['newp'] = $this->input->post('page');
        $data['grid']=build_grid_js('grid', site_url("customer_questions/grid"), $colModel, 'id', 'ASC', $this->gridParams, $buttons);

        $data['grid_title']="Pitanja";
        $this->_render_view("master/grid_view", $data);
     }
     
     public function grid() {
              
        $valid_fields = array('question', 'user', 'date', 'product', 'status' );

        $this->flexigrid->validate_post($this->gridParams['id'], 'date', 'DESC', $valid_fields);
        $criteria = $this->flexigrid->get_criteria();
        $records = $this->em->getRepository('models\Entities\AskCustomer')->getQuestions($criteria);

        $this->session->unset_userdata('edit_visited');
        $this->output->set_header($this->config->item('json_header'));
        $this->output->set_output($this->flexigrid->json_build($records['record_count'], $records['record_items']));
     }

	 public function question_details($id) {
	 	
		if( $data['question'] = $this->em->getRepository('models\Entities\AskCustomer')->find($id) ) {
			$this->resources['js'][] = 'checkbox';
			$this->_render_view('ask_customer/edit_question', $data);
        } else show_404();	
	 }
	 
	 public function edit_question( $id ) {
	 
	 	if( $data['question'] = $this->em->getRepository('models\Entities\AskCustomer')->find($id) ) {
	 		
			$this->resources['js'][] = 'checkbox';
				
			$data['question']->setStatus( $this->input->post('active') );
            $data['question']->setQuestion( $this->input->post('message') );
            
            $this->em->persist($data['question']);
            $this->em->flush();
            
            $data['message'] = '<p class="message_success">Sve izmene su uspešno izvršene!</p>';
            
            $this->_render_view( 'ask_customer/edit_question', $data );
			
        } else show_404();		
	 }

	 public function change_status( $id ) {
	 	
		$question = $this->em->getRepository('models\Entities\AskCustomer')->find($id);
		
		$users = $this->em->getRepository('models\Entities\User\Customer\Personal')->findUsersWithItem($question->getProduct()->getID());
		
		if( !$question->getStatus() ) {
			
			$this->load->library('Notification_Manager');
				
			$this->notification_manager->send_question_approval( $question );
			
			if( count( $users ) ) {
				foreach( $users as $user ) {
					$this->notification_manager->send_customer_question( $question, $user );
				}
			}
		}

        $question->getStatus() ? $question->setStatus(0) : $question->setStatus(1);

        $this->em->flush();

        $this->output->set_output($question->getStatus());
	 } 
	 
	 public function delete_question() {
	 	
		$id_list = explode( ',', $this->input->post('items') );
		
		$questions = $this->em->getRepository('models\Entities\AskCustomer')->deleteQuestions($id_list);
		
		$this->load->library('Notification_Manager');
		
		foreach( $questions as $question ) {

			$this->notification_manager->delete_question_info( $question );

        	$this->em->remove($question); 
		}
		
		$this->em->flush();
		
		$this->output->set_output( TRUE );	
	 }
	 
	 public function answers_listing( $question_id = null ) {
	 	
		$this->resources['css'][] = 'flexigrid';
        $this->resources['js'][] = 'flexigrid';

        $this->answerGridParams['title'] = 'Pregled odgovora';
		
		$colModel['date'] = array( 'Datum', 140, TRUE, 'center', 1 );
		$colModel['user'] = array( 'Korisnik', 250, TRUE, 'center', 1 );
		$colModel['product'] = array( 'Proizvod', 250, FALSE, 'center', 0 );
		$colModel['question'] = array( 'Pitanje', 250, FALSE, 'center', 0 );
        $colModel['answer'] = array( 'Odgovor', 500, FALSE, 'center', 0 ); 
        $colModel['status'] = array( 'Status', 50, TRUE, 'center', 0 );
        $colModel['details'] = array( 'Detalji', 50, FALSE, 'center', 0 );

        $buttons[] = array('Obriši odgovor', 'delete', 'grid_commands', site_url("customer_questions/delete_answer"));
        $buttons[] = array('separator');
        $buttons[] = array('Izaberi sve', 'select_all', 'grid_commands', '/select');
		$buttons[] = array('separator');
        $buttons[] = array('Poništi izbor', 'deselect_all', 'grid_commands', '/desel');
        $buttons[] = array('separator');

        if( $this->input->post('page') ) $this->answerGridParams['newp'] = $this->input->post('page');
        $data['grid']=build_grid_js('grid', site_url("customer_questions/answers_grid/".$question_id), $colModel, 'id', 'ASC', $this->answerGridParams, $buttons);

        $data['grid_title']="Odgovori";
        $this->_render_view("master/grid_view", $data);	
	 }

	 public function answers_grid( $question_id = null ) {
              
        $valid_fields = array('answer', 'user', 'date', 'status' );

        $this->flexigrid->validate_post($this->answerGridParams['id'], 'date', 'DESC', $valid_fields);
        $criteria = $this->flexigrid->get_criteria();
        $records = $this->em->getRepository('models\Entities\CustomerAnswer')->getAnswers($criteria, $question_id);

        $this->session->unset_userdata('edit_visited');
        $this->output->set_header($this->config->item('json_header'));
        $this->output->set_output($this->flexigrid->json_build($records['record_count'], $records['record_items']));
     }
	 
	 public function answer_details( $id ) {
	 
	 	if( $data['answer'] = $this->em->getRepository('models\Entities\CustomerAnswer')->find($id) ) {
			$this->resources['js'][] = 'checkbox';
			$this->_render_view('ask_customer/edit_answer', $data);
        } else show_404();		
	 }
	 
	 public function edit_answer( $id ) {
	 
	 	if( $data['answer'] = $this->em->getRepository('models\Entities\CustomerAnswer')->find($id) ) {
	 		
			$this->resources['js'][] = 'checkbox';
				
			$data['answer']->setStatus( $this->input->post('active') );
            $data['answer']->setAnswer( $this->input->post('answer') );
            
            $this->em->persist($data['answer']);
            $this->em->flush();
            
            $data['message'] = '<p class="message_success">Sve izmene su uspešno izvršene!</p>';
            
            $this->_render_view( 'ask_customer/edit_answer', $data );
			
        } else show_404();		
	 }
	 
	 public function delete_answer() {
	 	
		$id_list = explode( ',', $this->input->post('items') );
		
		$answers = $this->em->getRepository('models\Entities\CustomerAnswer')->deleteAnswers($id_list);
		
		$questions = $this->load->library('Notification_Manager');
		
		foreach( $answers as $answer ) {
			
			$this->notification_manager->delete_answer_info( $answer );

        	$this->em->remove($answer); 
		}
		
		$this->em->flush();
		
		$this->output->set_output( TRUE );
	 }
	 
	 public function change_answer_status( $id ) {
		
		$answer = $this->em->getRepository('models\Entities\CustomerAnswer')->find($id);

		if( !$answer->getStatus() ) {
			$this->load->library('Notification_Manager');
			$this->notification_manager->send_answer_approval( $answer );
			$this->notification_manager->send_customer_answer( $answer );
		}
		
		$answer->getStatus() ? $answer->setStatus(0) : $answer->setStatus(1);

        $this->em->flush();

        $this->output->set_output($answer->getStatus());
	 } 

 }
 
 /* End of file customer_questions.php */
 /* Location: ./system/application/_backend/controllers/customer_questions.php */
