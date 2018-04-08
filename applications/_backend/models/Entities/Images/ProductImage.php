<?php

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */

 namespace models\Entities\Images;
 
 use models\Entities\Product;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity
  * @Table(name="ecom_product_images")
  */
 class ProductImage {
     
    /**
     * @Id
     * @Column(type="integer", length=10, nullable=false)
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /** @Column(type="string", length=60, nullable=false) */
    private $name;
    /** @Column(type="integer", length=4, nullable=false) */
    private $position;
    
    /**
     * @ManyToOne(targetEntity="models\Entities\Product", inversedBy="images")
     * @JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;
    
    public function getID() { return $this->id; }
    public function setName( $value ) { $this->name = $value; }
    public function getName() { return $this->name; }
    public function setPosition( $value ) { $this->position = $value; }
    public function getPosition() { return $this->position; }
    public function setProduct( Product $value ) { $this->product = $value; }
    public function getProduct() { return $this->product; }
    
    public function getSource( $path ) {
        return $this->name ? APP_URL.$path.$this->name : APP_URL.$path.'default_kiddyjoy.png';
        //return $this->name ? 'www.kiddyjoy.com/'.$path.$this->name : APP_URL.$path.'default_kiddyjoy.png';
    }
 }
 
 /* End of file ProductImage.php */
 /* Location: ./system/applications/_frontend/models/Entities/Images/ProductImage.php */