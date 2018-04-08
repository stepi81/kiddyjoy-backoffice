<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ...
 * @author Andrej The Savic [ ABC Design ]
 */
 
 class MY_Profiler extends CI_Profiler {
 	
 	public function __construct( $config = array() ) {
 		
 		$this->_available_sections[] = 'doctrine_output';
 		//$this->_available_sections[] = 'apc_output';
 		
 		parent::__construct();
 	}
 	
 	protected function _compile_doctrine_output() {
		
 		if( isset($this->CI->db) ) {
 			
	 		$output  = "\n\n";
			$output .= '<fieldset style="border:1px solid #009999;padding:6px 10px 10px 10px;margin:20px 0 20px 0;background-color:#eee">';
			$output .= "\n";
			$output .= '<legend style="color:#009999;"> DOCTRINE QUERIES('.count($this->CI->db->queries).') </legend>';
			$output .= "\n";
			
			if( !count($this->CI->db->queries) ) $output .= "<div style='color:#009999;font-weight:normal;padding:4px 0 4px 0'>".'No Query'."</div>";
			else {
				$output .= "\n\n<table cellpadding='4' cellspacing='1' border='1' width='100%'>\n";
				
				foreach($this->CI->db->queries as $query) {
					$output .= "<tr style='padding:2px'>
					<td width='10%' style='color:#000;background-color:#ddd;border-bottom:solid #ddd 10px;'>{$query['time']}</td>
					<td width='90%' style='color:#009999;font-weight:normal;background-color:#ddd;border-bottom:solid #ddd 10px;'>";
					if( $query['info'] ) $output .= "<span style='color:red'>{$query['info'][0]} >>> {$query['info'][1]}</span><br />";
					$output .= "{$query['query']}</td>\n</tr>\n";
				}
				$output .= "</table>\n";
			}
			$output .= "</fieldset>";
			
	 		return $output;
 		}
 	}
 	
 	protected function _compile_apc_output() {
		
 		if( isset($this->CI->db) ) {
 			
	 		$output  = "\n\n";
			$output .= '<fieldset style="border:1px solid #009999;padding:6px 10px 10px 10px;margin:20px 0 20px 0;background-color:#eee">';
			$output .= "\n";
			$output .= '<legend style="color:#009999;"> APC CACHE </legend>';
			$output .= "\n";
			
			// TODO
			
			$output .= "</fieldset>";
			
	 		return $output;
 		}
 	}
 }
 
 /* End of file MY_Profiler.php */
 /* Location: ./system/applications/_frontend/core/MY_Profiler.php */