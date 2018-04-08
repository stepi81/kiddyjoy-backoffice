<?php

/**
 * ...
 * @author Ivan Despic [ Codeion ]
 */

 namespace models\Entities\Product;
 use models\Entities\Product\CategorySpecifications;
 use models\Entities\Product;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity(repositoryClass="models\ProductCategoryRepository")
  * @Table(name="ecom_product_categories")
  */
 class Category {
     
    /**
     * @Id
     * @Column(type="integer", length=10, nullable=false)
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /** @Column(type="string", length=40, nullable=false) */
    private $name;

    /** @Column(type="string", length=60, nullable=true) */
    private $position;
	/** @Column(type="string", length=120, nullable=true) */
	private $icon;
	
	/**
     * @OneToMany(targetEntity="models\Entities\Advertising\Ad", mappedBy="category")
     * @OrderBy({"position" = "ASC"})
     */
	private $ads;
	
	/**
     * @ManyToMany(targetEntity="models\Entities\Product")
     * @JoinTable(name="ecom_vendor_categories_highlights",
     *      joinColumns={@JoinColumn(name="category_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="product_id", referencedColumnName="id", unique=true)}
     *      )
     */
	private $highlights;
	
	/**
     * @ManyToMany(targetEntity="models\Entities\Product\Brand")
     * @JoinTable(name="ecom_product_categories_brands",
     *      joinColumns={@JoinColumn(name="category_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="brand_id", referencedColumnName="id", unique=true)}
     *      )
     */
	private $brands;
    
    public function __construct() {
        
        $this->subcategories = new \Doctrine\Common\Collections\ArrayCollection();
        $this->ads = new \Doctrine\Common\Collections\ArrayCollection();
        $this->highlights = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function getID() { return $this->id; }
    public function setName( $value ) { $this->name = $value; }
    public function getName() { return  $this->name; }
    public function setPosition( $value ) { $this->position = $value; }
    public function getPosition() { return  $this->position; }
	
	public function setImage( $value ) { $this->icon = $value; }
	public function getImage() { return $this->icon; }
	public function getImageURL() { return $this->icon ? assets_url('img/icons/categories/'.$this->icon) : null; }
	
	public function setVendorHighlight( $value ) { $this->highlights[] = $value; } 
	public function getVendorHighlights() { return $this->highlights; }
	public function setBrand( $value ) { $this->brands[] = $value; }
	public function getBrands() { return $this->brands; }
		
 }
 
 /* End of file Category.php */
 /* Location: ./system/applications/_backend/models/Entities/Product/Category.php */