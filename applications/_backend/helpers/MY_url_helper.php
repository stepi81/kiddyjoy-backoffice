<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */

 if ( ! function_exists('assets_url')) {
     
     function assets_url( $uri = '' ) {
         
         return APP_URL.'assets/'.$uri;
     }
 }
 
 if ( ! function_exists('asset_url')) {
     
     function asset_url( $uri = '' ) {
         
         return APP_URL.'assets/'.$uri;
     }
 }

 if ( ! function_exists('layout_url')) {
     
     function layout_url( $uri = '' ) {
         
         return APP_URL.'assets/img/layout/_backend/'.$uri;
     }
 }
 
 if ( ! function_exists('img_url')) {
     
     function img_url($uri = '') {
         
         return APP_URL.'assets/img/' . $uri;
     }

 }
 
 /* End of file MY_url_helper.php */
 /* Location: ./system/applications/_backend/helpers/MY_url_helper.php */