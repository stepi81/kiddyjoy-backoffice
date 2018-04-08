<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ...
 * @author Ivan Despic [ Codeion SA ]
 */
 
 if ( ! function_exists('price_list')) {
     
     function price_list( $price, $product ) {

         $prices = array_filter( explode(',', $product->price_list) );
         
         if( $price != '' && $price != $product->price ){
             if( count( $prices ) < 10 ){
                array_unshift( $prices, $product->price );    
             } else {
                array_pop($prices);
                array_unshift( $prices, $product->price );    
             }
         }
         
         $prices = implode(',', $prices);
         
         return $prices;
     }
 }
 
 /* End of file product_helper.php */
 /* Location: ./system/application/helpers/product_helper.php */