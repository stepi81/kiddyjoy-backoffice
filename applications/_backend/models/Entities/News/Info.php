<?php

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */

 namespace models\Entities\News;
 
 use models\Entities\Product;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity(repositoryClass="models\NewsRepository")
  * @Table(name="ecom_news")
  */
 class Info {
 	
	/**
	 * @Id
	 * @Column(type="integer", nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;

    /** @Column(type="smallint", length=2, nullable=false) */
    private $type_id;
	/** @Column(type="datetime") */
	private $date;
	/** @Column(type="string", length=120, nullable=false) */
	private $title;
	/** @Column(type="text", nullable=false) */
	private $page;
	/** @Column(type="string", length=60, nullable=true) */
	private $thumb;
	/** @Column(type="smallint", length=2, nullable=false) */
	private $status;
    /** @Column(type="string", length=240, nullable=false) */
    private $summary;
	/** @Column(type="integer", length=10, nullable=false) */
	private $statistic_comments;
	
	/**
     * @OneToMany(targetEntity="InfoImage", mappedBy="info")
     */
	private $images;
	
	/**
     * @ManyToMany(targetEntity="models\Entities\Product")
     * @JoinTable(name="ecom_news_products",
     *      joinColumns={@JoinColumn(name="news_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="product_id", referencedColumnName="id")}
     *      )
     */
    private $products;
	
    /**
     * @ManyToOne(targetEntity="models\Entities\Vendor")
     * @JoinColumn(name="vendor_id", referencedColumnName="id")
     **/
    private $vendor;
    
	public function __construct() {
		
        $this->page = '';
		$this->status = 0;
		$this->statistic_comments = 0;
		
		$this->images = new \Doctrine\Common\Collections\ArrayCollection();
		$this->products = new \Doctrine\Common\Collections\ArrayCollection();
    }
	
    public function getID() { return $this->id; }
    public function setVendor( $value ) { $this->vendor = $value; } 
    public function getVendor() { return $this->vendor; }
    public function setNewsTypeID( $value ) { $this->type_id = $value; }
    public function getNewsTypeID() { return $this->type_id; }
	public function setDate ( $value ) { $this->date = $value; }
    public function getDate() { return $this->date; }
	public function getFormatedDate() { return $this->date->format('d.m.Y'); }
	public function setTitle( $value ) { $this->title = $value; }
	public function getTitle() { return $this->title; }
	public function setPage( $value ) { $this->page = $value; }
	public function getPage() { return $this->page; }
	public function setThumb( $value ) { $this->thumb = $value; }
	public function getThumb() { return $this->thumb; }
	public function getThumbURL() { return $this->thumb ? assets_url('img/news/thumb/'.$this->thumb) : assets_url('img/news/thumb/kiddyjoy_vesti.jpg'); }
	public function setStatus( $value ) { $this->status = $value; }
	public function getStatus() { return $this->status; }
	public function setImage( InfoImage $value ) { $this->images[] = $value; }
    public function getImages() { return $this->images; }
    public function setSummary( $value ) { $this->summary = $value; }
    public function getSummary() { return $this->summary; }
	public function setProduct( Product $value ) { $this->products[] = $value; }
    public function getProducts() { return $this->products; }
	public function setStatisticComments( $value ) { $this->statistic_comments = $value; }
	public function getStatisticComments() { return $this->statistic_comments; }
	
	public function getFrontURL() {
		if( $this->type_id == 1 ){
			return WEB_APP_URL.'novosti/'.url_title($this->title, 'underscore', TRUE).'-'.$this->id;
		} else {
			return WEB_APP_URL.'akcije/'.url_title($this->title, 'underscore', TRUE).'-'.$this->id;	
		}
	}
 }
 
 /* End of file Info.php */
 /* Location: ./system/applications/_backend/models/entities/Info.php */