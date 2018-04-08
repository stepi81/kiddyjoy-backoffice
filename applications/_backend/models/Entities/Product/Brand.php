<?php

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */

 namespace models\Entities\Product;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity(repositoryClass="models\BrandRepository")
  * @Table(name="ecom_brands")
  */
 class Brand {
 	
	/**
	 * @Id
	 * @Column(type="integer", length=10, nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/** @Column(type="string", length=60, nullable=false) */
	private $name;
    /** @Column(type="string", length=120, nullable=false) */
    private $image;
	/** @Column(type="smallint", length=2, nullable=false) */
	private $featured;
	/** @Column(type="integer", length=10, nullable=true) */
	private $position;
	/** @Column(type="smallint", length=2, nullable=false) */
	private $status;
	
	public function __construct() {
		
		$this->status = 0;
		$this->featured = 0;
		$this->image = NULL;
	}
    
    public function getID() { return $this->id; }
	public function setName( $value ) { $this->name = $value; }
    public function getName() { return $this->name; }
    public function setImage( $value ) { $this->image = $value; } 
    public function getImage() { return $this->image; }
	public function setPosition( $value ) { $this->position = $value; }
	public function getPosition() { return $this->position; }
	public function setStatus( $value ) { $this->status = $value; }
    public function getStatus() { return $this->status; }
	public function setFeatured( $value ) { $this->featured = $value; }
    public function getFeatured() { return $this->featured; }
	public function getURL() { return assets_url('img/brands/'.$this->image); }
	public function unsetImage() { $this->image = NULL; }
	
	//public function getImageURL() { return $this->image ? assets_url('img/brands/'.$this->image->getName()) : assets_url('img/brands/default_kiddyjoy.png'); }
	public function getImageURL() {
		
        if( $this->getImage() ) {
        	try {
        		return $this->getURL();
        	}
        	catch( \Doctrine\ORM\EntityNotFoundException $e ) {
				return assets_url('img/brands/default_kiddyjoy.png'); 
			}
		}
		else return assets_url('img/brands/default_kiddyjoy.png');
	}
 }
 
 /* End of file Brand.php */
 /* Location: ./system/applications/_backend/models/Entities/Product/Brand.php */