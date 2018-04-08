<?php

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */

 namespace models\Entities\Product;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity(repositoryClass="models\StickerRepository") 
  * @Table(name="ecom_stickers")
  */
 class Sticker {
 	
	/**
	 * @Id
	 * @Column(type="integer", length=4, nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/** @Column(type="string", length=60, nullable=false) */
	private $name;
	/** @Column(type="string", length=60, nullable=false) */
	private $image;
	/** @Column(type="smallint", length=2, nullable=false) */
	private $type_id;
	
	public function __construct() {
		
		$this->type_id = 1;
    }
	
    public function getID() { return $this->id; }
	public function setName( $value ) { $this->name = $value; }
    public function getName() { return $this->name; }
	public function setImage( $value ) { $this->image = $value; }
    public function getImage() { return $this->image; }
	public function getImageURL() { return assets_url('img/stickers/'.$this->image); }
	public function setTypeID( $value ) { $this->type_id = $value; }
	public function getTypeID() { return $this->type_id; }
 }
 
 /* End of file Sticker.php */
 /* Location: ./system/applications/_backend/models/Entities/Product/Sticker.php */