<?php

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */

 namespace models\Entities;
 use models\Entities\Images\PageImage;
 use models\Entities\InfoSection;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity(repositoryClass="models\InfoDeskRepository")
  * @Table(name="ecom_pages")
  */
 class InfoDesk {
    
    /**
     * @Id
     * @Column(type="integer", length=10, nullable=false)
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;
		
	/**
     * @ManyToOne(targetEntity="InfoSection")
     * @JoinColumn(name="section_id", referencedColumnName="id")
     */
	private $section;
	

    /** @Column(type="string", length=60, nullable=false) */
    private $name;
    /** @Column(type="string", length=60, nullable=true) */
    private $title;
    /** @Column(type="text", nullable=false) */
    private $content;
	
	/** @Column(type="string", length=60, nullable=true) */
	private $icon;
	
	/** @Column(type="integer", length=4, nullable=false) */
	private $position;
	
    /** @Column(type="smallint", length=2, nullable=false) */
    private $featured;
	/** @Column(type="smallint", length=2, nullable=false) */
    private $status;
    
    /*
     * @OneToMany(targetEntity="models\Entities\Images\PageImage", mappedBy="page")
     */
    private $images;
    
    public function __construct() {
        
        $this->content = '';
        $this->status = 0;
        
        $this->images = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function getID() { return $this->id; }
	
	public function getSection() { return $this->section; }
	public function setSection( InfoSection $value ) { $this->section = $value; }
	
	public function setName( $value ) { $this->name = $value; }
    public function getName() { return $this->name; }
    public function setTitle( $value ) { $this->title = $value; }
    public function getTitle() { return $this->title; }
    public function setContent( $value ) { $this->content = $value; }
    public function getContent() { return $this->content; }

	public function setIcon( $value ) { $this->icon = $value; }
	public function getIcon() { return $this->icon; }
	public function getIconURL() { return $this->icon ? assets_url('img/icons/pages/'.$this->icon) : assets_url('img/icons/pages/default_kiddyjoy_icon.png'); }
	
	public function setPosition( $value ) { $this->position = $value; }
    public function getPosition() { return $this->position; }

    public function setFeatured( $value ) { $this->featured = $value; if(!($value)) $this->featured = NULL; }
    public function getFeatured() { return $this->featured; }

    public function setStatus( $value ) { $this->status = $value; }
    public function getStatus() { return $this->status; }
    public function setImage( PageImage $value ) { $this->images[] = $value; }
    public function getImages() { return $this->images; }
    public function getURL() { return WEB_APP_URL.url_title($this->name, 'underscore', TRUE); }
 }
 
 /* End of file InfoDesk.php */
 /* Location: ./system/applications/_backend/models/Entities/InfoDesk.php */