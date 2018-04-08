<?php

/**
 * ...
 * @author Marko Stepanovic [ Codeion ]
 */
 
 namespace models\Entities;
 
 use models\Entities\Images\BackgroundImage;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity(repositoryClass="models\BackgroundRepository")
  * @Table(name="ecom_backgrounds")
  */
 class Background {
     
    /**
     * @Id
     * @Column(type="integer", nullable=false)
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;
	
	/**
     * @ManyToOne(targetEntity="models\Entities\Vendor")
     * @JoinColumn(name="vendor_id", referencedColumnName="id")
     **/
    private $vendor;
    /** @Column(type="string", length=60, nullable=false) */
    private $name;
	/** @Column(type="string", length=60, nullable=true) */
    private $object_class;
	/** @Column(type="integer", length=10, nullable=true) */
    private $object_id;
    /** @Column(type="integer", length=2, nullable=false) */
    private $status;
	
	/**
     * @OneToMany(targetEntity="models\Entities\Images\BackgroundImage", mappedBy="background")
     */
	private $images;
	
	public function __construct() {

		$this->images = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function getID() { return $this->id; }
	
	public function setVendor( $value ) { $this->vendor = $value; } 
    public function getVendor() { return $this->vendor; }
	
    public function setName( $value ) { $this->name = $value; }
    public function getName() { return $this->name; }
	public function setObjectClass( $value ) { $this->object_class =  $value; }
	public function getObjectClass() { return $this->object_class; }
	public function setObjectID( $value ) { $this->object_id = $value; }
	public function getObjectID() { return $this->object_id; }
    public function setStatus( $value ) { $this->status = $value; }
    public function getStatus() { return $this->status; }
	public function getImages() { return $this->images; }
 }
 
 /* End of file Background.php */
 /* Location: ./system/applications/_backend/models/entities/Background.php */