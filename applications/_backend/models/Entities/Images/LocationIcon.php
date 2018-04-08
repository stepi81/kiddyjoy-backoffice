<?php

/**
 * ...
 * @author Marko Stepanovic [ Codeion ]
 */

 namespace models\Entities\Images;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity
  * @Table(name="ecom_location_icons")
  */
 class LocationIcon {
     
    /**
     * @Id
     * @Column(type="integer", nullable=false)
     */
    private $location_id;
    
    /** @Column(type="string", length=60, nullable=false) */
    private $name;
    
    public function setID( $value ) { $this->location_id = $value; }
    public function getID() { return $this->location_id; }
    public function setName( $value ) { $this->name = $value; }
    public function getName() { return $this->name; }    
    public function getURL() { return assets_url('img/locations/icons/'.$this->name); }
 }
 
 /* End of file LocationIcon.php */
 /* Location: ./system/applications/_frontend/models/Entities/Images/LocationIcon.php */