<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */

 use models\Entities\User\Admin;
 
 class Auth_Manager {
    
    private static $user;
    private $CI;
    
    function __construct() {
        
        $this->CI =& get_instance();
    }
    
    public function user() {
        
        if( !isset(self::$user) ) {
            
            if( !$user_id = $this->CI->session->userdata('user_id') ) {
                return FALSE;
            }
            
            if( !$user_obj = $this->CI->user_model->find($user_id) ) {
                return FALSE;
            }
            
            self::$user = $user_obj;
        }
        
        return self::$user;
    }
    
    /**
     * Login
     *
     * @access  public
     * @param   string
     * @param   string
     * @return  boolean
     */
    public function login( $email, $password ) {
        
        if( $user_obj = $this->CI->user_model->findOneBy(array( 'email' =>$email)) ) {    
            
            $u_input = new Admin();
            $u_input->setPassword($password);
            
            if( $user_obj->getPassword() == $u_input->getPassword() ) {
                
                unset($u_input);
                
                $this->CI->session->set_userdata( 'user_id', $user_obj->getID() );
                self::$user = $user_obj;
                
                return TRUE;
            }
			    
            unset($u_input);
        }
		
        return FALSE;
    }
	
	/**
     * Section Access
     *
     * @access  public
     * @return  boolean
     */
	public function allowAccess() {
		
		if( in_array($this->CI->controller, array('home', 'account')) ) return TRUE;
		else {		
			$sections = self::$user->getGroup()->getSections();
			foreach( $sections as $section ) {
				if( $section->getChildren()->count() ) {
					foreach( $section->getChildren() as $subsection ) {
						if( $this->CI->controller == basename($subsection->getController()) ) return TRUE;
					}
				}
				else if( $this->CI->controller == basename($section->getController()) ) return TRUE;
			}
			return FALSE;
		}
	}
    
    public function __clone() {
        
        trigger_error('Clone is not allowed.', E_USER_ERROR);
    }   
 }
 
 /* End of file Auth_Manager.php */
 /* Location: ./system/applications/_backend/libraries/Auth_Manager.php */