<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */
 
 require_once 'abstract_user.php';
 
 use models\Entities\User\NewsletterUser;
 
 class Newsletter_User extends Abstract_User {
     
     public function __construct() {
         
         parent::__construct();

         $this->resources['css'] = array();
         $this->resources['js'] = array();
		 
		 $this->type = USER_TYPE_NEWSLETTER;
     }
     
     public function listing() {
         
         $this->resources['css'][] = 'flexigrid';
         $this->resources['js'][] = 'flexigrid';
         
         $this->gridParams['title'] = 'Pregled newsletter korisnika';

         $colModel['email']			= array( 'E-mail', 200, TRUE, 'center', 1 );

         $data['grid'] = build_grid_js('grid', site_url("users/newsletter_user/grid"), $colModel, 'id', 'ASC', $this->gridParams, $buttons = '');
         $data['grid_title'] = "Newsletter korisnici";
         
         $this->_render_view( "master/grid_view", $data );
     }
 }
 
 /* End of file newsletter_user.php */
 /* Location: ./system/applications/_backend/controllers/users/newsletter_user.php */