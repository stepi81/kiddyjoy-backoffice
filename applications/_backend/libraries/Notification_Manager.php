<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 class Notification_Manager {

    var $CI;
    private $logo;
     
    function __construct() {
         
         $config['mailtype'] = 'html';
        
         $this->CI =& get_instance();
		 
         $this->CI->load->library('email');
         $this->CI->email->initialize($config);
         $this->CI->email->from( 'no-reply@kiddyjoy.rs', 'KiddyJoy' );
    } 
     
     function new_admin_password( $user, $password ) {
        
         $this->CI->email->to($user->getEmail());
         $this->CI->email->subject( 'Nova Lozinka' );
         $data['subject'] = 'Nova Lozinka';
         $data['user'] = $user;
         $data['password'] = $password;
         $data['logo'] = APP_URL.'assets/img/layout/_backend/email_logo.png';
         
         $data['body'] = $this->CI->load->view( 'notifications/user/password_recovery_view', $data, true );
         $this->CI->email->message($this->CI->load->view( 'notifications/master_layout', $data, true ));
         
         return $this->CI->email->send();    
     }

     function send_newsletter( $user, $title, $message ) {
     	
         $this->CI->email->to($user['email']);
         $this->CI->email->subject( $title );
  
         $data['logo'] = APP_URL.'assets/img/layout/_backend/email_logo.png';
         $data['message'] = $message;
		 $data['request_token'] = $user['request_token'];
		 
		 $data['body'] = $this->CI->load->view( 'notifications/newsletter/classic', $data, true );
		 
         $this->CI->email->message($data['body']);
         
         return $this->CI->email->send(); 
	 }
     
     public function send_review_award( $review ) {
         
         $this->CI->email->to( $review->getUserId()->getEmail() );
         $this->CI->email->subject( 'KiddyJoy odobren utisak' );
         $data['review'] = $review;
         $data['height'] = 150;
		 $data['logo'] = APP_URL.'assets/img/layout/_backend/email_logo.png';
         
         $data['body'] = $this->CI->load->view( 'notifications/review/award_view', $data, true );
         $this->CI->email->message($this->CI->load->view( 'notifications/email_layout', $data, true ));
         
         return $this->CI->email->send();
     }
     
     public function send_review_rejection( $review ) {
         
         $this->CI->email->to( $review->getUserId()->getEmail() );
         $this->CI->email->subject( 'Minimum zahteva nije ispunjen' );
         $data['review'] = $review;
         $data['height'] = 150;
		 $data['logo'] = APP_URL.'assets/img/layout/_backend/email_logo.png';
         
         $data['body'] = $this->CI->load->view( 'notifications/review/rejection_view', $data, true );
         $this->CI->email->message($this->CI->load->view( 'notifications/email_layout', $data, true ));
         
         return $this->CI->email->send();
     }
	 
	 public function send_customer_question( $question, $user ) {

	 	 $this->CI->email->to( $user->getEmail() );
         $this->CI->email->subject( 'Pitanja kupaca' );
		 $data['user'] = $user;
         $data['customer'] = $question->getUser();
		 $data['product'] = $question->getProduct();
		 $data['question'] = $question;
         $data['height'] = 150;
         
         $data['body'] = $this->CI->load->view( 'notifications/customer_question/question_view', $data, true );
         $this->CI->email->message($this->CI->load->view( 'notifications/email_layout', $data, true ));
         
         return $this->CI->email->send();
	 }
	 
	 public function send_question_approval( $question ) {

	 	 $this->CI->email->to( $question->getUser()->getEmail() );
         $this->CI->email->subject( 'Pitanja kupaca' );
		 $data['user'] = $question->getUser();
		 $data['product'] = $question->getProduct();
		 $data['question'] = $question;
         $data['height'] = 150;
         
         $data['body'] = $this->CI->load->view( 'notifications/customer_question/question_approval_view', $data, true );
         $this->CI->email->message($this->CI->load->view( 'notifications/email_layout', $data, true ));
         
         return $this->CI->email->send();
	 }
	 
	 public function send_customer_answer( $answer ) {

	 	 $this->CI->email->to( $answer->getQuestion()->getUser()->getEmail() );
         $this->CI->email->subject( 'Odgovor Kupca' );
		 $data['user'] = $answer->getQuestion()->getUser();
         $data['replier'] = $answer->getUser();
		 $data['product'] = $answer->getQuestion()->getProduct();
		 $data['answer'] = $answer;
         $data['height'] = 150;
         
         $data['body'] = $this->CI->load->view( 'notifications/customer_question/answer_view', $data, true );
         $this->CI->email->message($this->CI->load->view( 'notifications/email_layout', $data, true ));
         
         return $this->CI->email->send();
	 }
	 
	 public function send_answer_approval( $answer ) {

	 	 $this->CI->email->to( $answer->getUser()->getEmail() );
         $this->CI->email->subject( 'Pitanja kupaca' );
		 $data['user'] = $answer->getUser();
		 $data['product'] = $answer->getQuestion()->getProduct();
		 $data['answer'] = $answer;
         $data['height'] = 150;
         
         $data['body'] = $this->CI->load->view( 'notifications/customer_question/answer_approval_view', $data, true );
         $this->CI->email->message($this->CI->load->view( 'notifications/email_layout', $data, true ));
         
         return $this->CI->email->send();
	 }
	 
	 public function delete_question_info( $question ) {

	 	 $this->CI->email->to( $question->getUser()->getEmail() );
         $this->CI->email->subject( 'Pitanja kupaca' );
         $data['user'] = $question->getUser();
		 $data['product'] = $question->getProduct();
		 $data['question'] = $question;
         $data['height'] = 150;
         
         $data['body'] = $this->CI->load->view( 'notifications/customer_question/delete_question_view', $data, true );
         $this->CI->email->message($this->CI->load->view( 'notifications/email_layout', $data, true ));
         
         return $this->CI->email->send();
	 }
	 
	 public function delete_answer_info( $answer ) {

	 	 $this->CI->email->to( $answer->getUser()->getEmail() );
         $this->CI->email->subject( 'Pitanja kupaca' );
         $data['user'] = $answer->getUser();
		 $data['question'] = $answer->getQuestion();
		 $data['product'] = $answer->getQuestion()->getProduct();
		 $data['answer'] = $answer;
         $data['height'] = 150;
         
         $data['body'] = $this->CI->load->view( 'notifications/customer_question/delete_answer_view', $data, true );
         $this->CI->email->message($this->CI->load->view( 'notifications/email_layout', $data, true ));
         
         return $this->CI->email->send();
	 }
 }

 /* End of file Notification_Manager.php */
 /* Location: ./system/application/models/Notification_Manager.php */