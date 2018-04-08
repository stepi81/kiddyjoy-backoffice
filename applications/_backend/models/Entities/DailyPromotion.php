<?php

/**
 * ...
 * @author Marko Stepanovic [ Codeion ]
 */
 
 namespace models\Entities;
 
 use models\Entities\Product;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity(repositoryClass="models\DailyPromotionRepository")
  * @Table(name="ecom_daily_promotions")
  */
 class DailyPromotion {
     
    /**
     * @Id
     * @Column(type="integer", nullable=false)
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;

    /** @Column(type="date") */
    private $start_date;
    /** @Column(type="date") */
    private $end_date;
    /** @Column(type="integer", length=2, nullable=false) */
    private $status;
    
    /**
     * @OneToOne(targetEntity="models\Entities\Product")
     * @JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;
    
    public function getID() { return $this->id; }
    public function setStartDate( $value ) { $this->start_date = $value; }
    public function getStartDate() { return $this->start_date; }
    public function getFormatedStartDate() { return $this->start_date->format('d.m.Y'); }
    public function setEndDate( $value ) { $this->end_date = $value; }
    public function getEndDate() { return $this->end_date; }
    public function setProduct( Product $value ) { $this->product = $value; }
    public function getFormatedEndDate() { return $this->end_date->format('d.m.Y'); }
    public function getProduct() { return $this->product; }
    public function setStatus( $value ) { $this->status = $value; }
    public function getStatus() { return $this->status; }
 }
 
 /* End of file DailyPromotion.php */
 /* Location: ./system/applications/_backend/models/entities/DailyPromotion.php */