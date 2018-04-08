<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */
 
 class Navigation_Manager {
     
     private $CI;
     
     public function __construct() {
         
         $this->CI =& get_instance();
     }
	 
	 public function menu() {
	 	
		if( $user = $this->CI->auth_manager->user() ) {
			$sections = $user->getGroup()->getSections();
			echo '<h3><a href="'.site_url().'">Naslovna</a></h3>'."\n";
			foreach( $sections as $section ) {
				if( $this->CI->session->userdata('application_id') ) { if( $this->CI->session->userdata('application_id') == 'mobile' ) { $visibility = $section->getMobileVisibility(); } else { $visibility = $section->getVendorVisibility(); } } else { $visibility = $section->getVisibility(); }
				if( $visibility ) {
				//if( $this->CI->session->userdata('application_id') ? $section->getVendorVisibility() : $section->getVisibility() ) {
					if( $section->getChildren()->count() ) {
						echo "\t\t".'<h3 class="head"'.$this->activeSection($section).'><a href="#">'.$section->getName().'</a></h3>'."\n";
						echo "\t\t\t".'<div>'."\n";
						echo "\t\t\t\t".'<ul>'."\n";
						foreach( $section->getChildren() as $subsection ) {
							//if( $this->CI->session->userdata('application_id') ? $subsection->getVendorVisibility() : $subsection->getVisibility() ) echo "\t\t\t\t\t".'<li><a href="'.$subsection->getURI().'">'.$subsection->getName().'</a></li>'."\n";
							if( $this->CI->session->userdata('application_id') ) { if( $this->CI->session->userdata('application_id') == 'mobile' ) { $subsection_visibility = $subsection->getMobileVisibility(); } else { $subsection_visibility = $subsection->getVendorVisibility(); } } else { $subsection_visibility = $subsection->getVisibility(); }
							if( $subsection_visibility ) echo "\t\t\t\t\t".'<li><a href="'.$subsection->getURI().'">'.$subsection->getName().'</a></li>'."\n";
						}
						echo "\t\t\t\t".'</ul>'."\n";
						echo "\t\t\t".'</div>'."\n";
					}
					else echo "\t\t".'<h3><a href="'.$section->getURI().'">'.$section->getName().'</a></h3>'."\n";
				}
			}
		}
	 }
	 
	 public function breadcrumbs() {
	 	/*
		if( $this->CI->controller != 'home' ) {
			
			$sections = $user->getGroup()->getSections();
			foreach( $sections as $section ) {
				if( $section->getChildren()->count() ) {
					foreach( $section->getChildren() as $subsection ) {
						if( $this->CI->controller == basename($subsection->getController()) ) {
							echo '<ul id="breadcrumbs">'."\n";
							echo '<li><a href="#">Administratori / Grupe</a></li>';
							echo '<li>Nova grupa</li>';
							echo '</ul>'."\n";
						}
					}
				}
			}
		}
		*/
		echo '<ul id="breadcrumbs">'."\n";
		echo '</ul>'."\n";
	 }
	 
	 public function backToGrid( $id, $uri ) {
	 	
		$grid_navigator = $this->CI->session->userdata('grid_navigator');
		return "backToGrid('".site_url($uri)."', ".$grid_navigator[$id].")";
	 }
	 
	 private function activeSection( $section ) {
	 	
		foreach( $section->getChildren() as $subsection ) {
			if( $this->CI->controller == basename($subsection->getController())/* && $subsection->getVisibility()*/ ) return ' id="active"';
		}
	 }
 }
 
 /* End of file Navigation_Manager.php */
 /* Location: ./system/applications/_backend/libraries/Navigation_Manager.php */