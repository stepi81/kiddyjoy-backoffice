<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */
 
 class SEO_Manager {
     
     private $CI;
     private $data;
     private $prefix;
     
     public function __construct() {
         
         $this->CI =& get_instance();
         $this->data = array();
         
         $this->prefix = 'Backoffice - ';
         $this->title = '';
         $this->description = '';
         $this->keywords = '';
     }
     
     public function __set( $name, $value ) {
         $this->data[$name] = $value;
     }
     
     public function __get( $name ) {
         if (array_key_exists($name, $this->data)) {
             switch( $this->CI->controller ) {
                 case 'home':
                    $this->title = $this->prefix.'Dobrodošli u KiddyJoy';
                    break;
             }
             return $this->data[$name];
         }
     }
 }
 
 /* End of file SEO_Manager.php */
 /* Location: ./system/applications/_backend/libraries/SEO_Manager.php */