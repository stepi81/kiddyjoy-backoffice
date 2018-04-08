<?php

/**
 * ...
 * @author Marko Stepanovic [ Codeion ]
 */

 namespace models\Entities\Cart;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity(repositoryClass="models\CartSectionRepository")
  * @Table(name="ecom_cart_sections")
  */
 class Section {
 	
    /**
     * @Id
     * @Column(type="integer", length=10, nullable=false)
     * @GeneratedValue(strategy="AUTO")
     */
	private $id;
	
	/** @Column(type="string", length=60, nullable=false) */
	private $route;
	/** @Column(type="string", length=60, nullable=false) */
	private $label;
	/** @Column(type="string", length=240, nullable=false) */
	private $title;
	/** @Column(type="text", nullable=false) */
	private $description;
	/** @Column(type="smallint", length=2, nullable=false) */
	private $status;
	
    public function getID() { return $this->id; }
	public function setRoute( $value ) { $this->route = $value; }
    public function getRoute() { return $this->route; }
	public function setLabel( $value ) { $this->label = $value; }
	public function getLabel() { return $this->label; }
	public function setTitle( $value ) { $this->title = $value; }
	public function getTitle() { return $this->title; }
	public function setDescription( $value ) { $this->description = $value; }
	public function getDescription() { return $this->description; }
	public function setStatus( $value ) { $this->status = $value; }
	public function getStatus() { return $this->status; }
 }
 
 /* End of file Section.php */
 /* Location: ./system/applications/_frontend/models/Entities/Cart/Section.php */