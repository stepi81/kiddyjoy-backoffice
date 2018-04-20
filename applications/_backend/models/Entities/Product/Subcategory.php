<?php

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */

 namespace models\Entities\Product;

 use models\Entities\Product\Review\ReviewSpecification;

 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 /**
  * @Entity(repositoryClass="models\ProductSubcategoryRepository")
  * @Table(name="ecom_product_subcategories")
  */
 class Subcategory {

    /**
     * @Id
     * @Column(type="integer", length=10, nullable=false)
     * @GeneratedValue(strategy="AUTO")
     */
	private $id;

	/** @Column(type="string", length=100, nullable=false) */
	private $name;
	/** @Column(type="integer", length=4, nullable=false) */
	private $position;
	/** @Column(type="text", nullable=true) */
    private $description;
	/** @Column(type="smallint", length=2, nullable=false) */
	private $status;
     /** @Column(type="string", length=240, nullable=false) */
     private $seo_title;
     /** @Column(type="string", length=240, nullable=false) */
     private $seo_keywords;
     /** @Column(type="string", nullable=false) */
     private $seo_description;

	/**
     * @ManyToOne(targetEntity="Category")
     * @JoinColumn(name="category_id", referencedColumnName="id")
     */
	private $category;

	/**
     * @OneToMany(targetEntity="models\Entities\Product\Specification", mappedBy="subcategory", cascade={"remove"})
	 * @OrderBy({"position" = "ASC"})
     */
	private $specifications;

    /**
     * @OneToMany(targetEntity="models\Entities\Product", mappedBy="subcategory", cascade={"remove"})
     */
    private $products;

    /**
     * @OneToMany(targetEntity="Subcategory", mappedBy="parent")
     **/
    private $children;

    /**
     * @ManyToOne(targetEntity="Subcategory", inversedBy="children")
     * @JoinColumn(name="parent_id", referencedColumnName="id")
     **/
    private $parent;

	/** @Column(type="string", length=120, nullable=true) */
	private $icon;

	/** @Column(type="smallint", length=2, nullable=false) */
    private $highlight;

	/**
     * @ManyToMany(targetEntity="models\Entities\Product")
     * @JoinTable(name="ecom_vendor_subcategories_highlights",
     *      joinColumns={@JoinColumn(name="subcategory_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="product_id", referencedColumnName="id", unique=true)}
     *      )
     */
	private $highlights;

	/**
     * @OneToMany(targetEntity="models\Entities\Advertising\Ad", mappedBy="subcategory")
     * @OrderBy({"position" = "ASC"})
     */
	private $ads;

	/**
     * @OneToMany(targetEntity="models\Entities\Product\Review\ReviewSpecification", mappedBy="subcategory")
     * @OrderBy({"position" = "ASC"})
     **/
	private $review_specifications;

	/**
     * @ManyToMany(targetEntity="models\Entities\Product\Brand")
     * @JoinTable(name="ecom_product_subcategories_brands",
     *      joinColumns={@JoinColumn(name="subcategory_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="brand_id", referencedColumnName="id", unique=true)}
     *      )
     */
	private $brands;

	/**
     * @OneToMany(targetEntity="models\Entities\Product\Size", mappedBy="subcategory")
     * @OrderBy({"position" = "ASC"})
     */
	private $sizes;

	public function __construct() {

		$this->status = 0;

        $this->specifications = new \Doctrine\Common\Collections\ArrayCollection();
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
		$this->highlights = new \Doctrine\Common\Collections\ArrayCollection();
		$this->review_specifications = new \Doctrine\Common\Collections\ArrayCollection();
	}

    public function getID() { return $this->id; }
	public function setName( $value ) { $this->name = $value; }
    public function getName() { return $this->name; }
	public function getURL() { return WEB_APP_URL.url_title($this->name, 'underscore', TRUE); }
	public function setPosition( $value ) { $this->position = $value; }
    public function getPosition() { return $this->position; }
    public function setDescription( $value ) { $this->description = $value; }
    public function getDescription() { return nl2br($this->description); }
    public function setStatus( $value ) { $this->status = $value; }
	public function getStatus() { return $this->status; }
    public function setCategory( $value ) { $this->category = $value; }
    public function getCategory() { return $this->category; }
	public function getSpecifications() { return $this->specifications; }
    public function setParent( $value ) { $this->parent = $value; }
    public function getParent() { return $this->parent; }
    public function getChildren() { return $this->children; }
	public function setImage( $value ) { $this->icon = $value; }
	public function getImage() { return $this->icon; }
	public function getImageURL() { return $this->icon ? assets_url('img/icons/subcategories/'.$this->icon) : assets_url('img/icons/subcategories/kiddyjoy_subcategories.png'); }
	public function setHighlight( $value ) { $this->highlight = $value; }
	public function getHighlight() { return $this->highlight; }
	public function setVendorHighlight( $value ) { $this->highlights[] = $value; }
	public function getVendorHighlights() { return $this->highlights; }
	public function setBrand( $value ) { $this->brands[] = $value; }
	public function getBrands() { return $this->brands; }
     public function setSeoTitle( $value ) { $this->seo_title = $value; }
     public function getSeoTitle() { return $this->seo_title; }
     public function setSeoKeywords( $value ) { $this->seo_keywords = $value; }
     public function getSeoKeywords() { return $this->seo_keywords; }
     public function setSeoDescription( $value ) { $this->seo_description = $value; }
     public function getSeoDescription() { return $this->seo_description; }

 	public function getReviewSpecifications() {

		if( !$this->review_specifications->count() ) {
			$specs = unserialize(REVIEW_DEFAULT_SPECIFICATIONS);
			foreach( $specs as $spec ) {
				$review_spec = new ReviewSpecification();
				$review_spec->setID($spec['id']);
				$review_spec->setName($spec['name']);
				$this->review_specifications->add($review_spec);
			}
		}
		return $this->review_specifications;
	}
 }

 /* End of file Subcategory.php */
 /* Location: ./system/applications/_frontend/models/Entities/Product/Subcategory.php */