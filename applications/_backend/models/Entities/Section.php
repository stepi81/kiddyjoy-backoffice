<?php

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */

 namespace models\Entities;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity
  * @Table(name="backoffice_sections")
  */
 class Section {
 	
	/**
	 * @Id
	 * @Column(type="integer", nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/** @Column(type="string", length=60, nullable=false) */
	private $name;
	/** @Column(type="string", length=60, nullable=true) */
	private $controller;
	/** @Column(type="string", length=60, nullable=true) */
	private $method;
	/** @Column(type="integer", length=2, nullable=true) */
	private $visibility;
	/** @Column(type="integer", length=2, nullable=true) */
	private $vendor_visibility;
	/** @Column(type="integer", length=2, nullable=true) */
	private $mobile_visibility;
	
	/**
     * @OneToMany(targetEntity="Section", mappedBy="parent")
     **/
    private $children;
	
	/**
     * @ManyToOne(targetEntity="Section", inversedBy="children")
     * @JoinColumn(name="parent_id", referencedColumnName="id")
     **/
    private $parent;
	
	public function __construct() {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }
	
    public function getID() { return $this->id; }
	public function setName( $value ) { $this->name = $value; }
	public function getName() { return $this->name; }
	public function setController( $value ) { $this->controller = $value; }
	public function getController() { return $this->controller; }
	public function setMethod( $value ) { $this->method = $value; }
	public function getMethod() { return $this->method; }
	public function getVisibility() { return $this->visibility; }
	public function getVendorVisibility() { return $this->vendor_visibility; }
	public function getMobileVisibility() { return $this->mobile_visibility; }
	public function getChildren() { return $this->children; }
	public function getParent() { return $this->parent; }
	public function getURI() { return isset($this->method) ? site_url($this->getController().'/'.$this->getMethod()) : site_url($this->getController()); }
 }
 
 /* End of file Section.php */
 /* Location: ./system/applications/_backend/models/entities/Section.php */