<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ...
 * @author Marko Stepanovic [ Codeion ]
 */
 
 use models\Entities\Questionnaire; 
 use models\Entities\Questionnaire\Question; 
 use models\Entities\Questionnaire\Answer; 
 
 class Questions extends MY_Controller {
     
      public $gridParams = array(
        'id'                    => 'productsGrid',
        'width'                 => 570,
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
     
     public function __construct() {
         
         parent::__construct();
         
         $this->load->helper('flexigrid');
         
         $this->load->library('Flexigrid');
         
         $this->resources['css'] = array();
         $this->resources['js'] = array();
     }
     
     public function listing( $id, $message = NULL ) {
            
        $this->resources['css'][] = 'flexigrid';
        $this->resources['js'][] = 'flexigrid';
        $this->resources['js'][] = 'checkbox';
        
        $this->session->set_userdata('return_uri', $this->uri->uri_string());
        $this->gridParams['title'] = 'Pregled svih pitanja';

        $colModel['position']  = array( 'Pozicija', 50, TRUE, 'center', 1 );
        $colModel['question']  = array( 'Pitanje', 400, FALSE, 'center', 0 );
        $colModel['actions'] = array( 'Detalji', 50, FALSE, 'center', 0 );

        $buttons[] = array('Novo pitanje', 'add', 'grid_commands', site_url("inquiry/questions/create/$id"));
        $buttons[] = array('separator');
        $buttons[] = array('Obriši pitanje', 'delete', 'grid_commands', site_url("inquiry/questions/delete"));
        $buttons[] = array('separator');
        $buttons[] = array('Izaberi sve', 'select_all', 'grid_commands', '/select');
		$buttons[] = array('separator');
        $buttons[] = array('Poništi izbor', 'deselect_all', 'grid_commands', '/desel');
        $buttons[] = array('separator');
        
        if( $this->input->post('page') ) $this->gridParams['newp'] = $this->input->post('page');
        
        $data['grid'] = build_grid_js('grid', site_url("inquiry/questions/grid/".$id), $colModel, 'position', 'ASC', $this->gridParams, $buttons);
        $data['questionnaire'] = $this->em->getRepository('models\Entities\Questionnaire')->find($id);
       
        $message ? $data['message'] = $message : '';

        $data['grid_title'] = "Pitanja";
        $this->_render_view( "questionnaire/questions_grid", $data );
     }
     
     public function grid( $id ) {

        $valid_fields = array('id', 'position');
        
        $this->flexigrid->validate_post($this->gridParams['id'],'position', 'ASC', $valid_fields);
        $criteria = $this->flexigrid->get_criteria();
        $records = $this->em->getRepository('models\Entities\Questionnaire\Question')->getQuestions( $criteria, $id );
         
        $this->session->unset_userdata('edit_visited');
        $this->output->set_header($this->config->item('json_header'));
        $this->output->set_output($this->flexigrid->json_build($records['record_count'],$records['record_items']));
     }
     
     public function delete() {
        
        $id_list=explode(',', $this->input->post('items'));

        foreach ($id_list as $id) {
            $ad = $this->em->getRepository('models\Entities\Questionnaire\Question')->find($id);
            $ads = $this->em->getRepository('models\Entities\Questionnaire\Question')->getQuestionByQuestionnaire($ad->getQuestionnaire()->getID());
            foreach ($ads as $ad_data) {
                $oldPosition=$ad_data->getPosition();
                if ($ad->getPosition() < $ad_data->getPosition()) {
                    $ad_data->setPosition($oldPosition - 1);
                    $this->em->persist($ad_data);
                    $this->em->flush();
                }
            }
        }
        $this->em->getRepository('models\Entities\Questionnaire\Question')->deleteQuestions($id_list);
        $this->output->set_output(TRUE);
    }
        
     public function create($questionnaire_id) {
     
         $data['questionnaire_id'] = $questionnaire_id;
         $this->_render_view( 'questionnaire/new_question', $data );
     }
     
     public function edit( $id ) {

        if( $data['questionnaire'] = $this->em->getRepository('models\Entities\Questionnaire')->find($id) ) {
            
            // TODO server validation
            
            $data['questionnaire']->setStatus( $this->input->post('status') );
            $data['questionnaire']->setTitle( $this->input->post('title') );
            
            if($_FILES["image"]["size"] > 0){
                $old_image = $data['questionnaire']->getImage();
                $image = $this->create_image(); 
                if ($old_image != NULL){ 
                    unlink( SERVER_IMAGE_PATH.'questionnaires/'. $old_image );
                }
                $data['questionnaire']->setImage( $image );
            }
            $this->em->persist($data['questionnaire']);
            $this->em->flush();
            
            $message = '<p class="message_success"  style="width: 373px; padding: 8px 5px;">Sve izmene su uspešno izvršene!</p>';
 
            $this->listing( $id, $message );
        }
        else show_404();
     }
     
     public function save($questionnaire_id){

        $position = $this->input->post('position');

        $questions = $this->em->getRepository('models\Entities\Questionnaire\Question')->getQuestionByQuestionnaire($questionnaire_id);
        $max = $this->em->getRepository('models\Entities\Questionnaire\Question')->getMaxQuestionPosition($questionnaire_id);

        $maxPosition = $max[0][1];

        if ($position) {
            if ($position >= $maxPosition) {
                $position = $maxPosition + 1;
            } else {
                foreach ($questions as $ad) {
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
         
         $question = new Question();
         $question->setQuestionnaire($this->em->getReference('models\Entities\Questionnaire', $questionnaire_id));
         $question->setType($this->input->post('answer_type'));
         $question->setText($this->input->post('text'));
         $question->setPosition($position);
           
         $this->em->persist($question);
         $this->em->flush();

         if($this->input->post('answer')){
            $i = 1; 
            foreach($this->input->post('answer') as $answer){
                 if($answer != ''){
                      $answers = new Answer();
                      $answers->setAnswer($this->em->getReference('models\Entities\Questionnaire\Question', $question->getID()));
                      $answers->setText($answer);
                      $answers->setPosition($i);

                      $this->em->persist($answers);
                      $this->em->flush();
                     
                     $i++;
                }
            }
         }
        $data['message']="<p class='message_success'>Novo pitanje je uspešno postavljeno!</p>";
        $data['questionnaire_id'] = $questionnaire_id;
        $this->_render_view('questionnaire/new_question', $data);
     }
 
     private function create_image( $image = NULL ) {
         
        if( !$_FILES['image']['size'] ) return $image;
        
        $upload_config['encrypt_name']      = TRUE;
        $upload_config['upload_path']       = SERVER_IMAGE_PATH.'questionnaires/';
        $upload_config['allowed_types']     = 'gif|jpg|png';
        $upload_config['max_size']          = '2048';
        $upload_config['remove_spaces']     = TRUE;
        
        $this->load->library('upload');
        
        $this->upload->initialize($upload_config);
        
        if( $this->upload->do_upload('image') ) {
            
            $image_data = $this->upload->data();
            
            $img_config['image_library']     = 'gd2';
            $img_config['source_image']     = $image_data['full_path'];
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
 }
 
 /* End of file questions.php */
 /* Location: ./system/applications/_backend/controllers/inquiry/questions.php */