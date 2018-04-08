<?php

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */

 namespace models\Entities\Order;
 
 use models\Entities\Product;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /** @MappedSuperclass */
 class ItemAbstract {
 	
 	/**
	 * @Id
	 * @Column(type="integer", length=10, nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
 	
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
	
	//public function setProductID( $value ) { $this->product_id = $value; }
 	public function getID() { return $this->id; }
    public function getProductID() { return $this->getProduct()->getID(); }
	public function setPrice( $value ) { $this->price = $value; }
    public function getPrice() { return $this->price; }
	public function setQuantity( $value ) { $this->quantity = $value; }
    public function getQuantity() { return $this->quantity; }
	public function setBundle( $value ) { $this->bundle = json_encode($value); }
    public function getBundle() { return isset($this->bundle) ? json_decode($this->bundle) : array(); }
    public function setProduct( Product $value ) { $this->product = $value; }
    public function getProduct() { return $this->product; }
 }
 
 /* End of file ItemAbstract.php */
 /* Location: ./system/applications/_frontend/models/Entities/Order/ItemAbstract.php */