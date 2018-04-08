<?php

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */

 namespace models\Entities;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity(repositoryClass="models\PostalCodeRepository") 
  * @Table(name="ecom_postal_codes")
  */
 class PostalCode {
    
    /**
     * @Id
     * @Column(type="string", length=13, nullable=false)
     */
    private $postal_code;

    /** @Column(type="string", length=60, nullable=false) */
    private $city;
    /** @Column(type="decimal", precision=6, scale=1, nullable=false) */
    private $latitude;
    /** @Column(type="decimal", precision=6, scale=1, nullable=false) */
    private $longitude;
    
    public function getPostalCode() { return $this->postal_code; }
    public function setCity( $value ) { $this->city = $value; }
    public function getCity() { return $this->city; }
    public function getTitle() { return $this->city.' ('.$this->postal_code.')'; }
    public function setLatitude( $value ) { $this->latitude = $value; }
    public function getLatitude() { return $this->latitude; }
    public function setLongitude( $value ) { $this->longitude = $value; }
    public function getLongitude() { return $this->longitude; }
 }
 
 /* End of file PostalCode.php */
 /* Location: ./system/applications/_frontend/models/Entities/PostalCode.php */