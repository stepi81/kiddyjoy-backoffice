<?php

/**
 * ...
 * @author Marko Stepanovic [ Codeion ]
 */
 
 namespace models\Entities;
 
 use models\Entities\Location;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity
  * @Table(name="ecom_location_data")
  */
 class LocationData {
     
    /**
     * @Id
     * @Column(type="string", length=20, nullable=false)
     */
    private $location_id;

    /** @Column(type="string", length=120, nullable=false) */
    private $alias;

    /** @Column(type="string", length=60, nullable=false) */
    private $email;
    /** @Column(type="decimal", precision=6, scale=1, nullable=false) */
    private $latitude;
    /** @Column(type="decimal", precision=6, scale=1, nullable=false) */
    private $longitude;
    /** @Column(type="string", length=60, nullable=false) */
    private $address;
    /** @Column(type="string", length=20, nullable=false) */
    private $phones;
    /** @Column(type="string", length=250, nullable=false) */
    private $info;
    /** @Column(type="string", length=60, nullable=false) */
    private $icon;
	/** @Column(type="smallint", length=2, nullable=false) */
    private $public;
    
    /**
     * @OneToMany(targetEntity="models\Entities\Images\LocationImage", mappedBy="location", cascade={"remove"})
     */
    private $images;
    
    public function __construct() {

        $this->icon = NULL;

    }
    
    public function setID( $value ) { $this->location_id = $value; }
    public function getID() { return $this->location_id; }
	public function setAlias( $value ) { $this->alias = $value; }
    public function getAlias() { return $this->alias; }
    public function setEmail( $value ) { $this->email = $value; }
    public function getEmail() { return $this->email; }
    public function setLatitude( $value ) { $this->latitude = $value; }
    public function getLatitude() { return $this->latitude; }
    public function setLongitude( $value ) { $this->longitude = $value; }
    public function getLongitude() { return $this->longitude; }
    public function setAddress( $value ) { $this->address = $value; }
    public function getAddress() { return $this->address; }
    public function setPhones( $value ) { $this->phones = $value; }
    public function getPhones() { return $this->phones; }
    public function setInfo( $value ) { $this->info = $value; }
    public function getInfo() { return $this->info; }
    public function setIcon( $value ) { $this->icon = $value; } 
    public function getIcon() { return $this->icon; }
	/*
	public function setIconSmall( $value ) { $this->icon_small = $value; } 
    public function getIconSmall() { return $this->icon_small; }
	public function setIconMobile( $value ) { $this->icon_mobile = $value; } 
    public function getIconMobile() { return $this->icon_mobile; }
	*/
	public function setPublic( $value ) { $this->public = $value; } 
    public function getPublic() { return $this->public; }

    public function getIconURL() {
    		
        if( $this->getIcon() ) {
            try {
                return assets_url('img/icons/locations/large/'.$this->getIcon() );// TODO large or small?
            }
            catch( \Doctrine\ORM\EntityNotFoundException $e ) {
                return assets_url('img/icons/locations/default_kiddyjoy.png'); 
            }
        } else {
            return assets_url('img/icons/locations/default_kiddyjoy.png'); 
        }

    }
 }
 
 /* End of file Location.php */
 /* Location: ./system/applications/_backend/models/entities/Location.php */