<?php

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */

 namespace models\Entities;
 
 use models\Entities\Review;
 use models\Entities\Order\ItemFinal;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
 * @Entity
 */
 class ReviewWeb extends Review {
	
	/**
     * @OneToOne(targetEntity="models\Entities\Order\ItemFinal", inversedBy="review")
     * @JoinColumn(name="item_id", referencedColumnName="id")
     **/
	private $item;
	
 	public function getType() { return 'Web'; }
 	public function setItem( ItemFinal $value ) { $this->item = $value; }
	public function getItem() { return $this->item; }
 }
 
 /* End of file ReviewWeb.php */
 /* Location: ./system/applications/_frontend/models/Entities/Product/ReviewWeb.php */