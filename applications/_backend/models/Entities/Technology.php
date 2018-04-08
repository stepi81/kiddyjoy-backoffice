<?php

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */

 namespace models\Entities;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity(repositoryClass="models\TechnologyRepository")
  * @Table(name="ecom_technologies")
  */
 class Technology {
 	
	/**
	 * @Id
	 * @Column(type="integer", nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/** @Column(type="string", length=240, nullable=false) */
	private $title;
	/** @Column(type="text", nullable=false) */
	private $description;
	/** @Column(type="string", length=120, nullable=true) */
	private $image;

	
	public function __construct() {
		
    }
	
    public function getID() { return $this->id; }
	public function setTitle( $value ) { $this->title = $value; }
	public function getTitle() { return $this->title; }
	public function setDescription( $value ) { $this->description = $value; }
	public function getDescription() { return $this->description; }
	public function setImage( $value ) { $this->image = $value; }
	public function getImage() { return $this->image; }
	public function getImageURL() { return $this->image ? assets_url('img/technologies/'.$this->image) : assets_url('img/technologies/kiddyjoy_technologies.png'); }
 }
 
 /* End of file Technology.php */
 /* Location: ./system/applications/_backend/models/Entities/Technology.php */