<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * ...
 * @author Ivan Despic [ Codeion SA ]
 */
 use models\Entities\Comment;
 use models\Entities\CommentResponses;
 
 class Comments extends MY_Controller {
     
     public $gridParams=array(
        'id'                    => 'productsGrid',
        'width'                 => 'auto',
        'height'                => 400,
        'rp'                    => 40,
        'rpOptions'             => '[25,40,75,100]',
        'pagestat'              => 'Prikaz: {from} do {to} / Ukupno: {total} komentara.',
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

        $this->gridParams['title'] = 'Pregled svih komentara';

        $colModel['user_name'] = array( 'Ime korisnika', 100, TRUE, 'center', 1 );
		$colModel['type'] = array( 'Tip', 140, FALSE, 'center', 0 );
        //$colModel['subcategory'] = array( 'Podkategorija', 200, TRUE, 'center', 1 ); 
        $colModel['product'] = array( 'Rekord', 280, TRUE, 'center', 1 ); 
        $colModel['message'] = array( 'Komentar', 550, TRUE, 'center', 1 );
        $colModel['date'] = array( 'Datum', 140, TRUE, 'center', 1 );  
        $colModel['status'] = array( 'Status', 50, TRUE, 'center', 0 );
        $colModel['details'] = array( 'Detalji', 50, FALSE, 'center', 0 );
        $colModel['actions'] = array( 'Odgovori', 50, FALSE, 'center', 0 );
        $colModel['discussion'] = array( 'Diskusija', 50, FALSE, 'center', 0 );

        $buttons[] = array('Obriši komentar', 'delete', 'grid_commands', site_url("comments/delete"));
        $buttons[] = array('separator');
        $buttons[] = array('Izaberi sve', 'select_all', 'grid_commands', '/select');
		$buttons[] = array('separator');
        $buttons[] = array('Poništi izbor', 'deselect_all', 'grid_commands', '/desel');
        $buttons[] = array('separator');

        if( $this->input->post('page') ) $this->gridParams['newp'] = $this->input->post('page');
        $data['grid'] = build_grid_js('grid', site_url("comments/grid"), $colModel, 'id', 'ASC', $this->gridParams, $buttons);

        $data['grid_title'] = "Komentari";
        $this->_render_view("master/grid_view", $data);
     }
     
      public function grid() {
              
        $valid_fields = array('subcategory', 'product', 'date', 'status', 'user_name', 'message');

        $this->flexigrid->validate_post($this->gridParams['id'], 'date', 'DESC', $valid_fields);
        $criteria = $this->flexigrid->get_criteria();
        $records = $this->em->getRepository('models\Entities\Comment')->getComment($criteria);

        $this->session->unset_userdata('edit_visited');
        $this->output->set_header($this->config->item('json_header'));
        $this->output->set_output($this->flexigrid->json_build($records['record_count'], $records['record_items']));
      }
     
     public function change_status( $id ){
         
        $comment=$this->em->getRepository('models\Entities\Comment')->find($id);

		switch( get_class($comment)) {
        		
        	case 'models\Entities\Comment\ProductComment':
        		$comment->getStatus() ? 
        			$comment->getProduct()->setStatisticComments( $comment->getProduct()->getStatisticComments() - 1 ) : 
        			$comment->getProduct()->setStatisticComments( $comment->getProduct()->getStatisticComments() + 1 );
        	break;
			case 'models\Entities\Comment\NewsComment':
				$comment->getStatus() ? 
        			$comment->getNews()->setStatisticComments( $comment->getNews()->getStatisticComments() - 1 ) : 
        			$comment->getNews()->setStatisticComments( $comment->getNews()->getStatisticComments() + 1 );
        	break;
			case 'models\Entities\Comment\ArticleComment':
				$comment->getStatus() ? 
        			$comment->getArticle()->setStatisticComments( $comment->getArticle()->getStatisticComments() - 1 ) : 
        			$comment->getArticle()->setStatisticComments( $comment->getArticle()->getStatisticComments() + 1 );
        	break;
				
		}
		
		$comment->getStatus() ? $comment->setStatus(0) : $comment->setStatus(1);

		//$this->em->persist($data['comment']);
        $this->em->flush();

        $this->output->set_output($comment->getStatus());
     }
     
     public function details( $id ) {

        if ($data['comment']=$this->em->getRepository('models\Entities\Comment')->find($id)) {
            $this->resources['js'][]='checkbox';
            $this->_render_view('public_relations/edit_comment', $data);
        } else
            show_404();
     }
	 
     public function response_details( $id ) {

        if ($data['comment'] = $this->em->getRepository('models\Entities\Comment\Response')->find($id)) {
             $this->_render_view('public_relations/edit_response', $data);
        } else
            show_404();
     }
     
     public function edit( $id ) {

        if( $data['comment'] = $this->em->getRepository('models\Entities\Comment')->find($id) ) {
            	
			$this->resources['js'][]='checkbox';
			
			//echo 'klasa: '.get_class($data['comment']);
			//exit();	
            
			switch( get_class($data['comment'])) {
        		
	        	case 'models\Entities\Comment\ProductComment':
	        		$data['comment']->getStatus() ? 
	        			$data['comment']->getProduct()->setStatisticComments( $data['comment']->getProduct()->getStatisticComments() - 1 ) : 
	        			$data['comment']->getProduct()->setStatisticComments( $data['comment']->getProduct()->getStatisticComments() + 1 );
	        	break;
				case 'models\Entities\Comment\NewsComment':
					$data['comment']->getStatus() ? 
	        			$data['comment']->getNews()->setStatisticComments( $data['comment']->getNews()->getStatisticComments() - 1 ) : 
	        			$data['comment']->getNews()->setStatisticComments( $data['comment']->getNews()->getStatisticComments() + 1 );
	        	break;
				case 'models\Entities\Comment\ArticleComment':
					$data['comment']->getStatus() ? 
	        			$data['comment']->getArticle()->setStatisticComments( $data['comment']->getArticle()->getStatisticComments() - 1 ) : 
	        			$data['comment']->getArticle()->setStatisticComments( $data['comment']->getArticle()->getStatisticComments() + 1 );
	        	break;
					
			}
			  
            $data['comment']->setStatus( $this->input->post('active') );
            $data['comment']->setMessage( $this->input->post('message') );
            
            $this->em->persist($data['comment']);
            $this->em->flush();
            
            $data['message'] = '<p class="message_success">Sve izmene su uspešno izvršene!</p>';
            
            $this->_render_view( 'public_relations/edit_comment', $data );
			
        } else show_404();
     }
	 
     public function edit_response( $id ) {

        if( $data['comment']=$this->em->getRepository('models\Entities\Comment\Response')->find($id) ) {
            
            $data['comment']->setMessage( $this->input->post('message') );
            
            $this->em->persist($data['comment']);
            $this->em->flush();
            
            $data['message']='<p class="message_success">Sve izmene su uspešno izvršene!</p>';
            $this->resources['js'][]='checkbox';
            $this->_render_view( 'public_relations/edit_response', $data );
        }
        else show_404();
     }
     
     public function delete(){
        $id_list = explode( ',', $this->input->post('items') );
        
        $this->em->getRepository('models\Entities\Comment')->deleteComments($id_list);
        $this->output->set_output( TRUE ); 
     }
     
     public function listing_responses( $comment_id, $message=NULL ) {

        $this->resources['css'][]='flexigrid';
        $this->resources['js'][]='flexigrid';

        $this->gridParams['title']='Pregled svih odgovora';
       
        $colModel['date'] = array( 'Datum', 80, TRUE, 'center', 1 );  
        $colModel['message'] = array( 'Odgovor', 550, TRUE, 'center', 1 );
        $colModel['details']  = array( 'Detalji', 50, FALSE, 'center', 0 );

        $buttons[] = array('Obriši odgovor', 'delete', 'grid_commands', site_url("comments_responses/delete"));
        $buttons[] = array('separator');
        $buttons[] = array('Izaberi sve', 'select_all', 'grid_commands', '/select');
		$buttons[] = array('separator');
        $buttons[] = array('Poništi izbor', 'deselect_all', 'grid_commands', '/desel');
        $buttons[] = array('separator');

        $data['grid'] = build_grid_js('grid', site_url("comments/grid_responses/" . $comment_id), $colModel, 'id', 'ASC', $this->gridParams, $buttons);
        $id = $comment_id;
        $data['comment'] = $this->em->getRepository('models\Entities\Comment')->find($id);
        $data['comment_id'] = $comment_id;
        $data['grid_title'] = "Odgovori";
        If (isset($message)){$data['message'] = $message;}
        $this->_render_view("public_relations/comment_responses", $data);
     }
     
     public function grid_responses( $comment_id ){

         $valid_fields = array( 'date' );
         $this->flexigrid->validate_post('date', 'DESC', $valid_fields, $this->session->userdata('current_listing'));
         $criteria = $this->flexigrid->get_criteria();
         $records = $this->em->getRepository('models\Entities\CommentResponses')->getCommentResponses($criteria, $comment_id);

         $this->session->unset_userdata('edit_visited');
         $this->output->set_header($this->config->item('json_header'));
         $this->output->set_output($this->flexigrid->json_build($records['record_count'],$records['record_items']));
     }
     
     public function insert_response( $comment_id ){
         
         $response = new CommentResponses();
         $response->setMessage($this->input->post('message'));
         $data['comment_id'] = $response->setCommentId($comment_id);
         $this->em->persist($response);
         $this->em->flush();
         $message = '<p class="message_success"  style="width: 373px; padding: 8px 5px;">Novi odgovor je uspešno postavljen!</p>';
         $comment_id = $comment_id;
         $this->listing_responses($comment_id, $message);
     }
     
     public function delete_response() {
         $id_list=explode( ',', $this->input->post('items') );
    
         $this->em->getRepository('models\Entities\CommentResponses')->deleteResponses($id_list);
         $this->output->set_output( TRUE );
     }
     
     public function listing_by_record( $type, $record_id ){
            
        $this->resources['css'][]='flexigrid';
        $this->resources['js'][]='flexigrid';
 
        $this->gridParams['title']='Pregled svih komentara';

        $colModel['user_name'] = array( 'Ime korisnika', 140, TRUE, 'center', 1 ); 
        $colModel['message'] = array( 'Poruka', 800, TRUE, 'center', 1 );
        $colModel['date'] = array( 'Datum', 140, TRUE, 'center', 1 );  
        $colModel['status'] = array( 'Aktivan', 80, TRUE, 'center', 1 );
        $colModel['details']  = array( 'Detalji', 80, FALSE, 'center', 0 );
        $colModel['actions']  = array( 'Odgovori', 80, FALSE, 'center', 0 );

        $buttons[] = array('Obriši komentar', 'delete', 'grid_commands', site_url("comments/delete"));
        $buttons[] = array('separator');
        $buttons[] = array('Izaberi sve', 'select_all', 'grid_commands', '/select');
		$buttons[] = array('separator');
        $buttons[] = array('Poništi izbor', 'deselect_all', 'grid_commands', '/desel');
        $buttons[] = array('separator');

        $data['grid'] = build_grid_js('grid', site_url("comments/grid_by_record/" . $type.'/'.$record_id), $colModel, 'id', 'ASC', $this->gridParams, $buttons);

		switch( $type ) {
        		
        	case 1:
        		$data['product'] = $this->em->getRepository('models\Entities\Product')->find($record_id);
        		$data['grid_title'] = "Komentari::" . $data['product']->getSubcategory()->getName() . '-' . $data['product']->getName();
        	break;
			case 2:
				$data['news'] = $this->em->getRepository('models\Entities\News\Info')->find($record_id);
        		$data['grid_title'] = "Komentari::" . $data['news']->getTitle();
        	break;
			case 3:
				$data['article'] = $this->em->getRepository('models\Entities\Article')->find($record_id);
        		$data['grid_title'] = "Komentari::" . $data['article']->getTitle();
        	break;				
		}

        $this->_render_view("master/grid_view", $data);
    }
    
    public function grid_by_record($type, $record_id){
        
        $valid_fields = array('date', 'status', 'user_name', 'message');
        $this->flexigrid->validate_post('date', 'DESC', $valid_fields, $this->session->userdata('current_listing'));
        $criteria=$this->flexigrid->get_criteria();
        $records=$this->em->getRepository('models\Entities\Comment')->getCommentByRecord($criteria, $type, $record_id);

        $this->session->unset_userdata('edit_visited');
        $this->output->set_header($this->config->item('json_header'));
        $this->output->set_output($this->flexigrid->json_build($records['record_count'],$records['record_items']));
    }
 }
 
 /* End of file Comments.php */
 /* Location: ./system/application/_backend/controllers/comments.php */
