<?php

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */

 namespace models\Entities\Order;
 
 use models\Entities\Order\ItemAbstract;
 use models\Entities\OrderShopFinal;
 use models\Entities\ReviewStore;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity
  * @Table(name="ecom_order_shop_items_final")
  */
 class ItemShopFinal extends ItemAbstract {
	
	/**
     * @OneToOne(targetEntity="models\Entities\ReviewStore", mappedBy="item")
     **/
	private $review;
	
	/**
     * @ManyToOne(targetEntity="models\Entities\OrderShopFinal", inversedBy="items")
     * @JoinColumn(name="order_id", referencedColumnName="id")
     */
	private $order;
	
 	public function setReview( ReviewStore $value ) { $this->review = $value; }
    public function getReview() { return $this->review; }
	public function setOrder( OrderShopFinal $value ) { $this->order = $value; }
    public function getOrder() { return $this->order; }
	
 }
 
 /* End of file ItemShopFinal.php */
 /* Location: ./system/applications/_frontend/models/Entities/Order/ItemShopFinal.php */