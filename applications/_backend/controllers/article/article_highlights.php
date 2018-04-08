<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * ...
 * @author Marko Stepanovic [ Codeion ]
 */
 
 use models\Entities\Article\Highlight;
 use models\Entities\Article;
 
 class Article_Highlights extends MY_Controller {
     
    public $gridParams = array(
        'id'                    => 'articleHighlightGrid',
        'width'                 => 'auto',
        'height'                => 400,
        'rp'                    => 15,
        'rpOptions'             => '[10,15,20,25,40]',
        'pagestat'              => 'Prikaz: {from} do {to} / Ukupno: {total}.',
        'pagetext'              => 'Stranica',
        'outof'                 => 'od',
        'findtext'              => 'Pronađi',
        'procmsg'               => 'Obrada u toku, molimo sačekajte...',
        'blockOpacity'          => 0.5,
        'showTableToggleBtn'    => true
    );
     
    public function __construct() {
         
        parent::__construct();
        
        $this->load->helper(array('form', 'url'));
        $this->load->helper('flexigrid');

        $this->load->library('Flexigrid');
         
        $this->resources['css'] = array();
        $this->resources['js'] = array();
    }
     
    public function listing( $category_id ) {
            
        $this->resources['css'][] = 'flexigrid';
        $this->resources['js'][] = 'flexigrid';
		$this->resources['js'][] = 'checkbox';
		
		$data['article_category'] = $this->em->getRepository('models\Entities\Article\Category')->find($category_id);
		
		$this->gridParams['title'] = 'Pregled highlighta - '.$data['article_category']->getName();
        $data['grid_title'] = "IZdvajamo - ".$data['article_category']->getName(); 

        $colModel['position']  = array( 'Pozicija', 80, TRUE, 'center', 1 );
		$colModel['type']  = array( 'Tip', 80, TRUE, 'center', 1 );
        $colModel['article'] = array( 'Blog', 250, TRUE, 'center', 1 );
		
		$buttons[] = array('Obriši iz izdvajamo', 'delete', 'grid_commands', site_url("article/article_highlights/delete/"));
        $buttons[] = array('separator');
        $buttons[] = array('Izaberi sve', 'select_all', 'grid_commands', '/select');
        $buttons[] = array('separator');
        $buttons[] = array('Poništi izbor', 'deselect_all', 'grid_commands', '/desel');
        $buttons[] = array('separator');
 
        if( $this->input->post('page') ) $this->gridParams['newp'] = $this->input->post('page');
        $data['grid'] = build_grid_js('grid', site_url("article/article_highlights/grid/".$category_id), $colModel, 'position', 'ASC', $this->gridParams, $buttons);

        $this->_render_view( "article/create_highlight_grid", $data );
    }
  
    public function grid($category_id) {
          
         $valid_fields = array( 'name', 'position', 'status' );
         
         $this->flexigrid->validate_post($this->gridParams['id'], 'position', 'ASC', $valid_fields);
         $criteria = $this->flexigrid->get_criteria();

         $records = $this->em->getRepository('models\Entities\Article\Highlight')->getHighlights( $criteria, $category_id );
         
         $this->session->unset_userdata('edit_visited');
         $this->output->set_header($this->config->item('json_header'));
         $this->output->set_output($this->flexigrid->json_build($records['record_count'],$records['record_items']));
    }
	
	public function save() {
		
		if ( $article = $this->em->getRepository('models\Entities\Article')->find( $this->input->post('article_id') ) ) {
		
			if( $article->getCategory()->getID() == $this->input->post('category_id') ) {
			
				$data['highlight'] = new Highlight(); 
					
		        $position = $this->input->post('position');	        
		
		        $maxHighlight = $this->em->getRepository('models\Entities\Article\Highlight')->getMaxPosition($this->input->post('category_id'), $this->input->post('type'));
		        $records = $this->em->getRepository('models\Entities\Article\Highlight')->getHighlightsByCategory($this->input->post('category_id'), $this->input->post('type'));
		
		        $maxPosition = $maxHighlight[0][1];
		
		        if ($position) {
		            if ($position >= $maxPosition) {
		                $position = $maxPosition + 1;
		            } else {
		                foreach ($records as $record) {
		                    if ($position <= $record->getPosition()) {
		                        $record->setPosition($record->getPosition() + 1);
		                        $this->em->persist($record);
		                        $this->em->flush();
		                    }
		                }
		            }
		        } else {
		            $position = $maxPosition + 1;
		        }
		
				$data['highlight']->setArticle($this->em->getReference('models\Entities\Article', $this->input->post( 'article_id' )));
				$data['highlight']->setType( $this->input->post('type') );
		        $data['highlight']->setPosition( $position );
			
				$this->em->persist($data['highlight']);
				$this->em->flush();
				
				$this->session->set_flashdata('create_highlight_message', '<p class="message_success" style="width: 250px; padding: 8px 5px;">Uspešno ste setovali highlight</p>');
			} else {
				$this->session->set_flashdata('create_highlight_message', '<p class="message_error" style="width: 250px; padding: 8px 5px;">Blog ne pripada ovoj kategoriji, pokušajte ponovo!</p>');
			}
			
		} else {
			$this->session->set_flashdata('create_highlight_message', '<p class="message_error" style="width: 250px; padding: 8px 5px;">Ne postoji blog sa unetim id-em, pokušajte ponovo!</p>');	
		}

		redirect('article/article_highlights/listing/'.$this->input->post('category_id'));		
	}
	
	public function delete() {
		
		$id_list = explode( ',', $this->input->post('items') );
		
		foreach ($id_list as $id) {
            $highlight = $this->em->getRepository('models\Entities\Article\Highlight')->find($id);
            $records = $this->em->getRepository('models\Entities\Article\Highlight')->getHighlightsByCategory($highlight->getArticle()->getCategory()->getID(), $highlight->getType());
            foreach ($records as $record) {
                $oldPosition = $record->getPosition();
                if ($highlight->getPosition() < $record->getPosition()) {
                    $record->setPosition($oldPosition - 1);
                    $this->em->persist($record);
                    $this->em->flush();
                }
            }
        }
		
		$this->em->getRepository('models\Entities\Article\Highlight')->deleteHighlights($id_list);
		$this->output->set_output( TRUE );
	 }

 }
 
 /* End of file article_highlightss.php */
 /* Location: ./system/applications/_backend/controllers/article/article_highlightss.php */