<?php

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */

 namespace models\Entities\Order;
 
 use models\Entities\Product;
 use models\Entities\Order;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity
  * @Table(name="ecom_order_items")
  */
 class Item {
     
    /**
     * @Id
     * @Column(type="integer", length=10, nullable=false)
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /** @Column(type="integer", length=10, nullable=false) */
    private $product_id;
    /** @Column(type="string", length=20, nullable=false) */
    private $location_id;	
    /** @Column(type="decimal", precision=2, scale=1, nullable=false) */
    private $price;
    /** @Column(type="integer", length=4, nullable=false) */
    private $quantity;
    /** @Column(type="text", nullable=true) */
    private $bundle;
    
    /**
     * @OneToOne(targetEntity="models\Entities\Product")
     * @JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;
    
    /**
     * @ManyToOne(targetEntity="models\Entities\Order", inversedBy="items")
     * @JoinColumn(name="order_id", referencedColumnName="id")
     */
    private $order;
    
    public function getID() { return $this->id; }
    public function setProductID( $value ) { $this->product_id = $value; }
    public function getProductID() { return $this->product_id; } 
    public function setLocationID( $value ) { $this->location_id = $value; }
    public function getLocationID() { return $this->location_id; }
    public function setPrice( $value ) { $this->price = $value; }
    public function getPrice() { return $this->price; }
    public function setQuantity( $value ) { $this->quantity = $value; }
    public function getQuantity() { return $this->quantity; }
    public function setBundle( $value ) { $this->bundle = json_encode($value); }
    public function getBundle() { return json_decode($this->bundle); }
    public function setProduct( Product $value ) { $this->product = $value; }
    public function getProduct() { return $this->product; }
    public function setOrder( Order $value ) { $this->order = $value; }
    public function getOrder() { return $this->order; }
 }
 
 /* End of file Item.php */
 /* Location: ./system/applications/_frontend/models/Entities/Order/Item.php */