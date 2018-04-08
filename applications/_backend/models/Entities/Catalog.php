<?php

/**
 * ...
 * @author Ivan Despic [ Codeion ]
 */

 namespace models\Entities;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity(repositoryClass="models\CatalogRepository")
  * @Table(name="ecom_catalogs")
  */
 class Catalog {
 	
	/**
	 * @Id
	 * @Column(type="integer", nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/** @Column(type="string", length=60, nullable=false) */
	private $title;
	/** @Column(type="string", length=20, nullable=false) */
	private $edition;
	/** @Column(type="string", length=60, nullable=false) */
	private $image;
	/** @Column(type="string", length=60, nullable=false) */
	private $pdf;
	/** @Column(type="datetime") */
	private $date;
	/** @Column(type="smallint", length=2, nullable=false) */
	private $status;
	
	public function getID() { return $this->id; }
	public function setDate() { $this->date = new \DateTime("now"); }
    public function getDate() { return $this->date; }
    public function getFormatedDate() { return $this->date->format('d/m/Y'); }
	public function setTitle( $value ) { $this->title = $value; }
    public function getTitle() { return $this->title; }
	public function setEdition( $value ) { $this->edition = $value; }
	public function getEdition() { return $this->edition; }
	public function setImage( $value ) { $this->image = $value; }
	public function getImage() { return assets_url( 'img/catalogs/'.$this->image ); }
    public function getImageName() { return $this->image; }
	public function setPDF( $value ) { $this->pdf = $value; }
	public function getPDF() { return site_url( 'download/'.$this->pdf ); }
    public function getPDFName() { return $this->pdf; }
	public function setStatus( $value ) { $this->status = $value; }
	public function getStatus() { return $this->status; }
 }
 
 /* End of file Catalog.php */
 /* Location: ./system/applications/_backend/models/Entities/Catalog.php */