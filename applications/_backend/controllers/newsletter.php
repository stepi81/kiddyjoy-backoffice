<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * ...
 * @author Ivan Despic [ Codeion ]
 */

use models\Entities\Newsletters;

class Newsletter extends MY_Controller {

    public $gridParams = array(
        'id'                 => 'productsGrid',
        'width'              => 'auto', 
        'height'             => 400, 
        'rp'                 => 15, 
        'rpOptions'          => '[10,15,20,25,40]', 
        'pagestat'           => 'Prikaz: {from} do {to} / Ukupno: {total} newslettera.', 
        'pagetext'           => 'Stranica', 
        'outof'              => 'od', 
        'findtext'           => 'Pronađi', 
        'procmsg'            => 'Obrada u toku, molimo sa�?ekajte...', 
        'blockOpacity'       => 0.5, 
        'showTableToggleBtn' => true);

    public function __construct() {

        parent::__construct();

        $this->load->helper(array('form', 'url'));

        $this->load->helper('flexigrid');
        $this->load->helper('upload');
        $this->load->helper('tinymce');

        $this->load->library('Flexigrid');

        $this->resources['css']=array();
        $this->resources['js']=array();
    }
    
    public function demo( $offset = 0 ) {
	
	ini_set('memory_limit', '256M');
	set_time_limit(0);
	
	$users = $this->em->getRepository('models\Entities\User\Customer')->getNewsletterUsers($offset);
        
	//$users[] = array('id' => 86341,'email'=>'dejan.blagaic@comtradeshop.com');
	//$users[] = array('id' => 86341,'email'=>'andrej@codeion.com');
	//$users[] = array('id' => 86341,'email'=>'damir@codeion.com');
	//echo var_dump($users);
    	
		$config = Array(		
		    'protocol' => 'smtp',
		    'smtp_host' => 'smtp.critsend.com',
		    'smtp_port' => 25,
		    'smtp_user' => 'kiddyjoy.online@gmail.com',
		    'smtp_pass' => 'a40U1GwQJjF4V',
		    'mailtype'  => 'html', 
		    'charset'   => 'utf-8'
		);
		
		$this->load->library('email', $config);
		$this->email->set_newline("\r\n");
		$this->email->set_crlf("\r\n");
		
		$this->email->from( 'noreply@kiddyjoy.com', 'www.kiddyjoy.com' );
		$this->email->subject('KiddyJoy Newsletter');
		
		$data['subject'] = 'KiddyJoy Newsletter';
		$data['message'] = '<img src="'.assets_url('img/newsletter/novembar-dnevne-akcije-2013-kiddyjoy.jpg').'" alt="Prikažite sliku" />';
		
		//echo $this->email->print_debugger();
    	
		foreach( $users as $user ) {
			$data['unsubscribe'] = APP_URL.'newsletter/odjava/'.$this->encrypt_decrypt($user['id']);
			$this->email->to($user['email']);
			$this->email->message( $this->load->view( 'notifications/newsletter_view', $data, true ) );
				$this->email->send();
		}
		
		if( count($users) ) echo '<a href="'.site_url('newsletter/demo').'/'.($offset+5000).'">salji dalje</a> '.($offset+5000).' od ukupno 48.249';
    }
    
    private function encrypt_decrypt($user) {
	
	    //hex decription  
	
	    $action = true; 
	    $output = false;
	    $key = '*!#KiDdYjOy#!*';
	
	    // initialization vector 
	    $iv = md5(md5($key));
	
	    $encrypt_method = "AES-256-CBC";
	    $secret_key = $key;
	    $secret_iv = md5(md5($key));
	
	    // hash
	    $key = hash('sha256', $secret_key);
	    $iv = hash('sha256', $secret_iv);
	
	    if( $action ) {
	        $string = @openssl_encrypt($user, $encrypt_method, $key, $iv);
	        $string = base64_encode($string);
	     
	     $output = '';
	     for ($i=0; $i<strlen($string); $i++){
	          $ord = ord($string[$i]);
	          $hexCode = dechex($ord);
	          $output .= substr('0'.$hexCode, -2);
	     } 
	     $output = strToUpper($output);
	    } else {
	     
	        $string='';
	     for ($i=0; $i < strlen($user)-1; $i+=2){
	          $string .= chr(hexdec($user[$i].$user[$i+1]));
	     } 
	        $output = $decryptedMessage = openssl_decrypt(base64_decode($string), $encrypt_method, $key, $iv);
	    }
	
	    return $output;
	 }

