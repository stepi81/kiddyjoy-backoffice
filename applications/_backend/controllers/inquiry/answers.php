<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ...
 * @author Ivan Despic [ Codeion ]
 */

 use models\Entities\Questionnaire\Answer; 
 
class Answers extends MY_Controller {

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

     public function listing($question_id) {

         $data['question'] = $this->em->getRepository('models\Entities\Questionnaire\Question')->find($question_id);
         
         if ($data['question']->getType() == 2){
            
             $this->resources['css'][] = 'flexigrid';
             $this->resources['js'][] = 'flexigrid';
                        
             $this->gridParams['title'] = 'Pregled ponudjenih odgovora';
    
             $colModel['position']   = array( 'Pozicija', 50, TRUE, 'center', 1 );
             $colModel['text']  = array( 'Odgovor', 360, FALSE, 'center', 1 );
             $colModel['details']   = array( 'Detalji', 50, FALSE, 'center', 0 );
    
             $buttons[] = array('Obriši odgovor', 'delete', 'grid_commands', site_url("inquiry/answers/delete"));
             $buttons[] = array('separator');
             $buttons[] = array('Izaberi sve', 'select_all', 'grid_commands', '/select');
			 $buttons[] = array('separator');
             $buttons[] = array('Poništi izbor', 'deselect_all', 'grid_commands', '/desel');
             $buttons[] = array('separator');
             
             if( $this->input->post('page') ) $this->gridParams['newp'] = $this->input->post('page');
             $data['grid'] = build_grid_js('grid', site_url("inquiry/answers/grid/".$question_id), $colModel, 'position', 'ASC', $this->gridParams, $buttons);
    
             $data['grid_title'] = $data['question']->getText();
             $this->_render_view( "questionnaire/answers_grid", $data );
         }else{
             $this->_render_view( "questionnaire/answers_grid", $data );
         }
     }
     public function grid($question_id) {
         
         $valid_fields = array('id', 'position');
         
         $this->flexigrid->validate_post($this->gridParams['id'], 'position', 'ASC', $valid_fields);
         $criteria = $this->flexigrid->get_criteria();
         $records = $this->em->getRepository('models\Entities\Questionnaire\Answer')->getAnswers( $criteria, $question_id );
         
         $this->session->unset_userdata('edit_visited');
         $this->output->set_header($this->config->item('json_header'));
         $this->output->set_output($this->flexigrid->json_build($records['record_count'],$records['record_items']));
     }
     
     public function save($question_id){
        
        $position = $this->input->post('answer_position');

        $answers = $this->em->getRepository('models\Entities\Questionnaire\Answer')->getAnswersByQuestion( $question_id );
        $max = $this->em->getRepository('models\Entities\Questionnaire\Answer')->getMaxAnswerPosition($question_id); 
         
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
         
        $answer = new Answer();
        $answer->setAnswer($this->em->getReference('models\Entities\Questionnaire\Question', $question_id));
        $answer->setText($this->input->post('answer_text'));
        $answer->setPosition($position);
        
        $this->em->persist($answer);
        $this->em->flush();
        
        $data['message']='safsgfasgasg';

        redirect( 'inquiry/answers/listing/'.$question_id );
     }
     
     public function delete() {
        
     $id_list = explode(',', $this->input->post('items'));

        foreach ($id_list as $id) {
            $ad = $this->em->getRepository('models\Entities\Questionnaire\Answer')->find($id);
            $ads = $this->em->getRepository('models\Entities\Questionnaire\Answer')->getAnswersByQuestion($ad->getAnswer()->getID());
            foreach ($ads as $ad_data) {
                $oldPosition=$ad_data->getPosition();
                if ($ad->getPosition() < $ad_data->getPosition()) {
                    $ad_data->setPosition($oldPosition - 1);
                    $this->em->persist($ad_data);
                    $this->em->flush();
                }
            }
        }
        $this->em->getRepository('models\Entities\Questionnaire\Answer')->deleteAnswer($id_list);
        $this->output->set_output(TRUE);
    }
     
