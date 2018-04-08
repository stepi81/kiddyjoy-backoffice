<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */

 if ( ! function_exists('build_plupload_js')) {
     
     function build_plupload_js( $url ) {
         
         $plupload_js = '<script type="text/javascript">$(document).ready(function(){';
		 $plupload_js .= '$("#uploader").pluploadQueue({';
		 
		 // General settings
		 $plupload_js .= 'runtimes : "html5,flash",';
		 $plupload_js .= 'url : "'.$url.'",';
		 $plupload_js .= 'max_file_size : "10mb",';
		 $plupload_js .= 'chunk_size : "1mb",';
		 $plupload_js .= 'unique_names : false,';
		 $plupload_js .= 'multiple_queues : true,';
		 
		 // Specify what files to browse for
		 $plupload_js .= 'filters : [
		 {title : "Image files", extensions : "jpg,gif,png"},
		 {title : "Zip files", extensions : "zip,rar"},
		 {title: "Excel files", extensions: "xls,xslx,csv"},
		 {title: "PDF files", extensions: "pdf"},
		 {title: "Audio files", extensions: "mp3"},
		 {title: "Video files", extensions: "avi,flv,mp4"},
         {title: "Documents", extensions: "doc,docx,ppt,pptx"}
		 ],';
		 
		 // Flash settings
		 $plupload_js .= 'flash_swf_url : "'.assets_url('js/plupload/plupload.flash.swf').'"';
		 
		 $plupload_js .= '});});</script>';
		 
		 return $plupload_js;
     }
 }
 
 /* End of file upload_helper.php */
 /* Location: ./system/applications/_backend/helpers/upload_helper.php */