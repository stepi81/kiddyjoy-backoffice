<?php

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */

 namespace models\Entities\Images;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity
  * @Table(name="ecom_brand_images")
  */
 class BrandImage {
     
    /**
     * @Id
     * @Column(type="string", length=16, nullable=false)
     */
    private $brand_id;
    
    /** @Column(type="string", length=60, nullable=true) */
    private $name;
    
    public function setID( $value ) { $this->brand_id = $value; }
    public function getID() { return $this->brand_id; }
    public function setName( $value ) { $this->name = $value; }
    public function getName() { return $this->name; }    
    public function getURL() { return assets_url('img/brands/'.$this->name); }
 }
 
 /* End of file BrandImage.php */
 /* Location: ./system/applications/_frontend/models/Entities/Images/BrandImage.php */
