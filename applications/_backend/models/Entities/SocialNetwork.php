<?php

/**
 * ...
 * @author Marko Stepanovic [ Codeion ]
 */

 namespace models\Entities;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity(repositoryClass="models\SocialNetworkRepository") 
  * @Table(name="ecom_social_networks")
  */
 class SocialNetwork {
 	
	/**
	 * @Id
	 * @Column(type="integer", length=10, nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/** @Column(type="string", length=60, nullable=false) */
	private $title;
	/** @Column(type="string", length=250, nullable=false) */
	private $social_url;
	/** @Column(type="string", length=60, nullable=false) */
	private $image;
	/** @Column(type="smallint", length=2, nullable=false) */
	private $status;
	
	public function __construct() {

    }
	
    public function getID() { return $this->id; }
	public function setName( $value ) { $this->title = $value; }
    public function getName() { return $this->title; }
	public function setImage( $value ) { $this->image = $value; }
    public function getImage() { return $this->image; }
	public function getImageURL() { return assets_url('img/icons/social/'.$this->image); }
	public function setSocialURL( $value ) { $this->social_url = $value; }
	public function getSocialURL() { return $this->social_url; }
	public function setStatus( $value ) { $this->status = $value; }
	public function getStatus() { return $this->status; }
 }
 
 /* End of file SocialNetwork.php */
 /* Location: ./system/applications/_backend/models/Entities/SocialNetwork.php */