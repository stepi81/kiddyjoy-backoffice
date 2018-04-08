<?php

/**
 * ...
 * @author Aleksandar Milas [ Codeion ]
 */

 namespace models\Entities;
 
 use models\Entities\Images\PreorderImage;
 use models\Entities\PreorderItem;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity(repositoryClass="models\PreorderPagesRepository")
  * @Table(name="ecom_preorders")
  */
 class Preorder {
 	
	/**
	 * @Id
	 * @Column(type="integer", length=10, nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/** @Column(type="string", length=16, nullable=true) */
    private $vendor_id;
    /** @Column(type="smallint", length=2, nullable=false) */
    private $type_id;
	/** @Column(type="datetime") */
	private $date;
	/** @Column(type="string", length=120, nullable=false) */
	private $title;
	/** @Column(type="string", length=240, nullable=false) */
	private $summary;
	/** @Column(type="text", nullable=false) */
	private $page;
	/** @Column(type="string", length=60, nullable=true) */
	private $thumb;
	/** @Column(type="smallint", length=2, nullable=false) */
	private $status;
	
	/**
     * @OneToMany(targetEntity="models\Entities\Images\PreorderImage", mappedBy="preorder")
     */
	private $images;
	
	/**
     * @OneToMany(targetEntity="models\Entities\PreorderItem", mappedBy="preorder")
     */
    private $items;
	
	public function __construct() {
		
        $this->page = '';
		$this->status = 0;
		
        $this->items         = new \Doctrine\Common\Collections\ArrayCollection();
		$this->images = new \Doctrine\Common\Collections\ArrayCollection();
    }
	
    public function getID() { return $this->id; }
	public function setPreorderTypeID( $value ) { $this->type_id = $value; }
    public function getPreorderTypeID() { return $this->type_id; }
	public function setDate ( $value ) { $this->date = $value; }
    public function getDate() { return $this->date; }
	public function getFormatedDate() { return $this->date->format('d.m.Y'); }
	public function setTitle( $value ) { $this->title = $value; }
	public function getTitle() { return $this->title; }
	public function setSummary( $value ) { $this->summary = $value; }
	public function getSummary() { return $this->summary; }
	public function setPage( $value ) { $this->page = $value; }
	public function getPage() { return $this->page; }
	public function setThumb( $value ) { $this->thumb = $value; }
	public function getThumb() { return $this->thumb; }
	public function getThumbURL() { return $this->thumb ? asset_url('img/preorders/'.$this->thumb) : asset_url('img/preorders/kiddyjoy_vesti.jpg'); }
	public function setStatus( $value ) { $this->status = $value; }
	public function getStatus() { return $this->status; }
	public function setImage( PreorderImage $value ) { $this->images[] = $value; }
    public function getImages() { return $this->images; }
	
	public function setItem( PreorderItem $value ) { $this->items[] = $value; }
	public function getItems() { return $this->items; }
	
	
 }
 
 /* End of file Preorder.php */
 /* Location: ./system/applications/_backend/models/Entities/Preorder.php */