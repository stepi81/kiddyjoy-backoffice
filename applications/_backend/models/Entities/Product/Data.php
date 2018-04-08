<?php

/**
 * ...
 * @author Ivan Despic[ Codeion ]
 */

 namespace models\Entities\Product;
 
 use models\Entities\Product;
 use models\Entities\Product\Filter;
 use models\Entities\Product\Sticker;
 use models\Entities\Images\ProductImage;
 use models\Entities\Product\Video;
 use models\Entities\Product\TextFilter;
 use models\Entities\Product\Bundle;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 /**
  * @Entity(repositoryClass="models\ProductDataRepository") 
  * @Table(name="ecom_product_data")
  */
 class Data {
    
    /**
     * @Id
     * @OneToOne(targetEntity="models\Entities\Product")
     * @JoinColumn(name="id", referencedColumnName="id")
     **/
    private $product;

    /** @Column(type="text", nullable=true) */
    private $name;
    /** @Column(type="text", nullable=true) */
    private $description;
    /** @Column(type="string", length=120, nullable=true) */
    private $price_list;
     /** @Column(type="smallint", length=2, nullable=false) */
    private $promotion;
    /** @Column(type="smallint", length=2, nullable=false) */
    private $sale;
    /** @Column(type="smallint", length=2, nullable=false) */
    private $outlet;
    /** @Column(type="smallint", length=2, nullable=false) */
    private $highlight_category;     
    /** @Column(type="smallint", length=2, nullable=false) */
    private $highlight_subcategory;        
    /** @Column(type="integer", length=10, nullable=true) */
    private $statistic_visits;    
    /** @Column(type="integer", length=10, nullable=true) */
    private $statistic_votes;    
    /** @Column(type="decimal", precision=2, scale=1, nullable=true) */
    private $statistic_rating;
    /** @Column(type="integer", length=10, nullable=true) */
    private $statistic_sold;
    /** @Column(type="datetime") */
    protected $release_date;
    /** @Column(type="smallint", length=2, nullable=false) */
    private $status; 
    
      /**
     * @ManyToMany(targetEntity="models\Entities\Product\Filter")
     * @JoinTable(name="ecom_products_filters",
     *      joinColumns={@JoinColumn(name="product_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="filter_id", referencedColumnName="id")}
     *      )
     */
    private $filters;
    
    /**
     * @ManyToMany(targetEntity="models\Entities\Product\Sticker")
     * @JoinTable(name="ecom_products_stickers",
     *      joinColumns={@JoinColumn(name="product_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="sticker_id", referencedColumnName="id")}
     *      )
     */
    private $stickers;
    
    /**
     * @OneToMany(targetEntity="models\Entities\Images\ProductImage", mappedBy="product", cascade={"remove"})
     */
    private $images; 
    
    /**
     * @OneToMany(targetEntity="models\Entities\Product\TextFilter", mappedBy="product", cascade={"remove"})
     */
    private $text_filter;
    
    /**
     * @OneToMany(targetEntity="models\Entities\Product\Video", mappedBy="product", cascade={"remove"})
     * @OrderBy({"position" = "ASC"})
     */
    private $videos;
    
    /**
     * @ManyToMany(targetEntity="models\Entities\Product\Bundle")
     * @JoinTable(name="ecom_products_bundles",
     *      joinColumns={@JoinColumn(name="product_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="bundle_id", referencedColumnName="id")}
     *      )
     */
    private $bundles;

    /**
     * @ManyToOne(targetEntity="models\Entities\Product\Category")
     * @JoinColumn(name="category_id", referencedColumnName="id")
     **/
    private $category;
    
    /**
     * @ManyToOne(targetEntity="models\Entities\Product\Subcategory", inversedBy="products")
     * @JoinColumn(name="subcategory_id", referencedColumnName="id")
     **/
    private $subcategory;

    /**
     * @ManyToOne(targetEntity="models\Entities\Product\Warranty", inversedBy="products")
     * @JoinColumn(name="warranty_id", referencedColumnName="id")
     */
    private $warranty;
    
    public function __construct() {
        
        $this->filters  = new \Doctrine\Common\Collections\ArrayCollection();
        $this->bundles  = new \Doctrine\Common\Collections\ArrayCollection(); 
        $this->stickers = new \Doctrine\Common\Collections\ArrayCollection(); 
        $this->videos   = new \Doctrine\Common\Collections\ArrayCollection();
        $this->text_filter = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function setProduct( $value ) { $this->product = $value; }
    public function getProduct() { return $this->product; }
    public function setCategory( $value ) { $this->category = $value; }
    public function getCategory() { return $this->category; }
    public function setSubcategory( $value ) { $this->subcategory = $value; }
    public function getSubcategory() { return $this->subcategory; }
    public function setWarranty( $value ) { $this->warranty = $value; }
    public function getWarranty() { return $this->warranty; }
    public function setName( $value ) { $this->name = $value; }
    public function getName() { return $this->name; }
    public function setOther( $value ) { $this->description = $value; }
    public function getOther() { return $this->description; }
    public function setPriceList( $value ) { $this->price_list = $value; }
    public function getPriceList() { return $this->price_list; }
    public function setPromotion( $value ) { $this->promotion = $value; }
    public function getPromotion() { return $this->promotion; }
    public function setSale( $value ) { $this->sale = $value; }
    public function getSale() { return $this->sale; }
    public function setOutlet( $value ) { $this->outlet= $value; }
    public function getOutlet() { return $this->outlet; }
    public function setHighlightCategory( $value ) { $this->highlight_category = $value; }
    public function getHighlightCategory() { return $this->highlight_category; }  
    public function setHighlightSubcategory( $value ) { $this->highlight_subcategory = $value; }
    public function getHighlightSubcategory() { return $this->highlight_subcategory; }   
    public function setStatisticVisits( $value ) { $this->statistic_visits = $value; }
    public function getStatisticVisits() { return $this->statistic_visits; }
    public function setStatisticVotes( $value ) { $this->statistic_votes = $value; }
    public function getStatisticVotes() { return $this->statistic_votes; }
    public function setStatisticRating( $value ) { $this->statistic_rating = $value; }
    public function getStatisticRating() { return $this->statistic_rating; }
    public function setStatisticSold( $value ) { $this->statistic_sold = $value; }
    public function getStatisticSold() { return $this->statistic_sold; }
    public function setReleaseDate () { $this->release_date = new \DateTime("now"); }
    public function getReleaseDate() { return $this->release_date; } 
    public function getFormatedReleaseDate() { return $this->release_date->format('d.m.Y H:i'); }
    public function setStatus( $value ) { $this->status = $value; }
    public function getStatus() { return $this->status; }  
    
    public function setVideo( Video $value ) { $this->videos[] = $value; }
    public function getVideos() { return $this->videos; }
    public function setProductFilter( Filter $value ) { $this->filters[] = $value; }
    public function getProductFilters() { return $this->filters; } 
    public function setProductSticker( Sticker $value ) { $this->stickers[] = $value; }
    public function getProductStickers() { return $this->stickers; }
    public function setImage( ProductImage $value ) { $this->images[] = $value; }
    public function getImages() { return $this->images; }
    public function setBundle( Bundle $value ) { $this->bundles[] = $value; }
    public function getBundles() { return $this->bundles; }
    public function setProductTextFilter( TextFilter $value ) { $this->text_filter[] = $value; }
    public function getProductTextFilters() { return $this->text_filter; }
    
    public function getBundle( $id ) {
        foreach( $this->bundles as $bundle ) {
            if( $bundle->getID() == $id ) return $bundle;
        }
    }
}
 
 /* End of file Product.php */
 /* Location: ./system/applications/_frontend/models/Entities/Product.php */