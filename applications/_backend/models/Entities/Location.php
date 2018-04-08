<?php

/**
 * ...
 * @author Marko Stepanovic [ Codeion ]
 */
 
 namespace models\Entities;
 
 use models\Entities\Images\LocationImage;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity(repositoryClass="models\LocationRepository")
  * @Table(name="ecom_locations")
  */
 class Location {
     
    /**
     * @Id
     * @Column(type="string", length=20, nullable=false)
     */
    private $id;

    /** @Column(type="string", length=30, nullable=false) */
    private $name;
    /** @Column(type="string", length=250, nullable=false) */
    private $address;
    /** @Column(type="string", length=120, nullable=false) */
    private $phones;
    /** @Column(type="string", length=250, nullable=false) */
    private $info;

    
    /**
     * @OneToMany(targetEntity="models\Entities\Images\LocationImage", mappedBy="location", cascade={"remove"})
     */
    private $images;
    
    public function __construct() {

        

    }
    
    public function getID() { return $this->id; }
    
    public function setID( $value ) { $this->id = $value; }
	
	public function setName( $value ) { $this->name = $value; }
    public function getName() { return $this->name; }  
    
    public function setAddress( $value ) { $this->address = $value; }
    public function getAddress() { return $this->address; }
    public function setPhones( $value ) { $this->phones = $value; }
    public function getPhones() { return $this->phones; }
    public function setInfo( $value ) { $this->info = $value; }
    public function getInfo() { return $this->info; }
    public function setImage( LocationImage $value ) { $this->images[] = $value; }
    public function getImages() { return $this->images; }

 }
 
 /* End of file Location.php */
 /* Location: ./system/applications/_backend/models/entities/Location.php */