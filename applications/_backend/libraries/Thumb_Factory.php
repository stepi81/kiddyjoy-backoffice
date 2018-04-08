<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 * @author: Marko Stepanovic [ Codeion SA ]
 */

class Thumb_Factory {
    
    public function __construct() {
        
        require_once APPPATH.'/libraries/image/ThumbLib.inc.php';
    }
 
}

/* End of file Thumb_Factory.php */
/* Location: ./application/_backend/libraries/Thumb_Factory.php */