    public function listing() {

        $this->resources['css'][]='flexigrid';
        $this->resources['js'][]='flexigrid';

        $this->gridParams['title']='Pregled';

        $colModel['send_date']=array( 'Datum slanja', 100, TRUE, 'center', 1 );
        $colModel['template'] =array( 'Template', 120, TRUE, 'center', 1 );
        $colModel['users_group'] =array( 'Grupa korisnika', 120, TRUE, 'center', 1 );
        $colModel['title'] =array( 'Naslov', 180, TRUE, 'center', 1 );
        $colModel['status'] =array( 'Status', 80, TRUE, 'center', 1 );
        $colModel['details']  =array( 'Detalji', 80, FALSE, 'center', 0 );

        $buttons[]=array('Novi newsletter', 'add', 'grid_commands', site_url("newsletter/create"));
        $buttons[]=array('separator');
        $buttons[]=array('Obriši newsletter', 'delete', 'grid_commands', site_url("newsletter/delete"));
        $buttons[]=array('separator');
        $buttons[]=array('Izaberi sve', 'select_all', 'grid_commands', '/select');
        $buttons[]=array('separator');
        $buttons[]=array('Poništi izbor', 'deselect_all', 'grid_commands', '/desel');
        $buttons[]=array('separator');
                
        if( $this->input->post('page') ) $this->gridParams['newp'] = $this->input->post('page');
        $data['grid'] = build_grid_js('grid', site_url('newsletter/grid'), $colModel, 'id', 'ASC', $this->gridParams, $buttons);
      
        $data['grid_title']="Newsletter";

        $this->session->set_userdata('return_uri',uri_string());

        $this->_render_view( 'master/grid_view', $data );
    }

    public function grid() {

        $valid_fields = array('send_date', 'template', 'users_group', 'title', 'status', );

        $this->flexigrid->validate_post($this->gridParams['id'], 'send_date', 'DESC', $valid_fields);
        $criteria=$this->flexigrid->get_criteria();
        $records=$this->em->getRepository('models\Entities\Newsletters')->getNewsletter($criteria);

        $this->session->unset_userdata('edit_visited');
        $this->output->set_header($this->config->item('json_header'));
        $this->output->set_output($this->flexigrid->json_build($records['record_count'], $records['record_items']));
    }

    public function create() {

        $this->resources['css'][]='datepicker';

        $this->_render_view('public_relations/create_newsletter');
    }

    public function save() {
        
        $data['newsletter'] = new Newsletters();

        $data['newsletter']->setSendDate( new \DateTime(date( 'Y-m-d', strtotime( $this->input->post('send_date'))))); 
        $data['newsletter']->setTemplate( 'classic' );
        $data['newsletter']->setUsersGroup( $this->input->post('users_group') );
        $data['newsletter']->setTitle( $this->input->post('title') );
        
        $userGroup = $data['newsletter']->getUsersGroup();
        
        switch ($userGroup) {
            case '1':
                $data['userGroup']='Privatni';
                break;
            case '2':
                $data['userGroup']='Poslovni';
                break;
            case '3':
                $data['userGroup']='Newsletter korisnici';
                break;
        }
    
        $this->em->persist($data['newsletter']);
        $this->em->flush();
        
        $this->resources['css'][] = 'plupload';
        $this->resources['css'][] = 'datepicker';
        $this->resources['js'][] = 'checkbox';
        $this->resources['js'][] = 'tiny_mce';
        $this->resources['js'][] = 'plupload_full';
        $this->resources['js'][] = 'plupload_queue';
        
        $data['plupload'] = build_plupload_js( site_url('upload/newsletter/'.$data['newsletter']->getID()) );
        $data['tinymce'] = build_tinymce_js('page', 600, 700, site_url('proxy/get_newsletter_images/'.$data['newsletter']->getID()));
        
        $this->_render_view( 'public_relations/edit_newsletter', $data );
    }

