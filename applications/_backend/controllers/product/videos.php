<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * ...
 * @author Ivan Despic [ Codeion ]
 */
 
 use models\Entities\Product;
 use models\Entities\Product\Video;
  
 class Videos extends MY_Controller {
     
     public $gridParams = array(
        'id'                    => 'videosGrid',
        'width'                 => 'auto',
        'height'                => 400,
        'rp'                    => 15,
        'rpOptions'             => '[10,15,20,25,40]',
        'blockOpacity'          => 0.5,
        'showTableToggleBtn'    => true
     );
     
     public function __construct() {
         
        parent::__construct();
         
        $this->load->helper('flexigrid');
        $this->load->library('Flexigrid');
        $this->load->helper('tinymce');
         
        $this->resources['css'] = array();
        $this->resources['js'] = array();
     }

     public function listing( $product_id ) {

        $data['product'] = $this->em->getRepository('models\Entities\Product')->find($product_id);

        $this->resources['css'][] = 'flexigrid';
        $this->resources['js'][] = 'flexigrid';

        $this->gridParams['title'] = 'Pregled svih videa';
        
        $colModel['title']  = array( 'Naziv', 270, TRUE, 'center', 1 );
        $colModel['position'] = array('Pozicija', 50, TRUE, 'center', 1);
        $colModel['actions'] = array( 'Detalji', 50, FALSE, 'center', 0 );  

        $buttons[] = array('Obriši', 'delete', 'grid_commands', site_url("product/videos/delete/$product_id"));
        $buttons[] = array('separator');
        $buttons[] = array('Izaberi sve', 'select_all', 'grid_commands', '/select');
        $buttons[] = array('Poništi izbor', 'deselect_all', 'grid_commands', '/desel');
        $buttons[] = array('separator');

        if( $this->input->post('page') ) $this->gridParams['newp'] = $this->input->post('page');

        $data['grid'] = build_grid_js('grid', site_url("product/videos/grid/" . $product_id), $colModel, 'position', 'ASC', $this->gridParams, $buttons);

        $data['grid_title'] = "Video"; 
        
        $this->_render_view( 'product/video/videos_view', $data ); 
     }
     
     public function grid( $product_id ) {
          
        $valid_fields = array('position', 'title');
         
        $this->flexigrid->validate_post($this->gridParams['id'], 'position', 'ASC', $valid_fields);
        $criteria = $this->flexigrid->get_criteria();
     
        $records = $this->em->getRepository('models\Entities\Product')->getProductVideos( $criteria, $product_id );
         
        $this->session->unset_userdata('edit_visited');
        $this->output->set_header($this->config->item('json_header'));
        $this->output->set_output($this->flexigrid->json_build($records['record_count'],$records['record_items']));
     }
     
     public function insert( $product_id ) {

        $product = $this->em->getRepository('models\Entities\Product')->find($product_id);
        
        if( count( $product->getVideos() ) ) {
           $maxPosition = $product->getVideos()->last()->getPosition();    
        } else {
           $maxPosition = 0;    
        }

        $position = $this->input->post('position');
         
        if ($position) {
           if ($position >= $maxPosition) {
               $position = $maxPosition + 1;
           } else {
               foreach ($product->getVideos() as $video) {
                   $adNowPosition = $video->getPosition();
                   if ($position <= $adNowPosition) {
                       $video->setPosition($adNowPosition + 1);
                       $this->em->persist($video);
                       $this->em->flush();
                   }
               }
           }
        } else {
           $position=$maxPosition + 1;
        }
         
        $video = new Video();
         
        $video->setProduct($product);
        $video->setCode( $this->input->post('video_id') );
        $video->setTitle( $this->input->post('video_name') );
        $video->setPosition( $position );    
        
        $this->em->persist($video);
        $this->em->flush();
         
        $this->listing( $product_id );
     }
     
     public function details( $id ) {
        
        if( $data['video'] = $this->em->getRepository('models\Entities\Product\Video')->find($id) ) {
            $this->_render_view( "product/video/edit_video", $data );
        }
        else show_404();
     }
     
     public function edit( $id ) {

        if ($data['video'] = $this->em->getRepository('models\Entities\Product\Video')->find($id)) {

            $product = $this->em->getRepository('models\Entities\Product')->find($data['video']->getProduct()->getID());
            
            if( count( $product->getVideos() ) ) {
                $maxPosition = $product->getVideos()->last()->getPosition();    
             } else {
                $maxPosition = 0;    
             }
            
            $position = $this->input->post('position');

            if ($position <= $data['video']->getPosition()) {
                foreach ($product->getVideos() as $video) {
                    if ($video->getPosition() >= $position && $video->getPosition() < $data['video']->getPosition())
                        $video->setPosition($video->getPosition() + 1);
                        $this->em->persist($video);
                        $this->em->flush();
                    }
            } else {
                if ($position >= $maxPosition) {
                    $position = $maxPosition;
                }
                foreach ($product->getVideos() as $video) {
                    if ($video->getPosition() <= $position && $video->getPosition() > $data['video']->getPosition())
                        $video->setPosition($video->getPosition() - 1);
                        $this->em->persist($video);
                        $this->em->flush();
                }
            }

            $data['video']->setTitle($this->input->post('video_name'));
            $data['video']->setCode($this->input->post('video_id'));
            $data['video']->setPosition($position);
            
            $this->em->persist($data['video']);
            $this->em->flush();
            
            $data['message'] = '<p class="message_success">Video je izmenjen!</p>';
            $this->_render_view('product/video/edit_video', $data);
        } 
        else show_404();
    }
    
    public function delete( $product_id ) {
        
        $product = $this->em->getRepository('models\Entities\Product')->find($product_id);
        
        $id_list=explode(',', $this->input->post('items'));

        foreach ($id_list as $id) {
            $video = $this->em->getRepository('models\Entities\Product\Video')->find($id);
            foreach ($product->getVideos() as $video_data) {
                if ($video->getPosition() < $video_data->getPosition()) {
                    $video_data->setPosition($video_data->getPosition() - 1);
                    $this->em->persist($video_data);
                    $this->em->flush();
                }
            }
        }
        $this->em->getRepository('models\Entities\Product')->deleteVideo($id_list);
        $this->output->set_output(TRUE);            
    }
}

/* End of file videos.php */
/* Location: ./system/applications/_backend/controllers/settings/videos.php */