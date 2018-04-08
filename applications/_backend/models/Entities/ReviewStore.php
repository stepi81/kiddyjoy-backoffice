<?php

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */

 namespace models\Entities;
 
 use models\Entities\Review;
 use models\Entities\Order\ItemShopFinal;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
 * @Entity
 */
 class ReviewStore extends Review {
	
	/**
     * @OneToOne(targetEntity="models\Entities\Order\ItemShopFinal", inversedBy="review")
     * @JoinColumn(name="item_id", referencedColumnName="id")
     **/
	private $item;
	
	public function getType() { return 'Radnja'; }
 	public function setItem( ItemShopFinal $value ) { $this->item = $value; }
	public function getItem() { return $this->item; }
 }
 
 /* End of file ReviewStore.php */
 /* Location: ./system/applications/_frontend/models/Entities/Product/ReviewStore.php */