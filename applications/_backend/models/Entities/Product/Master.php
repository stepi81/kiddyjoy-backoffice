<?php

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */

 namespace models\Entities\Product;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity
  * @Table(name="ecom_products")
  */
 class Master {
    
    /**
     * @Id
     * @Column(type="integer", length=10, nullable=false)
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;
    
	/** @Column(type="string", length=20, nullable=false) */  
    private $master_id;
    /** @Column(type="string", length=20, nullable=false) */  
    private $category_id;
    /** @Column(type="string", length=120, nullable=false) */
    private $name; 
    /** @Column(type="smallint", length=2, nullable=false) */
    private $price_type;
    /** @Column(type="decimal", precision=2, scale=1, nullable=false) */
    private $price;
    /** @Column(type="string", length=120, nullable=false) */
    private $description;
    /** @Column(type="smallint", length=2, nullable=false) */
    private $archive;
	
	/**
     * @OneToMany(targetEntity="models\Entities\Stock", mappedBy="product")
     */
	private $stock;
    
    
    /**
     * @ManyToOne(targetEntity="models\Entities\Product\Brand")
     * @JoinColumn(name="brand_id", referencedColumnName="id")
     */
    private $brand;
    
    /**
     * @OneToOne(targetEntity="models\Entities\Product")
     * @JoinColumn(name="id", referencedColumnName="id")
     */
    private $product;
    
    public function __construct() {}
    
    public function getID() { return $this->id; }  
    public function getMasterID() { return $this->master_id; } 
    public function getCategoryID() { return $this->category_id; }
    public function getName() { return $this->name; }  
    public function getPriceType() { return $this->price_type; }
	public function getPrice() { return $this->price; }  
    public function getDescription() { return $this->description; } 
    public function getArchive() { return $this->archive; }
	public function getBrand() { return $this->brand; }
	public function getStock() { return $this->stock; }
    
    public function getBrandName() {
        try {
            return $this->getBrand()->getName();
        }
        catch( \Doctrine\ORM\EntityNotFoundException $e ) {
            
            return '';
        }
    }
    
    public function getBrandImage() {
        try {
            return $this->getBrand()->getImageURL();
        }
        catch( \Doctrine\ORM\EntityNotFoundException $e ) {
            
            return '';
        }
    }
}
 
 /* End of file Master.php */
 /* Location: ./system/applications/_frontend/models/Entities/Product/Master.php */