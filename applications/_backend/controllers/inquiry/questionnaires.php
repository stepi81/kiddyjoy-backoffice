<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * ...
 * @author Marko Stepanovic [ Codeion ]
 */

 use models\Entities\Questionnaire;
 
 class Questionnaires extends MY_Controller {
     
     public $gridParams = array(
        'id'                    => 'productsGrid',
        'width'                 => 570,
        'height'                => 400,
        'rp'                    => 15,
        'rpOptions'             => '[10,15,20,25,40]',
        'pagestat'              => 'Prikaz: {from} do {to} / Ukupno: {total} anketa.',
        'pagetext'              => 'Stranica',
        'outof'                 => 'od',
        'findtext'              => 'Pronađi',
        'procmsg'               => 'Obrada u toku, molimo sačekajte...',
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
        $this->resources['js'][] = 'checkbox';
        
        $this->gridParams['title'] = 'Pregled svih anketa';

        $colModel['date']  = array( 'Datum', 100, TRUE, 'center', 1 );
        $colModel['title']  = array( 'Naslov', 200, TRUE, 'center', 1 );
        $colModel['status'] = array( 'Status', 50, TRUE, 'center', 1 );
        $colModel['results'] = array( 'Rezultati', 50, FALSE, 'center', 0 );
        $colModel['actions'] = array( 'Detalji', 50, FALSE, 'center', 0 );

        $buttons[] = array('Obriši anketu', 'delete', 'grid_commands', site_url("inquiry/questionnaires/delete"));
        $buttons[] = array('separator');
        $buttons[] = array('Izaberi sve', 'select_all', 'grid_commands', '/select');
        $buttons[] = array('separator');
        $buttons[] = array('Poništi izbor', 'deselect_all', 'grid_commands', '/desel');
        $buttons[] = array('separator');
        
        if( $this->input->post('page') ) $this->gridParams['newp'] = $this->input->post('page');
        $data['grid'] = build_grid_js('grid', site_url("inquiry/questionnaires/grid"), $colModel, 'date', 'DESC', $this->gridParams, $buttons);

        $data['grid_title'] = "Ankete";
        $this->_render_view( "questionnaire/questionnaire_grid", $data );
     }
     
     public function grid() {

         $valid_fields = array('date', 'title', 'status');
         
         $this->flexigrid->validate_post($this->gridParams['id'],'date', 'DESC', $valid_fields);
         $criteria = $this->flexigrid->get_criteria();
         $records = $this->em->getRepository('models\Entities\Questionnaire')->getQuestionnaires( $criteria );
         
         $this->session->unset_userdata('edit_visited');
         $this->output->set_header($this->config->item('json_header'));
         $this->output->set_output($this->flexigrid->json_build($records['record_count'],$records['record_items']));
     }
     
     public function save() {

        // TODO server validation
        
        $data['questionnaire'] = new Questionnaire();
        
             $image = $this->create_image();
            
             $data['questionnaire'] = new Questionnaire();
             $data['questionnaire']->setDate();
             $data['questionnaire']->setTitle( $this->input->post('title') );
             $data['questionnaire']->setImage( $image );
             $data['questionnaire']->setStatus( $this->input->post('status') );
             
             $this->em->persist($data['questionnaire']);
             $this->em->flush();
            
            $data['message'] = '<p class="message_success">Nova anketa je uspešno sačuvan!</p>';

        redirect( 'inquiry/questionnaires/listing' );
     }
     
     public function change_status( $id ){
         
          $questionnaire = $this->em->getRepository('models\Entities\Questionnaire')->find($id);
          $questionnaire->getStatus() ? $questionnaire->setStatus(0) : $questionnaire->setStatus(1); 
          
          $this->em->flush();

          $this->output->set_output($questionnaire->getStatus());
     }
     
     public function delete() {
        
        $id_list = explode( ',', $this->input->post('items') );
        
        $this->em->getRepository('models\Entities\Questionnaire')->deleteQuestionnaires($id_list);
        $this->output->set_output( TRUE );
     }
     
     private function create_image( $image = NULL ) {
         
        if( !$_FILES['image']['size'] ) return $image;
        
        $upload_config['encrypt_name']       = TRUE;
        $upload_config['upload_path']        = SERVER_IMAGE_PATH.'questionnaires/';
        $upload_config['allowed_types']      = 'gif|jpg|png';
        $upload_config['max_size']           = '2048';
        $upload_config['remove_spaces']      = TRUE;
        
        $this->load->library('upload');
        
        $this->upload->initialize($upload_config);
        
        if( $this->upload->do_upload('image') ) {
            
            $image_data = $this->upload->data();
            
            $img_config['image_library']     = 'gd2';
            $img_config['source_image']      = $image_data['full_path'];
            $img_config['width']             = 150;
            $img_config['height']            = 300;
            $img_config['maintain_ratio']    = TRUE;
            
            $img_config['master_dim']        = 'width';
            
            $this->load->library('image_lib', $img_config);
            
            if ( $this->image_lib->resize() ) {
                if( $image ) unlink( SERVER_IMAGE_PATH.'questionnaires/'.$image );
                return $image_data['file_name'];
            }
            else return NULL;
        }
        else return NULL;
     } 

    public function results($questionnaire_id){
        
         $questionnaire = $this->em->getRepository('models\Entities\Questionnaire')->find($questionnaire_id);
        
         $results = $this->em->getRepository('models\Entities\Questionnaire\Result')->getResultByQuestionnaire($questionnaire_id);

         if(!empty($results)){
      
             foreach($results as $result){

             $x = json_decode($result->getData());
           
		   
		     $question_type_1_exists = FALSE;
		     $question_type_2_exists = FALSE;
		     $question_type_3_exists = FALSE;
		   
           foreach ($x as $r) {
                 if($r->question_type == 1){
                    $question_type_1[$r->question_id][] = $r->answer_value;
					$question_type_1_exists = TRUE;
                 }elseif($r->question_type == 2){
                    $question_type_2[$r->question_id][] = $r->answer_value;
					$question_type_2_exists = TRUE;
                 }
                 elseif($r->question_type == 3){
                 $question_type_3[$r->question_id][] = $r->answer_value;
				 $question_type_3_exists = TRUE;
                 }
               }
           }
           
           if($question_type_1_exists)
             foreach($question_type_1 as $key=>$val){
                 $counted_results_polar[$key] = array_count_values($val);
             }
           if($question_type_2_exists)
             foreach($question_type_2 as $key=>$val){
                 $counted_results_single[$key] = array_count_values($val);
             }
			if($question_type_1_exists)
            	 $data['results_type_1'] = $counted_results_polar;
			if($question_type_2_exists)
            	 $data['results_type_2'] = $counted_results_single;
			if($question_type_3_exists)
            	 $data['results_type_3'] = $question_type_3;
            
             $data['questions'] = $this->em->getRepository('models\Entities\Questionnaire\Question')->getQuestionByQuestionnaire($questionnaire_id);
             $data['answers'] = $this->em->getRepository('models\Entities\Questionnaire\Answer')->findAll();
         }else{
             $data['empty'] = '<p style="height:40px; vertical-align:middle;text-align:center">Nema rezultata za ovu anketu</p>';
         }
         $data['questionnaire_title'] = $questionnaire->getTitle();
         $this->_render_view('questionnaire/results', $data);
    }
 }
 
 /* End of file questionnaires.php */
 /* Location: ./system/applications/_backend/controllers/questionnaires.php */