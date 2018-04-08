<?php

/**
 * ...
 * @author Marko Stepanovic [ Codeion ]
 */

 namespace models\Entities\Cart;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity(repositoryClass="models\CartPaymentRepository")
  * @Table(name="ecom_cart_payment_options")
  */
 class PaymentOption {
 	
    /**
     * @Id
     * @Column(type="integer", length=10, nullable=false)
     * @GeneratedValue(strategy="AUTO")
     */
	private $id;
	
	/** @Column(type="string", length=60, nullable=false) */
	private $plugin;
	/** @Column(type="string", length=240, nullable=false) */
	private $title;
	/** @Column(type="text", nullable=false) */
	private $description;
	/** @Column(type="string", length=60, nullable=true) */
	private $icon;
	/** @Column(type="smallint", length=2, nullable=false) */
	private $status;
	
	public function getID() { return $this->id; }
	public function setPlugin( $value ) { $this->plugin = $value; }
	public function getPlugin() { return $this->plugin; }
	public function setTitle( $value ) { $this->title = $value; }
	public function getTitle() { return $this->title; }
	public function setDescription( $value ) { $this->description = $value; }
	public function getDescription() { return $this->description; }
	public function setIcon( $value ) { $this->icon = $value; }
    public function getIcon() { return $this->icon; }
	public function getIconURL() { return $this->icon ? assets_url('img/cart/payment/'.$this->icon) : assets_url('img/cart/payment/default.jpg'); }
	public function setStatus( $value ) { $this->status = $value; }
	public function getStatus() { return $this->status; }
 }
 
 /* End of file PaymentOption.php */
 /* Location: ./system/applications/_frontend/models/Entities/Cart/PaymentOption.php */