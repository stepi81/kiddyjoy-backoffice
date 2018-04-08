<?php

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */

 namespace models\Entities\Order;
 
 use models\Entities\Order\ItemAbstract;
 use models\Entities\OrderFinal;
 use models\Entities\ReviewWeb;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity
  * @Table(name="ecom_order_items_final")
  */
 class ItemFinal extends ItemAbstract {
	
	/**
     * @OneToOne(targetEntity="models\Entities\ReviewWeb", mappedBy="item")
     **/
	private $review;
	
	/**
     * @ManyToOne(targetEntity="models\Entities\OrderFinal", inversedBy="items")
     * @JoinColumn(name="order_id", referencedColumnName="id")
     */
	private $order;
	
 	public function setReview( ReviewWeb $value ) { $this->review = $value; }
    public function getReview() { return $this->review; }
	public function setOrder( OrderFinal $value ) { $this->order = $value; }
    public function getOrder() { return $this->order; }
 }
 
 /* End of file ItemFinal.php */
 /* Location: ./system/applications/_frontend/models/Entities/Order/ItemFinal.php */