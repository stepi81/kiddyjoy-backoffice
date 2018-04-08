<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ...
 * @author Andrej The Savic [ ABC Design ]
 */
 
 use Doctrine\Common\EventArgs;
 
 class Activity_Manager {
     	
     private $CI;
     
     public function __construct() {
         	
         $this->CI =& get_instance();
		 $this->CI->evm->addEventListener(array(ACTIVITY_EVENT), $this);
     }

	 public function activity_event( EventArgs $e = NULL ) {
	 	
		switch( $e->type ) {
			
			case ACTIVITY_PRODUCT:
				$activity = new models\Entities\User\Activities\Activity_Product();
				break;
			case ACTIVITY_BUNDLE:
				$activity = new models\Entities\User\Activities\Activity_Bundle();
				break;
			case ACTIVITY_HIGHLIGHT_SLIDESHOW:
				$activity = new models\Entities\User\Activities\Activity_Product();
				break;
			case ACTIVITY_HIGHLIGHT_MENU:
				$activity = new models\Entities\User\Activities\Activity_Product();
				break;
			default:
				// TODO
		}
		
		$activity->setOperation( $e->operation );
		$activity->setProcess( $e->process );
		$activity->setAdmin( $this->CI->auth_manager->user() );
		$activity->setRecord( $e->record );
		$activity->setDate();
		
	 	try {
			$this->CI->em->persist($activity);
			$this->CI->em->flush();
		}
		catch( \Doctrine\ORMException $e ) {
			// TODO
		}
	 }
 }
 
 /* End of file Activity_Manager.php */
 /* Location: ./system/applications/_backend/libraries/Activity_Manager.php */