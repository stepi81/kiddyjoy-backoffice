<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */

 if ( ! function_exists('build_tinymce_js')) {
     
     function build_tinymce_js( $id_list, $width, $height, $image_list_url ) {
         
         $tinymce_js = '<script type="text/javascript">tinyMCE.init({';
		 
		 // General options
		 $tinymce_js .= 'mode : "exact",';
		 $tinymce_js .= 'elements: "'.$id_list.'",';
		 $tinymce_js .= 'theme : "advanced",';
		 $tinymce_js .= 'forced_root_block : "",';
		 $tinymce_js .= 'skin : "o2k7",';
		 $tinymce_js .= 'skin_variant : "silver",';
		 $tinymce_js .= 'plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",';
		 $tinymce_js .= 'width : '.$width.',';
		 $tinymce_js .= 'height : '.$height.',';
		 $tinymce_js .= 'document_base_url : "'.APP_URL.'",';
		 $tinymce_js .= 'relative_urls : false,';
		 $tinymce_js .= 'remove_script_host : false,';
		 
		 // Theme options
		 $tinymce_js .= 'theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",';
		 $tinymce_js .= 'theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",';
		 $tinymce_js .= 'theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",';
		 $tinymce_js .= 'theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",';
		 $tinymce_js .= 'theme_advanced_toolbar_location : "top",';
		 $tinymce_js .= 'theme_advanced_toolbar_align : "left",';
		 $tinymce_js .= 'theme_advanced_statusbar_location : "bottom",';
		 $tinymce_js .= 'theme_advanced_resizing : true,';
		 
		 $tinymce_js .= 'external_image_list_url : "'.$image_list_url.'"';
		 
		 $tinymce_js .= '});</script>';
		 
		 return $tinymce_js;
     }
 }
 
 /* End of file tinymce_helper.php */
 /* Location: ./system/applications/_backend/helpers/tinymce_helper.php */