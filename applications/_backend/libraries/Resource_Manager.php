<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */
 
 class Resource_Manager {
     
     private $css_url;
     private $css;
     private $js_url;
     private $js;
	 private $fonts;
     private $common_css;
	 private $common_fonts;
     private $common_js;
     private $favicon;
     
     public function __construct() {
         
         $this->css_url 					= APP_URL.'assets/css/_backend/';
         $this->js_url 						= APP_URL.'assets/js/';
         $this->favicon 					= APP_URL.'assets/img/favicon.ico';
         
         // external css resources
         $this->css['main']         		= "style.css";
         $this->css['flexigrid']			= "flexigrid.pack.css";
	  	 $this->css['plupload']				= "jquery.plupload.queue.css";
	  	 $this->css['datepicker']       	= "jquery.datepicker.css";
         $this->css['theme']            	= "jquery-ui-1.8.20.custom.css";
         $this->css['gallery']          	= "gallery.css";
         $this->css['fancybox']         	= "jquery.fancybox-1.3.1.css";
         $this->css['uploadify']        	= "uploadify.css";
         $this->css['multiselect']      	= "ui.multiselect.css";
         $this->css['multiselect_dropdown'] = "multiselect_dropdown.css";
         $this->css['dropbox'] 				= "dropbox.css";
	  	 $this->css['numberspinner'] 		= "numberspinner.css";
         // google web fonts
		 //$this->fonts['']	= "";
         
         // external javascript resources
         $this->js['jquery']        		= "//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js";
	   	 $this->js['jquery_ui']				= "//ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js";
		 //$this->js['jquery_ui']			= $this->js_url."jquery-ui-1.8.18.custom.min.js";
		 $this->js['dropdown']				= $this->js_url."jquery.dd.js";
		 $this->js['checkbox']				= $this->js_url."ui.checkbox.js";
		 //$this->js['flexigrid']			= $this->js_url."flexigrid.pack.js";
		 $this->js['flexigrid']				= $this->js_url."flexigrid.js";
		 $this->js['tiny_mce']				= $this->js_url."tiny_mce/tiny_mce.js";
         $this->js['gallery']           	= $this->js_url."gallery.js";
         $this->js['fancybox']          	= $this->js_url."jquery.fancybox-1.3.1.pack.js";
         $this->js['uploadify']         	= $this->js_url."jquery.uploadify.v2.1.0.js";
         $this->js['swfobject']         	= $this->js_url."swfobject.js";
         $this->js['sortable']          	= $this->js_url."jquery.ui.sortable.js";
         $this->js['mouse']             	= $this->js_url."jquery.ui.mouse.js";
		 $this->js['plupload_full']			= $this->js_url."plupload/plupload.full.js";
		 $this->js['plupload_queue']		= $this->js_url."plupload/jquery.plupload.queue.js";
         $this->js['master']        		= $this->js_url."master_backend.js";
         $this->js['multiselect']       	= $this->js_url."ui.multiselect.js";
         $this->js['multiselect_dropdown']  = $this->js_url."multiselect_dropdown.js";
         $this->js['dropbox']  				= $this->js_url."dropbox.js";
	  	 $this->js['numberspinner']  		= $this->js_url."numberspinner.js";
         
         $this->common_css 				= array('main');
		 $this->common_fonts			= array();
         $this->common_js 				= array('jquery', 'jquery_ui', 'multiselect');
     }
     
     public function load( $resources = array() ) {
		 
         $_CSS = isset($resources['css']) ? array_merge($this->common_css, $resources['css']) : $this->common_css;
		 $_FONTS = isset($resources['fonts']) ? array_merge($this->common_fonts, $resources['fonts']) : $this->common_fonts;
         $_JS = isset($resources['js']) ? array_merge($this->common_js, $resources['js']) : $this->common_js;
         
		 $_JS[] = 'master';
		 
         foreach( $_CSS as $value )
            echo '<link type="text/css" href="'.$this->css_url.$this->css[$value].'" rel="stylesheet" media="screen" charset="utf-8"/>'."\n";
		 
		foreach( $_FONTS as $value )
            echo '<link type="text/css" href="'.$this->fonts[$value].'" rel="stylesheet"/>'."\n";
         
         echo '<link type="image/x-icon" rel="icon" href="'.$this->favicon.'"/>'."\n";
         echo '<link type="image/x-icon" rel="shortcut icon" href="'.$this->favicon.'"/>'."\n";
         
         foreach( $_JS as $value )
            echo '<script type="text/javascript" src="'.$this->js[$value].'"></script>'."\n";
     }
 }
 
 /* End of file Resource_Manager.php */
 /* Location: ./system/applications/_backend/libraries/Resource_Manager.php */