<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */
 
 use models\Entities\Location;
 use models\Entities\Images\LocationImage;
 use models\Entities\Images\ProductImage;
 
 class Proxy extends CI_Controller {
     
    private $em;
     
    public function __construct() {
              
        parent::__construct();
        
        $this->load->library('Doctrine');
        $this->load->library('Thumb_Factory');
		$this->load->library('Resizer_Librarie');
        $this->em = $this->doctrine->em;
       
    }
    
     public function upload_product_images() {

        /**************************************************
         *************** IMAGE SERVER UPLOAD **************
         **************************************************/

        /* Upload Images on server */
        if (!empty($_FILES)) {
            
            $tempFile = $_FILES['Filedata']['tmp_name'];
            $targetPath = $_SERVER['DOCUMENT_ROOT'] . $_REQUEST['folder'] . '/';
            $file_name = explode('.',$_FILES['Filedata']['name']);
            //$unique_name =  uniqid('image');
            //$image_name = $unique_name . '.' . strtolower(end($file_name));
            $image_name = $_FILES['Filedata']['name'];
            $targetFile =  str_replace('//','/',$targetPath) . $image_name;

            move_uploaded_file($tempFile,$targetFile);
        }

        /*************************************************
        *************** IMAGE THUMB CREATE ***************
        **************************************************/
        
        /*
        $path = 'locations';
        $image = new LocationImage();

        $gallery_thumb = PhpThumbFactory::create( SERVER_PATH . "assets/img/$path/" . $image_name );

        $gallery_thumb->save( SERVER_PATH .  "assets/img/$path/" . $image_name );
        
        $gallery_thumb = PhpThumbFactory::create( SERVER_PATH . "assets/img/$path/" . $image_name );

        $gallery_thumb->adaptiveResize(425,232);
        $gallery_thumb->save( SERVER_PATH .  "assets/img/$path/thumb/" . $image_name );
        */
        
        
        /*if (copy( SERVER_PATH . '/assets/img/products/large/' . $image_name , SERVER_PATH . '/assets/img/products/thumb/' . $image_name)){ 
            
            $config['image_library'] = 'gd2';
            $config['source_image'] = SERVER_PATH . '/assets/img/products/thumb/' . $image_name;
            $config['maintain_ratio'] = TRUE;
            $config['width'] = 234;
            $config['height'] = 280;
    
            $this->load->library('image_lib');
            $this->image_lib->initialize($config);
            $this->image_lib->resize();  
        }
        
        if (copy( SERVER_PATH . '/assets/img/products/large/' . $image_name , SERVER_PATH . '/assets/img/products/small/' . $image_name)){ 
            
            $config['image_library'] = 'gd2';
            $config['source_image'] = SERVER_PATH . '/assets/img/products/small/' . $image_name;
            $config['maintain_ratio'] = TRUE;
            $config['width'] = 40;
            $config['height'] = 38;
    
            $this->load->library('image_lib');
            $this->image_lib->initialize($config);
            $this->image_lib->resize();  
        }
        
        if (copy( SERVER_PATH . '/assets/img/products/large/' . $image_name , SERVER_PATH . '/assets/img/products/medium/' . $image_name)){ 
            
            $config['image_library'] = 'gd2';
            $config['source_image'] = SERVER_PATH . '/assets/img/products/medium/' . $image_name;
            $config['maintain_ratio'] = TRUE;
            $config['width'] = 350;
            $config['height'] = 420;
    
            $this->load->library('image_lib');
            $this->image_lib->initialize($config);
            $this->image_lib->resize();  
        }*/
        
        $this->resizer_librarie->set(SERVER_PATH . '/assets/img/products/large/' . $image_name);
        $this->resizer_librarie->resize_image(350,420,'crop',SERVER_PATH . '/assets/img/products/medium/'.$image_name);
        
        $this->resizer_librarie->set(SERVER_PATH . '/assets/img/products/large/' . $image_name);
        $this->resizer_librarie->resize_image(234,280,'crop',SERVER_PATH . '/assets/img/products/thumb/'.$image_name);
		
		$this->resizer_librarie->set(SERVER_PATH . '/assets/img/products/large/' . $image_name);
        $this->resizer_librarie->resize_image(40,38,'crop',SERVER_PATH . '/assets/img/products/small/'.$image_name);
        
        /**************************************************
         ************* IMAGE DATABASE UPLOAD **************
         **************************************************/
        
        //get max position
        $max = $this->em->getRepository('models\Entities\Product')->getMaxImagePosition($_POST['product_id']); 
        
        //save image
       
        $product = $this->em->getRepository('models\Entities\Product')->find($_POST['product_id']);

        $image = new ProductImage();
        
        $image->setProduct( $this->em->getReference('models\Entities\Product', $product->getID() ));
        $image->setName( $image_name );
        $image->setPosition( $max[1] + 1 );
        
        $this->em->persist($image);
        $this->em->flush();

        echo "1"; //Responce that the upload is complete;
    }
    
    public function get_news_images( $news_id ) {
        
        // consider global function for TinyMCE images
        // params: id & entity class
        
        if( $images = $this->em->getRepository('models\Entities\News\Info')->getPageImages($news_id) ) {
            
            $result = 'var tinyMCEImageList = new Array(';
            foreach( $images as $img ) $result .= '["'.$img->getName().'", "'.$img->getURL().'"],';
            
            $this->output->set_output( rtrim($result, ',').');' );
        }
    }
	
	public function get_promotions_images( $promotion_id ) {
        
        // consider global function for TinyMCE images
        // params: id & entity class
        
        if( $images = $this->em->getRepository('models\Entities\Promotion\Page')->getPageImages($promotion_id) ) {
            
            $result = 'var tinyMCEImageList = new Array(';
            foreach( $images as $img ) $result .= '["'.$img->getName().'", "'.$img->getURL().'"],';
            
            $this->output->set_output( rtrim($result, ',').');' );
        }
    }
    
    public function get_informations_images( $page_id ) {
        
        // consider global function for TinyMCE images
        // params: id & entity class
        
        if( $images = $this->em->getRepository('models\Entities\InfoDesk')->getInformationsImages($page_id) ) {
            
            $result = 'var tinyMCEImageList = new Array(';
            foreach( $images as $img ) $result .= '["'.$img->getName().'", "'.$img->getURL().'"],';
            
            $this->output->set_output( rtrim($result, ',').');' );
        }
    }
    
    public function get_shopping_guide_images( $guide_id ) {
        
        // consider global function for TinyMCE images
        // params: id & entity class
        
        if( $images = $this->em->getRepository('models\Entities\ShoppingGuide\Guide')->getGuideImages($guide_id) ) {
            
            $result = 'var tinyMCEImageList = new Array(';
            foreach( $images as $img ) $result .= '["'.$img->getName().'", "'.$img->getURL().'"],';
            
            $this->output->set_output( rtrim($result, ',').');' );
        }
    }
    
    public function get_benchmark_images( $benchmark_id ) {
        
        // consider global function for TinyMCE images
        // params: id & entity class
        
        if( $images = $this->em->getRepository('models\Entities\Benchmark')->getBenchmarkImages($benchmark_id) ) {
            
            $result = 'var tinyMCEImageList = new Array(';
            foreach( $images as $img ) $result .= '["'.$img->getName().'", "'.$img->getURL().'"],';
            
            $this->output->set_output( rtrim($result, ',').');' );
        }
    }
    
	public function get_preorder_images( $preorder_id ) {
        
        if( $images = $this->em->getRepository('models\Entities\Preorder')->getPageImages($preorder_id) ) {
            
            $result = 'var tinyMCEImageList = new Array(';
            foreach( $images as $img ) $result .= '["'.$img->getName().'", "'.$img->getURL().'"],';
            
            $this->output->set_output( rtrim($result, ',').');' );
        }
    }
	
	public function get_article_images( $article_id ) {
        
        // consider global function for TinyMCE images
        // params: id & entity class
        
        if( $images = $this->em->getRepository('models\Entities\Article')->getArticleImages($article_id) ) {
            
            $result = 'var tinyMCEImageList = new Array(';
            foreach( $images as $img ) $result .= '["'.$img->getName().'", "'.$img->getURL().'"],';
            
            $this->output->set_output( rtrim($result, ',').');' );
        }
    }
	
    public function get_cities() {
        
        $cities = $this->em->getRepository('models\Entities\User\Customer')->getCities();
        $this->output->set_output( json_encode($cities) );
    }
	
	
	public function upload_location_images($type) {

        /**************************************************
         *************** IMAGE SERVER UPLOAD **************
         **************************************************/

        /* Upload Images on server */
        if (!empty($_FILES)) {
            
            $tempFile = $_FILES['Filedata']['tmp_name'];
            $targetPath = $_SERVER['DOCUMENT_ROOT'] . $_REQUEST['folder'] . '/';
            $file_name = explode('.',$_FILES['Filedata']['name']);
            $unique_name =  uniqid('image');
            $image_name = $unique_name . '.' . strtolower(end($file_name));
            $targetFile =  str_replace('//','/',$targetPath) . $image_name;

            move_uploaded_file($tempFile,$targetFile);
        }

        /*************************************************
        *************** IMAGE THUMB CREATE ***************
        **************************************************/
 
        $path = 'locations';
        $image = new LocationImage();

        $gallery_thumb = PhpThumbFactory::create( SERVER_PATH . "assets/img/$path/" . $image_name );

        $gallery_thumb->save( SERVER_PATH .  "assets/img/$path/" . $image_name );
        
        $gallery_thumb = PhpThumbFactory::create( SERVER_PATH . "assets/img/$path/" . $image_name );

        $gallery_thumb->adaptiveResize(425,232);
        $gallery_thumb->save( SERVER_PATH .  "assets/img/$path/thumb/" . $image_name );

        /**************************************************
         ************* IMAGE DATABASE UPLOAD **************
         **************************************************/
        
        //get max position
        $max = $this->em->getRepository('models\Entities\Location')->getMaxImagePosition($_POST['location_id']); 
        
        //save image
       
        $location = $this->em->getRepository('models\Entities\Location')->find($_POST['location_id']);

        $image->setLocation( $this->em->getReference('models\Entities\Location', $location->getID() ));
        $image->setName( $image_name );
        $image->setPosition( $max[1] + 1 );
        
        $this->em->persist($image);
        $this->em->flush();

        echo "1"; //Responce that the upload is complete;
    }
    
    public function get_products() {
        
        $cities = $this->em->getRepository('models\Entities\Product')->getProductsForBundles();
        $this->output->set_output( json_encode($cities) );
    }
	
    public function get_newsletter_images( $news_id ) {
        
        // consider global function for TinyMCE images
        // params: id & entity class
        
        if( $images = $this->em->getRepository('models\Entities\Newsletters')->getNewsletterImages($news_id) ) {
            
            $result = 'var tinyMCEImageList = new Array(';
            foreach( $images as $img ) $result .= '["'.$img->getName().'", "'.$img->getURL().'"],';
            
            $this->output->set_output( rtrim($result, ',').');' );
        }
    }

    public function form_newsletter() {
         
         $this->load->library('Notification_Manager');
		 
		 $current_date =  new \DateTime("now");
		 
		 foreach ($this->em->getRepository('models\Entities\Newsletters')->findAll() as $newsletter) {
		 
            if( $current_date->format('d.m.Y') == $newsletter->getSendDate() && $newsletter->getStatus() ) {
            	$newsletter->setStatus(0);
				$this->em->persist($newsletter);
            	$this->em->flush();
            	if( $newsletter->getUsersGroup() == 1 )
					foreach ($this->em->getRepository('models\Entities\User\Customer\Personal')->findAll() as $user) {
						if( $user->getNewsletter() ) $this->notification_manager->send_newsletter($user, $newsletter);
					}
				elseif( $newsletter->getUsersGroup() == 2 )
					foreach ($this->em->getRepository('models\Entities\User\Customer\Business')->findAll() as $user) {
						if( $user->getNewsletter() ) $this->notification_manager->send_newsletter($user, $newsletter);
					}
         	}
     	 }
         redirect();
    }
    
    public function points_management() {
    	
    	$order_id 	= $this->input->post('order_id');
    	$type 		= $this->input->post('type');
    	$action 	= $this->input->post('action');
    	
    	$order = $this->em->getRepository('models\Entities\Order')->getOrderByID( $order_id, $type );
    	$user = $order->getUser();
    	
    	if( $action ) {
    		$user->setPoints( $order->getPoints() );
    		$order->setStatus( ORDER_FINALIZED );
    	}
    	else {
    		$user->removePoints( $order->getPoints() );
    		$order->setStatus( ORDER_CANCELED );
    	}
    	
    	$this->em->flush();
    	
    	$this->output->set_output( $user->getPoints() );
    }
}
 
 /* End of file proxy.php */
 /* Location: ./system/applications/_backend/controllers/proxy.php */
