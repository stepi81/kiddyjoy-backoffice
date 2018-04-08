<?php

/**
 * ...
 * @author Marko Stepanovic [ Codeion ]
 */

 namespace models\Entities\Cart;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity(repositoryClass="models\CartShippingRepository")
  * @Table(name="ecom_cart_shipping_options")
  */
 class ShippingOption {
 	
    /**
     * @Id
     * @Column(type="integer", length=10, nullable=false)
     * @GeneratedValue(strategy="AUTO")
     */
	private $id;
	
	/** @Column(type="string", length=240, nullable=false) */
	private $title;
	/** @Column(type="text", nullable=false) */
	private $description;
	/** @Column(type="string", length=60, nullable=true) */
	private $icon;
	/** @Column(type="smallint", length=2, nullable=false) */
	private $locations;
	/** @Column(type="decimal", precision=2, scale=1, nullable=true) */
    private $price_limit;
	/** @Column(type="decimal", precision=2, scale=1, nullable=true) */
    private $price;
	/** @Column(type="smallint", length=2, nullable=false) */
	private $status;
	
	public function __construct() {
		
		$this->location = 0;
		$this->status = 0;
    }
	
    public function getID() { return $this->id; }
	public function setTitle( $value ) { $this->title = $value; }
	public function getTitle() { return $this->title; }
	public function setDescription( $value ) { $this->description = $value; }
	public function getDescription() { return $this->description; }
	public function setIcon( $value ) { $this->icon = $value; }
    public function getIcon() { return $this->icon; }
    public function getIconURL() { return $this->icon ? assets_url('img/cart/shipping/'.$this->icon) : assets_url('img/cart/shipping/default.jpg'); }
	public function setLocations( $value ) { $this->locations = $value; }
	public function getLocations() { return $this->locations; }
	public function setStatus( $value ) { $this->status = $value; }
	public function getStatus() { return $this->status; }
	public function setLimit( $value ) { $this->price_limit = $value; }
	public function getLimit() { return $this->price_limit; }
	public function setPrice( $value ) { $this->price = $value; }
	public function getPrice() { return $this->price; }
 }
 
 /* End of file ShippingOption.php */
 /* Location: ./system/applications/_frontend/models/Entities/Cart/ShippingOption.php */