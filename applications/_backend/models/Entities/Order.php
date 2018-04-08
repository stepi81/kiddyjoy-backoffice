<?php

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */

 namespace models\Entities;
 
 use models\Entities\Location;
 use models\Entities\Order\Item;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity(repositoryClass="models\OrderRepository")
  * @Table(name="ecom_orders")
  * @InheritanceType("SINGLE_TABLE")
  * @DiscriminatorColumn(name="type_id", type="integer")
  * @DiscriminatorMap({"1" = "models\Entities\Order\Regular", "2" = "models\Entities\Order\Fast"})
  */
 class Order {
     
    /**
     * @Id
     * @Column(type="integer", length=10, nullable=false)
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;
    /** @Column(type="smallint", length=2, nullable=false) */
    private $app_id;
    /** @Column(type="smallint", length=2, nullable=false) */
    private $payment_type;
    /** @Column(type="string", length=20, nullable=true) */
    private $reference_id;
    /** @Column(type="string", length=20, nullable=true) */
    private $payment_id;
    /** @Column(type="string", length=20, nullable=true) */
    private $transaction_id;
    /** @Column(type="string", length=20, nullable=true) */
    private $auth_code;
    /** @Column(type="string", length=10, nullable=true) */
    private $card_type;
    /** @Column(type="integer", length=4, nullable=false) */
    private $delivery_id;
    /** @Column(type="integer", length=2, nullable=false) */
    private $discount;
	/** @Column(type="decimal", precision=2, scale=1, nullable=false) */
    private $discount_value;
    /** @Column(type="decimal", precision=2, scale=1, nullable=false) */
    private $total_price;
    /** @Column(type="string", length=13, nullable=true) */
    private $invoice;
	/** @Column(type="string", length=45, nullable=true) */
    private $invoice_pdf;
    /** @Column(type="string") */
    private $info;
    /** @Column(type="datetime") */
    private $date;
    /** @Column(type="string", length=13, nullable=true) */
    private $postal_code;
    /** @Column(type="smallint", length=2, nullable=false) */
    private $status;
    
    /**
     * @OneToOne(targetEntity="models\Entities\Location")
     * @JoinColumn(name="location_id", referencedColumnName="id")
     */
    private $location;
    
    /**
     * @OneToMany(targetEntity="models\Entities\Order\Item", mappedBy="order")
     */
    private $items;
    
    public function __construct() {
        
        $this->items = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function setID( $value ) { $this->id = $value; }
    public function getID() { return $this->id; }
    public function setAppID( $value ) { $this->app_id = $value; }
    public function getAppID() { return $this->app_id; }
    public function setPaymentType( $value ) { $this->payment_type= $value; }
    public function getPaymentType() { return $this->payment_type; }
    public function setReferenceID( $value ) { $this->reference_id = $value; }
    public function getReferenceID() { return $this->reference_id; } 
    public function setPaymentID( $value ) { $this->payment_id = $value; }
    public function getPaymentID() { return $this->payment_id; } 
    public function setTransactionID( $value ) { $this->transaction_id = $value; }
    public function getTransactionID() { return $this->transaction_id; }
    public function setAuthCode( $value ) { $this->auth_code = $value; }
    public function getAuthCode() { return $this->auth_code; }
    public function setCardType( $value ) { $this->card_type = $value; }
    public function getCardType() { return $this->card_type; }
    public function setDelivery( $value ) { $this->delivery_id = $value; }
    public function getDelivery() { return $this->delivery_id; }
    public function setLocation( Location $value ) { $this->location = $value; }
    public function getLocation() { return $this->location; }
    public function setDiscount( $value ) { $this->discount = $value; }
    public function getDiscount() { return $this->discount; }
	public function setDiscountValue( $value ) { $this->discount_value = $value; }
    public function getDiscountValue() { return $this->discount_value; }
    public function setTotalPrice( $value ) { $this->total_price = $value; }
    public function getTotalPrice() { return $this->total_price; }
    public function setInvoice( $value ) { $this->invoice = $value; }
    public function getInvoice() { return $this->invoice; }
	public function setInvoicePDF( $value ) { $this->invoice_pdf = $value; }
    public function getInvoicePDF() { return $this->invoice_pdf; }
    public function setInfo( $value ) { $this->info = $value; }
    public function getInfo() { return $this->info; }
    public function setDate() { $this->date = new \DateTime("now"); }
    public function getDate() { return $this->date; }
    public function getFormatedDate() { return $this->date->format('d/m/Y'); }
    public function setPostalCode( $value ) { $this->postal_code = $value; }
    public function getPostalCode() { return $this->postal_code; }
    public function setStatus( $value ) { $this->status = $value; }
    public function getStatus() { return $this->status; }
    
    public function setItem( Item $value ) { $this->items[] = $value; }
    public function getItems() { return $this->items; }
 }
 
 /* End of file Order.php */
 /* Location: ./system/applications/_frontend/models/Entities/Order.php */