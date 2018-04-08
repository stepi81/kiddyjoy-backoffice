<?php

/**
 * ...
 * @author Ivan Despic [ Codeion ]
 */

 namespace models\Entities\Advertising;

 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity
  * @Table(name="ecom_ad_campaigns")
  */
 class AdCampaing {
    
    /**
     * @Id
     * @Column(type="integer", length=10, nullable=false)
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ManyToOne(targetEntity="models\Entities\Advertising\Ad")
     * @JoinColumn(name="ad_id", referencedColumnName="id")
     */
    private $ad;
    
    /**
     * @ManyToOne(targetEntity="models\Entities\Product\Category")
     * @JoinColumn(name="category_id", referencedColumnName="id")
     **/
    private $category;
    
    /**
     * @ManyToOne(targetEntity="models\Entities\Product\Subcategory")
     * @JoinColumn(name="subcategory_id", referencedColumnName="id")
     **/
    private $subcategory;
    
    /**
     * @ManyToOne(targetEntity="models\Entities\Product\Brand")
     * @JoinColumn(name="brand_id", referencedColumnName="id")
     */
    private $brand;
    
    
    public function getID() { return $this->id; }
    public function setAd( $value ) { $this->ad = $value; }
    public function getAd() { return $this->ad ; }
    public function setSubcategory( $value ) { $this->subcategory = $value; }
    public function getSubcategory() { return $this->subcategory; }
    public function setCategory( $value ) { $this->category = $value; }
    public function getCategory() { return $this->category; }
    public function setBrand( $value ) { $this->brand = $value; }
    public function getBrand() { return $this->brand; }

 }
 
 /* End of file AdCampaing.php */
 /* Location: ./system/applications/_backend/models/Entities/Advertising/AdCampaing.php */