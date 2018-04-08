<?php

/**
 * ...
 * @author Marko Stepanovic [ Codeion ]
 */

 namespace models\Entities\Product;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity(repositoryClass="models\SubcategoryPriceRangeRepository")
  * @Table(name="ecom_subcategory_price_ranges")
  */
 class SubcategoryPriceRange {
    
    /**
     * @Id
     * @Column(type="integer", nullable=false)
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;

    /** @Column(type="integer", length=10, nullable=false) */
    private $min_price;
	
	/** @Column(type="integer", length=10, nullable=false) */
    private $max_price;
    
    /**
     * @ManyToOne(targetEntity="models\Entities\Product\Subcategory")
     * @JoinColumn(name="subcategory_id", referencedColumnName="id")
     **/
    private $subcategory;
    
    public function __construct() {

    }
	
    public function getID() { return $this->id; }
    public function setMinPrice( $value ) { $this->min_price = $value; }
    public function getMinPrice() { return $this->min_price; }
	public function setMaxPrice( $value ) { $this->max_price = $value; }
	public function getMaxPrice() { return $this->max_price; }
  	public function setSubcategory( $value ) { $this->subcategory = $value; }
    public function getSubcategory() { return $this->subcategory; }

 }
 
 /* End of file SubcategoryPriceRange.php */
 /* Location: ./system/applications/_frontend/models/Entities/Product/SubcategoryPriceRange.php */