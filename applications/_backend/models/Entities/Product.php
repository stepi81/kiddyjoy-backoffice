<?php

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */

 namespace models\Entities;

 use models\Entities\Comment\ProductComment;
 use models\Entities\Product\Filter;
 use models\Entities\Product\Sticker;
 use models\Entities\Images\ProductImage;
 use models\Entities\Product\Video;
 use models\Entities\Product\TextFilter;
 use models\Entities\Product\Bundle;
 use models\Entities\Product\ProductColor;
 use models\Entities\AskCustomer; 
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity(repositoryClass="models\ProductRepository")
  * @Table(name="ecom_products")
  */
 class Product {
    
    /**
	 * @Id
	 * @Column(type="integer", length=10, nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
    
	/** @Column(type="string", length=20, nullable=true) */
    private $manufacturer_id;
    /** @Column(type="string", length=20, nullable=true) */        
    private $vendor_id;
    /** @Column(type="string", length=120, nullable=false) */        
    private $name;
    /** @Column(type="text", nullable=false) */
    private $description;
	/** @Column(type="text", nullable=true) */
    private $information;
    /** @Column(type="text", nullable=true) */
    private $other;
    /** @Column(type="string", length=120) */
    private $price_list;
	/** @Column(type="smallint", length=2, nullable=false) */
    private $featured;
    /** @Column(type="smallint", length=2) */
    private $promotion;
    /** @Column(type="smallint", length=2) */
    private $sale;
    /** @Column(type="smallint", length=2) */
    private $outlet;
    /** @Column(type="smallint", length=2) */
    private $highlight_category;
    /** @Column(type="smallint", length=2) */
    private $highlight_subcategory;
    /** @Column(type="integer", length=10) */
    private $statistic_visits;
    /** @Column(type="integer", length=10) */
    private $statistic_votes;
    /** @Column(type="decimal", precision=2, scale=1) */
    private $statistic_rating;
    /** @Column(type="integer", length=10) */
    private $statistic_sold;
	/** @Column(type="integer", length=10, nullable=false) */
	private $statistic_comments;
    /** @Column(type="datetime") */
    private $release_date;
	/** @Column(type="decimal", precision=2, scale=1, nullable=false) */
    private $price;
	/** @Column(type="decimal", precision=2, scale=1, nullable=false) */
    private $price_retail;
	/** @Column(type="decimal", precision=2, scale=1, nullable=false) */
    private $old_price;	
    /** @Column(type="integer", length=2) */
    private $status;
	/** @Column(type="integer", length=2) */
    private $vat;
    /** @Column(type="string", length=250, nullable=true) */
    private $vendor;
	
	/**
     * @ManyToOne(targetEntity="models\Entities\Product\Brand")
     * @JoinColumn(name="brand_id", referencedColumnName="id")
     */
    private $brand;
    
    /**
     * @ManyToOne(targetEntity="models\Entities\Product\Category")
     * @JoinColumn(name="category_id", referencedColumnName="id")
     **/
    private $category;
    
    /**
     * @ManyToOne(targetEntity="models\Entities\Product\Subcategory")
     * @JoinColumn(name="subcategory_id", referencedColumnName="id")
     **/
    private $subcategory;
	
	/**
     * @ManyToOne(targetEntity="models\Entities\Product\Sticker")
     * @JoinColumn(name="sticker_id", referencedColumnName="id")
     */
    private $sticker;
    
    /**
     * @ManyToOne(targetEntity="models\Entities\Product\Warranty")
     * @JoinColumn(name="warranty_id", referencedColumnName="id")
     */
    private $warranty;

    /**
     * @OneToMany(targetEntity="models\Entities\Images\ProductImage", mappedBy="product", cascade={"remove"})
     * @OrderBy({"position" = "ASC"})
     */
    private $images;
    
    /**
     * @OneToMany(targetEntity="models\Entities\Product\Video", mappedBy="product", cascade={"remove"})
     * @OrderBy({"position" = "ASC"})
     */
    private $videos;
    
    /**
     * @OneToMany(targetEntity="models\Entities\Product\TextFilter", mappedBy="product", cascade={"remove"})
     */
    private $text_filter;

    /**
     * @OneToMany(targetEntity="models\Entities\Comment\ProductComment", mappedBy="product", cascade={"remove"})
     * @OrderBy({"date" = "DESC"})
     */
    private $comments;
    
    /**
     * @OneToMany(targetEntity="models\Entities\AskCustomer", mappedBy="product", cascade={"remove"})
     */
    private $questions;
    
    /**
     * @ManyToMany(targetEntity="models\Entities\Product\Filter")
     * @JoinTable(name="ecom_products_filters",
     *      joinColumns={@JoinColumn(name="product_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="filter_id", referencedColumnName="id")}
     *      )
     */
    private $filters;
    
    /**
     * @ManyToMany(targetEntity="models\Entities\Product\Bundle")
     * @JoinTable(name="ecom_products_bundles",
     *      joinColumns={@JoinColumn(name="product_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="bundle_id", referencedColumnName="id")}
     *      )
     */
    private $bundles;
	
	/**
     * @ManyToMany(targetEntity="models\Entities\Product\Category")
     * @JoinTable(name="ecom_product_categories_highlights",
     *      joinColumns={@JoinColumn(name="product_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="category_id", referencedColumnName="id")}
     *      )
     */
    private $category_highlights;
	
	/**
     * @OneToMany(targetEntity="models\Entities\Product\ProductColor", mappedBy="product", cascade={"remove"})
     * @OrderBy({"position" = "ASC"})
     */
    private $product_colors;
	
	/**
     * @OneToMany(targetEntity="models\Entities\Product\ProductSize", mappedBy="product", cascade={"remove"})
     * @OrderBy({"position" = "ASC"})
     */
    private $product_sizes;
	
	/**
     * @ManyToMany(targetEntity="models\Entities\Product\Sticker")
     * @JoinTable(name="ecom_products_stickers",
     *      joinColumns={@JoinColumn(name="product_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="sticker_id", referencedColumnName="id")}
     *      )
     */
    private $stickers;

    public function __construct() {
    		
    	$this->featured = 0;
		$this->promotion = 0;
		$this->sale = 0;
		$this->outlet = 0;
		//$this->statistic_rating = 0;
		//$this->statistic_sold = 0;
		$this->statistic_visits = 0;
		//$this->statistic_votes = 0;
		$this->statistic_comments = 0;
		$this->price_retail = 0;
		$this->old_price = 0;
		$this->vat = 20;
        
        $this->filters          = new \Doctrine\Common\Collections\ArrayCollection();    
        $this->text_filter      = new \Doctrine\Common\Collections\ArrayCollection();
        $this->images           = new \Doctrine\Common\Collections\ArrayCollection();
        $this->videos           = new \Doctrine\Common\Collections\ArrayCollection();
        $this->comments         = new \Doctrine\Common\Collections\ArrayCollection(); 
        $this->bundles          = new \Doctrine\Common\Collections\ArrayCollection();
        $this->stock            = new \Doctrine\Common\Collections\ArrayCollection();
        $this->questions		= new \Doctrine\Common\Collections\ArrayCollection();
        $this->product_colors	= new \Doctrine\Common\Collections\ArrayCollection();
		$this->product_sizes	= new \Doctrine\Common\Collections\ArrayCollection();
		$this->stickers         = new \Doctrine\Common\Collections\ArrayCollection();
		$this->category_highlights	= new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function setID( $value ) { $this->id = $value; }
    public function getID() { return $this->id; }
	public function setManufacturerID( $value ) { $this->manufacturer_id = $value; }
	public function getManufacturerID() { return $this->manufacturer_id; }
    public function setVendorID( $value ) { $this->vendor_id = $value; }
    public function getVendorID() { return $this->vendor_id; }
    public function setName( $value ) { $this->name = $value; }
    public function getName() { return $this->name; }
    public function setDescription( $value ) { $this->description = $value; }
    public function getDescription() { return nl2br($this->description); }
	public function setInformation( $value ) { $this->information = $value; }
    public function getInformation() { return nl2br($this->information); }
    public function setOther( $value ) { $this->other = $value; }
    public function getOther() { return $this->other; }
    public function setPriceList( $value ) { $this->price_list = $value; }
    public function getPriceList() { return $this->price_list; }
	public function setFeatured( $value ) { $this->featured = $value; }
	public function getFeatured() { return $this->featured; }
    public function setPromotion( $value ) { $this->promotion = $value; }
    public function getPromotion() { return $this->promotion; }
    public function setSale( $value ) { $this->sale = $value; }
    public function getSale() { return $this->sale; }
    public function setOutlet( $value ) { $this->outlet = $value; }
    public function getOutlet() { return $this->outlet; }
    public function setHighlightCategory( $value ) { $this->highlight_category = $value; }
    public function getHighlightCategory() { return $this->highlight_category; }  
    public function setHighlightSubcategory( $value ) { $this->highlight_subcategory = $value; }
    public function getHighlightSubcategory() { return $this->highlight_subcategory; }
    public function setReleaseDate () { $this->release_date = new \DateTime("now"); } 
    public function getReleaseDate() { return $this->release_date; } 
    public function getFormatedReleaseDate() { return $this->release_date->format('d.m.Y H:i'); }
    public function setStatus( $value ) { $this->status = $value; }
    public function getStatus() { return $this->status; }
    public function setWarranty( $value ) { $this->warranty = $value; }
    public function getWarranty() { return $this->warranty; }
    public function setStatisticVisits( $value ) { $this->statistic_visits = $value; }
    public function getStatisticVisits() { return $this->statistic_visits; }
    public function setStatisticVotes( $value ) { $this->statistic_votes = $value; }
    public function getStatisticVotes() { return $this->statistic_votes; } 
    public function setStatisticRating( $value ) { $this->statistic_rating = $value; }  
    public function getStatisticRating() { return $this->statistic_rating; }
    public function setStatisticSold( $value ) { $this->statistic_sold = $value; } 
    public function getStatisticSold() { return $this->statistic_sold; }
	public function setStatisticComments( $value ) { $this->statistic_comments = $value; }
	public function getStatisticComments() { return $this->statistic_comments; }
	
	public function setPrice( $value ) { $this->price = $value; }
	public function getPrice() { return $this->price; }
	public function setPriceRetail( $value ) { $this->price_retail = $value; }
	public function getPriceRetail() { return $this->price_retail; }
	public function setOldPrice( $value ) { $this->old_price = $value; }
	public function getOldPrice() { return $this->old_price; }
	public function setBrand( $value ) { $this->brand = $value; }
	public function getBrand() { return $this->brand; }
    
    public function setCategory( $value ) { $this->category = $value; }
    public function getCategory() { return $this->category; }
    public function setSubcategory( $value ) { $this->subcategory = $value; }
    public function getSubcategory() { return $this->subcategory; }
    public function getSubcategoryName() { return $this->subcategory->getName(); }
    
    public function getFilters() { return $this->filters; }
	public function getProductColors() { return $this->product_colors; }
	public function getProductSizes() { return $this->product_sizes; }
    public function setImage( ProductImage $value ) { $this->images[] = $value; }   
    public function getImages() { return $this->images; }  
    public function setVideo( Video $value ) { $this->videos[] = $value; }
    public function getVideos() { return $this->videos; }  
    
	public function setBundle( Bundle $value ) { if(!$this->bundles->contains( $value )) { $this->bundles[] = $value; } }

    public function getBundles() { return $this->bundles; }
	
    public function setSticker( $value ) { $this->sticker = $value; }
    public function getSticker() { return $this->sticker; }
	public function setStickers( $value ) { $this->stickers[] = $value; }
	public function getStickers() { return $this->stickers; }

    public function setProductFilter( Filter $value ) { $this->filters[] = $value; } 
    public function getProductFilters() { return $this->filters; } 
    public function setProductTextFilter( TextFilter $value ) { $this->text_filter[] = $value; } 
    public function getProductTextFilters() { return $this->text_filter; } 
	public function setCategoryHighlight( $value ) { $this->category_highlights[] = $value; } 
	public function getCategoryHighlights() { return $this->category_highlights; }
	public function setVat( $value ) { $this->vat = $value; }
	public function getVat() { return $this->vat; }
    public function setVendor( $value ) { $this->vendor = $value; }
    public function getVendor() { return $this->vendor; }
    
    public function getBundle( $id ) {
        foreach( $this->bundles as $bundle ) {
            if( $bundle->getID() == $id ) return $bundle;
        }
    }   
    
    public function getTechnologies() {
        $technologies = array();
        foreach ($this->filters as $filter)
            if($filter->getTechnology()) $technologies[] = $filter->getTechnology();
        return $technologies; 
    }
    
    /* added by Goran - temporary for now*/
    public function getPriceHistory() { return array($this->getPrice()); }
    public function getPriceHistoryString() {
        return implode(',', $this->getPriceHistory());
    }
    
    public function getFormatedPrice() {
        return number_format( $this->getPrice(), 2, ',', '.' ) . ' RSD';
    }
    
    public function getURL() {
        return WEB_APP_URL.url_title($this->getSubcategoryName(), 'underscore', TRUE).'/'.url_title($this->name, 'underscore', TRUE).'-'.$this->id;
    }

    public function getImage( $path ) {
        if ( $this->images->count() ) return $this->images->first()->getURL($path);
        else {
            $image = new ProductImage();
            return $image->getURL($path);
        }
    }
    
    public function getImageName() {
        if ( $this->images->count() ) return $this->images->first()->getName();
        else return 'kiddyjoy.jpg';
    }
    
    public function getComments() {
        
        foreach( $this->comments as $comment )
            if( !$comment->getStatus() ) $keys[] = $this->comments->indexOf($comment);
        
        if( isset($keys) ) {
            foreach( $keys as $key )
                $this->comments->remove($key);
        }
        
        return $this->comments;
    }
    
    public function countComments() {
        $counter = 0;
        foreach( $this->comments as $comment )
            if( $comment->getStatus() ) $counter++;
        return $counter;
    }
    
    public function getFilter( $specification ) {
        foreach( $this->filters as $filter ) {
            if( $filter->getSpecification()->getID() == $specification->getID() ) return $filter->getName();
        }
        return NULL;
    }

    public function getTextFilter( $specification ) {
        foreach( $this->text_filter as $text_filter ) {
            if( $text_filter->getSpecification()->getID() == $specification->getID() ) return $text_filter->getDescription();
        }
        return NULL;
    }
    
    public function getBrandName() {
        try {
            return $this->getBrand()->getName();
        }
        catch( \Doctrine\ORM\EntityNotFoundException $e ) {
            
            return '';
        }
    }
    
    public function getBrandImage() {
        try {
            return $this->getBrand()->getImageURL();
        }
        catch( \Doctrine\ORM\EntityNotFoundException $e ) {
            
            return '';
        }
    }

    public function getFilterList() {
        
        foreach( $this->filters as $filter ) $id_list[] = $filter->getID();
        return isset($id_list) ? $id_list : NULL;
    }
    
    public function removeBundles( $id_list ) {
    	
    	foreach( $this->bundles as $bundle )
    		if( in_array($bundle->getID(), $id_list) ) $this->bundles->removeElement($bundle);
    }
}
 
 /* End of file Product.php */
 /* Location: ./system/applications/_frontend/models/Entities/Product.php */
