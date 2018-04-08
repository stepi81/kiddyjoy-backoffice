<?php

/**
 * ...
 * @author Aleksandar Milas [ Codeion ]
 */

 namespace models\Entities;
 
 use models\Entities\Location;
 use models\Entities\Order\ItemFinal;
 use models\Entities\Order\Points\WebTransaction;
 use models\Entities\PostalCode;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity
  * @Table(name="ecom_orders_final")
  */
 class OrderFinal {
 	
	/**
	 * @Id
	 * @Column(type="integer", length=10, nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
    /** @Column(type="smallint", length=2, nullable=false) */
    private $app_id;
	/** @Column(type="string", length=20, nullable=true) */
	private $reference_id;
	/** @Column(type="smallint", length=2, nullable=false) */
	private $payment_type;
	/** @Column(type="string", length=20, nullable=true) */
	private $payment_id;
	/** @Column(type="string", length=20, nullable=true) */
	private $transaction_id;
	/** @Column(type="string", length=20, nullable=true) */
	private $auth_code;
	/** @Column(type="string", length=10, nullable=true) */
	private $card_type;
	/** @Column(type="string", length=20, nullable=false) */
	private $delivery_id;
	/** @Column(type="integer", length=2, nullable=false) */
	private $discount;
	/** @Column(type="decimal", precision=2, scale=1, nullable=false) */
    private $discount_value;
	/** @Column(type="decimal", precision=2, scale=1, nullable=false) */
	private $total_price;
	/** @Column(type="string", length=13, nullable=true) */
	private $invoice;
	/** @Column(type="text", nullable=true) */
	private $info;
	/** @Column(type="datetime") */
	private $date;
	/** @Column(type="smallint", length=2, nullable=false) */
	private $status;
	
	/**
	 * @OneToOne(targetEntity="models\Entities\User\Customer")
	 * @JoinColumn(name="user_id", referencedColumnName="id")
	 */
	private $user;
	
	/**
	 * @OneToOne(targetEntity="models\Entities\Location")
	 * @JoinColumn(name="location_id", referencedColumnName="id")
	 */
	private $location;
	
	/**
     * @OneToMany(targetEntity="models\Entities\Order\ItemFinal", mappedBy="order")
	 * @OrderBy({"price" = "DESC"})
     */
	private $items;
	
	/**
	 * @OneToOne(targetEntity="models\Entities\PostalCode")
	 * @JoinColumn(name="postal_code", referencedColumnName="postal_code")
	 */
	private $postal_code;
	
	/**
	 * @OneToOne(targetEntity="models\Entities\Order\Points\WebTransaction")
	 * @JoinColumn(name="id", referencedColumnName="order_id")
	 */
	private $points_transaction;
	
	public function __construct() {
		
		$this->items = new \Doctrine\Common\Collections\ArrayCollection();
    }
	
	public function setID( $value ) { $this->id = $value; }
    public function getID() { return $this->id; }
    public function setAppID( $value ) { $this->app_id = $value; }
    public function getAppID() { return $this->app_id; }
	public function setReferenceID( $value ) { $this->reference_id = $value; }
    public function getReferenceID() { return $this->reference_id; }
	public function setPaymentType( $value ) { $this->payment_type = $value; }
	public function getPaymentType() { return $this->payment_type; }
	
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
	public function setDiscount( $value ) { $this->discount = $value; }
    public function getDiscount() { return $this->discount; }
	public function setDiscountValue( $value ) { $this->discount_value = $value; }
    public function getDiscountValue() { return $this->discount_value; }
	public function setTotalPrice( $value ) { $this->total_price = $value; }
    public function getTotalPrice() { return $this->total_price; }
	
	public function setInvoice( $value ) { $this->invoice = $value; }
    public function getInvoice() { return $this->invoice; }
	public function setInfo( $value ) { $this->info = $value; }
    public function getInfo() { return $this->info; }
	
    public function setDate() { $this->date = new \DateTime("now"); }
    public function getDate() { return $this->date; }
    public function getFormatedDate() { return $this->date->format('H:i:s d/m/Y'); }
	public function setStatus( $value ) { $this->status = $value; }
    public function getStatus() { return $this->status; }
 	public function setUser( User $value ) { $this->user = $value; }
	public function getUser() { return $this->user; }
    public function setLocation( Location $value ) { $this->location = $value; }
    public function getLocation() { return $this->location; }
	public function setItem( Item $value ) { $this->items[] = $value; }
	public function getItem() { return $this->items->first(); }
    public function getItems() { return $this->items; }
	public function setPostalCode( PostalCode $value ) {$this->postal_code = $value; }
	public function getPostalCode() { return $this->postal_code->getPostalCode(); }
	public function getPostalCodeObject() { return $this->postal_code; }
    
	public function setPointsTransaction( WebTransaction $value ) { $this->points_transaction = $value; }
	public function getUsedPoints() { return $this->points_transaction ? $this->points_transaction->getPoints() : 0; }
	
 	public function getPoints() { return (int)($this->total_price/1000); }
	
	public function getPointsActivationDate() {
		$this->date->add(new \DateInterval('P15D'));
		return $this->date->format('d.m.Y.');
	}
	
    public function getStatusInfo() {
    	$data = unserialize(ORDER_PROCESS);
    	return $data[$this->status];
    }
 }
 
 /* End of file OrderFinal.php */
 /* Location: ./system/applications/_frontend/models/Entities/OrderFinal.php */