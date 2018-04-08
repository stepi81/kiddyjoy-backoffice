<?php

/**
 * ...
 * @author Marko Stepanovic [ Codeion ]
 */

 namespace models\Entities;
 
 use models\Entities\Product;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity(repositoryClass="models\ArticleRepository")
  * @Table(name="ecom_articles")
  */
 class Article {
 	
	/**
	 * @Id
	 * @Column(type="integer", nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/** @Column(type="integer", length=10, nullable=false) */
	private $appliance_id;
	/** @Column(type="datetime") */
	private $date;
	/** @Column(type="string", length=120, nullable=false) */
	private $title;
	/** @Column(type="text", nullable=false) */
	private $content;
	/** @Column(type="string", length=60, nullable=true) */
	private $thumb;
	/** @Column(type="string", length=60, nullable=true) */
	private $image;
	/** @Column(type="smallint", length=2, nullable=false) */
	private $status;
    /** @Column(type="string", length=240, nullable=true) */
    private $summary;
	/** @Column(type="integer", length=2, nullable=false) */
	private $statistic_visits;
	/** @Column(type="integer", length=2, nullable=false) */
	private $statistic_votes;
	/** @Column(type="decimal", precision=2, scale=1, nullable=false) */
	private $statistic_rating;
	/** @Column(type="integer", length=10, nullable=false) */
	private $statistic_comments;
	
	/**
     * @ManyToOne(targetEntity="models\Entities\Article\Category", inversedBy="articles")
     * @JoinColumn(name="category_id", referencedColumnName="id")
     */
	private $category;
	
	/**
     * @OneToMany(targetEntity="models\Entities\Article\Image", mappedBy="article")
     */
	private $images;
	
	/**
     * @ManyToMany(targetEntity="models\Entities\Product")
     * @JoinTable(name="ecom_articles_products",
     *      joinColumns={@JoinColumn(name="article_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="product_id", referencedColumnName="id")}
     *      )
     */
    private $products;
    
	/**
     * @OneToMany(targetEntity="models\Entities\Article\Highlight", mappedBy="article", cascade={"remove"})
     */
	private $highlights;
    
	public function __construct() {
		
        $this->content = '';
		$this->status = 0;
		$this->statistic_visits = 0;
		$this->statistic_votes = 0;
		$this->statistic_rating = 0;
		$this->statistic_comments = 0;
		
		$this->images = new \Doctrine\Common\Collections\ArrayCollection();
		$this->products = new \Doctrine\Common\Collections\ArrayCollection();
		$this->highlights = new \Doctrine\Common\Collections\ArrayCollection();
    }
	
    public function getID() { return $this->id; }
	public function setApplianceID( $value ) { $this->appliance_id = $value; }
	public function getApplianceID() { return $this->appliance_id; }
    public function setCategory( $value ) { $this->category = $value; }
    public function getCategory() { return $this->category; }
	public function setDate ( $value ) { $this->date = $value; }
    public function getDate() { return $this->date; }
	public function getFormatedDate() { return $this->date->format('d.m.Y'); }
	public function setTitle( $value ) { $this->title = $value; }
	public function getTitle() { return $this->title; }
	public function setContent( $value ) { $this->content = $value; }
	public function getContent() { return $this->content; }
	public function setThumb( $value ) { $this->thumb = $value; }
	public function getThumb() { return $this->thumb; }
	public function getThumbURL() { return $this->thumb ? assets_url('img/articles/thumb/'.$this->thumb) : assets_url('img/articles/thumb/ecom_vesti.jpg'); }
	public function setImage( $value ) { $this->image = $value; }
	public function getImage() { return $this->image; }
	public function getImageURL() { return $this->image ? assets_url('img/articles/medium/'.$this->image) : assets_url('img/articles/large/ecom_vesti.jpg'); }
	public function setStatus( $value ) { $this->status = $value; }
	public function getStatus() { return $this->status; }
	public function setStatisticVisits( $value ) { $this->statistic_visits = $value; }
	public function getStatisticVisits() { return $this->statistic_visits; }
	public function setStatisticVotes( $value ) { $this->statistic_votes = $value; }
	public function getStatisticVotes() { return $this->statistic_votes; }
	public function setStatisticRating( $value ) { $this->statistic_rating = $value; }
	public function getStatisticRating() { return $this->statistic_rating; }
	public function setStatisticComments( $value ) { $this->statistic_comments = $value; }
	public function getStatisticComments() { return $this->statistic_comments; }
	public function setContentImage( $value ) { $this->images[] = $value; }
    public function getContentImages() { return $this->images; }
    public function setSummary( $value ) { $this->summary = $value; }
    public function getSummary() { return $this->summary; }
	public function setProduct( Product $value ) { $this->products[] = $value; }
    public function getProducts() { return $this->products; }
	
	public function getFrontURL() {
		if( $this->category->getParent() ) {
			return WEB_APP_URL.'clanci/'.url_title($this->category->getParent()->getName(), 'underscore', TRUE).'/'.url_title($this->title, 'underscore', TRUE).'-'.$this->id;
		} else {
			return WEB_APP_URL.'clanci/'.url_title($this->category->getName(), 'underscore', TRUE).'/'.url_title($this->title, 'underscore', TRUE).'-'.$this->id;	
		}
	}
 }
 
 /* End of file Article.php */
 /* Location: ./system/applications/_backend/models/entities/Article.php */