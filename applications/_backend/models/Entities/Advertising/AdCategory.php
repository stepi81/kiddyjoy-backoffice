<?php

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */

 namespace models\Entities\Advertising;
 
 use Doctrine\Common\Collections\ArrayCollection;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity(repositoryClass="models\AdvertisingRepository")
  * @Table(name="ecom_ad_categories")
  */
 class AdCategory {
 	
	/**
	 * @Id
	 * @Column(type="integer", length=11, nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/** @Column(type="string", length=60, nullable=false) */
	private $name;
	/** @Column(type="smallint", length=2, nullable=true) */
	private $shuffle;
	
	/**
	 * @OneToMany(targetEntity="Ad", mappedBy="ad_category")
	 */
	private $ads;
	
	public function __construct() {
		
        $this->ads = new ArrayCollection();
    }
	
    public function getID() { return $this->id; }
	
	public function getAds() {
		$ads = $this->ads->toArray();
		if( $this->shuffle ) shuffle($ads);
		return new ArrayCollection($ads); 
	}
 }
 
 /* End of file AdCategory.php */
 /* Location: ./system/applications/_backend/models/Entities/Advertising/AdCategory.php */