     public function details($answer_id){
         
         $data['edit_title'] = "izmeni odgovor";
         
         $data['answer'] = $this->em->getRepository('models\Entities\Questionnaire\Answer')->find($answer_id);
         
         $this->_render_view( 'questionnaire/edit_answer', $data );
     }
     
     public function edit($question_id){
                        
          if( $data['question'] = $this->em->getRepository('models\Entities\Questionnaire\Question')->find($question_id) ) {
                  
             $data['question']->setText( $this->input->post('question_text') );
             
             $questions = $this->em->getRepository('models\Entities\Questionnaire\Question')->getQuestionByQuestionnaire($data['question']->getQuestionnaire()->getID());
             $max = $this->em->getRepository('models\Entities\Questionnaire\Question')->getMaxQuestionPosition($data['question']->getQuestionnaire()->getID());  
                              
             $old_position = $data['question']->getPosition();  
             $position = $this->input->post('position');
                               
             $maxPosition=$max[0][1];
    
                    if ($position <= $old_position) {
                        foreach ($questions as $ad) {
                            if ($ad->getPosition() >= $position && $ad->getPosition() < $old_position)
                                $ad->setPosition($ad->getPosition() + 1);
                            $this->em->persist($ad);
                            $this->em->flush();
                        }
                    } else {
                        if ($position >= $maxPosition) {
                            $position=$maxPosition;
                        }
                        foreach ($questions as $ad) {
                            if ($ad->getPosition() <= $position && $ad->getPosition() > $old_position)
                                $ad->setPosition($ad->getPosition() - 1);
                            $this->em->persist($ad);
                            $this->em->flush();
                        }
                    }
                 
                 $data['question']->setPosition($position);
                 $this->em->persist($data['question']);
                 $this->em->flush();
                 $this->listing($question_id);
          }
          else show_404();
     }

     public function edit_answer($answer_id){

           if( $data['answer'] = $this->em->getRepository('models\Entities\Questionnaire\Answer')->find($answer_id) ) {
                  
             $data['answer']->setText( $this->input->post('answer_text') );
             
             $answer = $this->em->getRepository('models\Entities\Questionnaire\Answer')->getAnswersByQuestion($data['answer']->getAnswer()->getID());
             $max = $this->em->getRepository('models\Entities\Questionnaire\Answer')->getMaxAnswerPosition($data['answer']->getAnswer()->getID());  
             $old_position = $data['answer']->getPosition();  
             $position = $this->input->post('answer_position');
                               
             $maxPosition=$max[0][1];
    
                    if ($position <= $old_position) {
                        foreach ($answer as $ad) {
                            if ($ad->getPosition() >= $position && $ad->getPosition() < $old_position)
                                $ad->setPosition($ad->getPosition() + 1);
                            $this->em->persist($ad);
                            $this->em->flush();
                        }
                    } else {
                        if ($position >= $maxPosition) {
                            $position=$maxPosition;
                        }
                        foreach ($answer as $ad) {
                            if ($ad->getPosition() <= $position && $ad->getPosition() > $old_position)
                                $ad->setPosition($ad->getPosition() - 1);
                            $this->em->persist($ad);
                            $this->em->flush();
                        }
                    }
                 $data['answer']->setPosition($position);
                 $this->em->persist($data['answer']);
                 $this->em->flush();
                 $this->listing($data['answer']->getAnswer()->getID());
          }
          else show_404();
     }

     public function set_listing_page($page){

        $this->session->set_userdata('current_listing', $page);
        $this->session->set_userdata('current_controller', $this->controller);
        $this->session->unset_userdata('qtype');
        $this->session->unset_userdata('query');
    }
    
}

/* End of file answers.php */
 /* Location: ./system/applications/_backend/controllers/inquiry/answers.php */