    public function details( $id ) {

        if( $data['newsletter']=$this->em->getRepository('models\Entities\Newsletters')->find($id) ) {
            
            $userGroup = $data['newsletter']->getUsersGroup();
       
            switch ($userGroup) {
                case '1':
                    $data['userGroup'] = 'Privatni';
                    break;
                case '2':
                    $data['userGroup'] = 'Poslovni';
                    break;
                case '3':
                    $data['userGroup'] = 'Newsletter korisnici';
                    break;
            }
        
            $this->resources['css'][] = 'plupload';
            $this->resources['css'][] = 'datepicker';
            $this->resources['js'][] = 'checkbox';
            $this->resources['js'][] = 'tiny_mce';
            $this->resources['js'][] = 'plupload_full';
            $this->resources['js'][] = 'plupload_queue';
            
            $data['plupload'] = build_plupload_js( site_url('upload/newsletter/'.$data['newsletter']->getID()) );
            $data['tinymce'] = build_tinymce_js('page', 600, 700, site_url('proxy/get_newsletter_images/'.$id));
            
            $this->_render_view( 'public_relations/edit_newsletter', $data );
        }
        else show_404();
    }

    public function edit($id) {

        if( $data['newsletter'] = $this->em->getRepository('models\Entities\Newsletters')->find($id) ) {

            // TODO server validation
            $data['newsletter']->setTemplate( 'classic' );
            $data['newsletter']->setUsersGroup( $this->input->post('users_group') );
            $data['newsletter']->setTitle( $this->input->post('title') );
            $data['newsletter']->setSendDate( new \DateTime(date( 'Y-m-d', strtotime( $this->input->post('send_date'))))); 
            $data['newsletter']->setStatus( $this->input->post('status') );
            $data['newsletter']->setTitle( $this->input->post('title') );
            $data['newsletter']->setMessage( $this->input->post('message'));
            
            $userGroup = $data['newsletter']->getUsersGroup();
            switch ($userGroup) {
                case '1':
                    $data['userGroup']='Privatni';
                    break;
                case '2':
                    $data['userGroup']='Poslovni';
                    break;
                case '3':
                    $data['userGroup']='Newsletter korisnici';
                    break;
            }
			
			$users = $this->user_model->getUsersForNewsletter($data['newsletter']->getUsersGroup());
			
            $this->em->persist($data['newsletter']);
            $this->em->flush();
            
            $data['message']='<p class="message_success">Sve izmene su uspešno izvršene!</p>';

            $this->resources['css'][] = 'plupload';
            $this->resources['css'][] = 'datepicker';
            $this->resources['js'][] = 'checkbox';
            $this->resources['js'][] = 'tiny_mce';
            $this->resources['js'][] = 'plupload_full';
            $this->resources['js'][] = 'plupload_queue';
            
            $data['plupload'] = build_plupload_js( site_url('upload/newsletter/'.$data['newsletter']->getID()) );
            $data['tinymce'] = build_tinymce_js('page', 600, 700, site_url('proxy/get_news_images/'.$id));
            
            $this->_render_view( 'public_relations/edit_newsletter', $data );
			
			$this->load->library('Notification_Manager');
			
			foreach( $users as $user ) {
				$this->notification_manager->send_newsletter( $user, $this->input->post('title'), $this->input->post('message') );
			}
        }
        else show_404();
     }

	 public function change_status($id) {

        $newsletter = $this->em->getRepository('models\Entities\Newsletters')->find($id);
        $newsletter->getStatus() ? $newsletter->setStatus(0) : $newsletter->setStatus(1);

        $this->em->flush();
        $this->output->set_output($newsletter->getStatus());
     }

     public function delete() {
        
        $id_list = explode( ',', $this->input->post('items') );
        
        $this->em->getRepository('models\Entities\Newsletters')->deleteNewsletter($id_list);
        $this->output->set_output( TRUE );
     }
    
    public function preview_newsletter(){

       // $folder = 'comtradeshop';
      $data['subject'] = $this->input->post('subject');
      $data['message'] = $this->input->post('message');
      $data['logo'] = img_url( 'layout/_backend/email_logo.png' );
	  $data['request_token'] = '';
        
      $this->output->set_output($this->load->view( 'notifications/newsletter/classic', $data, true ));
    }
}

/* End of file newsletter.php */
/* Location: ./system/applications/_backend/controllers/newsletter.php */