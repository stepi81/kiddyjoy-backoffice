<?php

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */

 namespace models\Entities;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity
  * @Table(name="ecom_stock")
  */
 class Stock {
     
    /** @Id @Column(type="string", length=16, nullable=false) */
    private $product_id;
    /** @Id @Column(type="string", length=30, nullable=false) */
    private $location_id;
    
    /** @Column(type="decimal", precision=2, scale=1, nullable=false) */
    private $quantity;
    
    /**
     * @ManyToOne(targetEntity="models\Entities\Product", inversedBy="stock")
     * @JoinColumn(name="product_id", referencedColumnName="master_id")
     */
    private $product;
    
    /**
     * @OneToOne(targetEntity="models\Entities\Location")
     * @JoinColumn(name="location_id", referencedColumnName="id")
     */
    private $location;
    
    public function getProductID() { return $this->product_id; }
    public function getQuantity() { return $this->quantity; }
    public function getProduct() { return $this->product; }
    public function getLocation() { return $this->location; }
 }
 
 /* End of file Stock.php */
 /* Location: ./system/applications/_frontend/models/Entities/Stock.php */