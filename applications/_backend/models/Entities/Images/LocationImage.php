<?php

/**
 * ...
 * @author Marko Stepanovic [ Codeion ]
 */

 namespace models\Entities\Images;
 
 use models\Entities\Location;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity
  * @Table(name="ecom_location_images")
  */
 class LocationImage {
     
    /**
     * @Id
     * @Column(type="string", length=20, nullable=false)
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /** @Column(type="string", length=60, nullable=false) */
    private $name;
    /** @Column(type="integer", length=4, nullable=false) */
    private $position;
    
    /**
     * @ManyToOne(targetEntity="models\Entities\Location", inversedBy="images")
     * @JoinColumn(name="location_id", referencedColumnName="id")
     */
    private $location;
    
    public function getID() { return $this->id; }
    public function setName( $value ) { $this->name = $value; }
    public function getName() { return $this->name; }
    public function setPosition( $value ) { $this->position = $value; }
    public function getPosition() { return $this->position; }
    public function setLocation( Location $value ) { $this->location = $value; }
    public function getLocation() { return $this->location; }
    public function getURL() { return assets_url('img/locations/'.$this->name); }
 }
 
 /* End of file LocationImage.php */
 /* Location: ./system/applications/_frontend/models/Entities/Images/LocationImage.php */