<?php

/**
 * ...
 * @author Aleksandar Milas [ Codeion ]
 */

 namespace models\Entities\Images;
 
 use models\Entities\Background;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity
  * @Table(name="ecom_background_images")
  */
 class BackgroundImage {
 	
	/**
	 * @Id
	 * @Column(type="integer", nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/** @Column(type="string", length=60, nullable=false) */
	private $name;
	/** @Column(type="smallint", length=2, nullable=false) */
	private $position;
	/** @Column(type="string", length=60, nullable=false) */
	private $url;
	
	/**
     * @ManyToOne(targetEntity="models\Entities\Background", inversedBy="images")
     * @JoinColumn(name="background_id", referencedColumnName="id")
     */
	private $background;
	
    public function getID() { return $this->id; }
	public function setName( $value ) { $this->name = $value; }
    public function getName() { return $this->name; }
	public function setPosition( $value ) { $this->position = $value; }
	public function getPosition() { return $this->position; }
	public function setURL( $value ) { $this->url = $value; }
	public function getURL() { return $this->url; }
	public function setBackground( Background $value ) { $this->background = $value; }
    public function getBackground() { return $this->background; }
	public function getImageURL() { return ECOM_APP_URL.'img/backgrounds/'.$this->name; }
 }
 
 /* End of file BackgroundImage.php */
 /* Location: ./system/applications/_backend/models/Entities/Images/BackgroundImage.php */