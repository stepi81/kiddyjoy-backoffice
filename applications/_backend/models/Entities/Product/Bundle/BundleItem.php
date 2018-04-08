<?php

/**
 * ...
 * @author Andrej The Savic [ ABC Design ]
 */

 namespace models\Entities\Product\Bundle;
 
 use models\Entities\Product;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity
  * @Table(name="ecom_bundle_items")
  */
 class BundleItem {
 	
     /**
     * @Id
     * @Column(type="integer", length=10, nullable=false)
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /** @Column(type="decimal", precision=2, scale=1, nullable=true) */
    private $price;
    
    /** @Column(type="integer", length=4, nullable=false) */
    private $discount;
    
     /**
     * @OneToOne(targetEntity="models\Entities\Product")
     * @JoinColumn(name="product_id", referencedColumnName="id")
     **/
    private $product;
    
    public function __construct() {
        // TODO
    }
    
    public function getID() { return $this->id; }
 	public function setPrice( $value ) { $this->price =  is_numeric($value) && $value > 0 ? $value : NULL; }
    public function setDiscount( $value ) { $this->discount = is_null($this->price) ? $value : 0; }
    public function getDiscount() { return $this->discount; }
    public function setProduct( Product $value ) { $this->product = $value; } 
    public function getProduct() { return $this->product; }
    
    public function getPrice( $format = FALSE ) {
    	
    	if( $this->price ) $price = $this->price;
    	else $price = round($this->product->getPrice() - ($this->product->getPrice() * ($this->discount / 100 )), -1);
    	return $format ? number_format($price, 2, ',', '.').' RSD' : $price;
    }
 }
 
 /* End of file BundleItem.php */
 /* Location: ./system/applications/_frontend/models/Entities/Product/Bundle/BundleItem.php */