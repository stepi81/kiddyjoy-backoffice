<?php

/**
 * ...
 * @author Damir Mozar [ ABC Design ]
 */

 namespace models\Entities\Order;
 
 use models\Entities\Order\RegularOrder;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity(repositoryClass="models\CouponRepository")
  * @Table(name="ecom_order_coupons")
  */
 class Coupon {
     
    /**
     * @Id
     * @Column(type="integer", length=10, nullable=false)
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /** @Column(type="string", length=240, nullable=false) */        
    private $title;
    /** @Column(type="smallint", length=4, nullable=false) */
    private $discount;
    /** @Column(type="string", length=20, nullable=false) */
    private $code;
    /** @Column(type="text", nullable=false) */
    private $definition;
    /** @Column(type="smallint", length=2, nullable=false) */
    private $status;
	/** @Column(type="smallint", length=2, nullable=false) */
    private $type;
	/** @Column(type="date",  nullable=true) */
    private $publish_start;
	/** @Column(type="date",  nullable=false) */
    private $publish_end;
    
 	public function __construct() {
        // TODO
    }
    
    public function getID() { return $this->id; }
 	public function setTitle( $value ) { $this->title = $value; }
    public function getTitle() { return $this->title; }
    public function setDiscount( $value ) { $this->discount = $value; }
    public function getDiscount() { return $this->discount; }
 	public function setCode( $value ) { $this->code = $value; }
    public function getCode() { return $this->code; }
    public function setDefinition( $value ) { $this->definition = json_encode($value); }
    public function getDefinition() { return json_decode($this->definition); }
 	public function setStatus( $value ) { $this->status = $value; }
    public function getStatus() { return $this->status; }
 	public function setOrder( RegularOrder $value ) { $this->orders[] = $value; }
	public function getOrder() { return $this->orders; }
	public function getType() { return $this->type; }
	public function setType($value) { $this->type = $value; }
	public function getPublishstart() { return $this->publish_start; }
	public function setPublishstart($value) { $this->publish_start = $value; }
	public function getPublishend() { return $this->publish_end; }
	public function setPublishend($value) { $this->publish_end = $value; }
	
	
 }
 
 /* End of file Coupon.php */
 /* Location: ./system/applications/_backend/models/Entities/Order/Coupon.php */