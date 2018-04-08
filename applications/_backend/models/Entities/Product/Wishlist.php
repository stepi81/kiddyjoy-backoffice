<?php

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */

 namespace models\Entities\Product;
 
 use models\Entities\Product;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity
  * @Table(name="ecom_wishlist")
  */
 class Wishlist {
 	
	/**
	 * @Id
	 * @Column(type="integer", length=10, nullable=false)
	 */
	private $user_id;
	
	
	private $products;
	
    public function getUserID() { return $this->user_id; }
	public function setProduct( Product $value ) { $this->products[] = $value; }
	public function getProducts() { return $this->products; }
 }
 
 /* End of file Wishlist.php */
 /* Location: ./system/applications/_frontend/models/Entities/Product/Wishlist.php */