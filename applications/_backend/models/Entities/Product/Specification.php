<?php

/**
 * ...
 * @author Ivan Despic [ Codeion ]
 */

 namespace models\Entities\Product;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity(repositoryClass="models\ProductSpecificationRepository")
  * @Table(name="ecom_specifications")
  */
 class Specification {
 	
    /**
     * @Id
     * @Column(type="integer", nullable=false)
     * @GeneratedValue(strategy="AUTO")
     */
	private $id;
	
	/** @Column(type="string", length=60, nullable=false) */
	private $name;
	/** @Column(type="integer", length=4, nullable=false) */
	private $position; 
    /** @Column(type="integer", length=2, nullable=false) */
    private $position_info;
	/** @Column(type="integer", length=2, nullable=false) */
    private $position_klirit;
	/** @Column(type="smallint", length=2, nullable=false) */
    private $status;
	/** @Column(type="smallint", length=2, nullable=false) */
    private $visibility;
	/** @Column(type="smallint", length=2, nullable=false) */
    private $filter_visibility;
	/** @Column(type="smallint", length=2, nullable=false) */
    private $bundle_visibility;
	/** @Column(type="smallint", length=2, nullable=false) */
    private $techicon_visibility;	
    /** @Column(type="integer", length=2, nullable=false) */    
    private $type_id;
	
	/**
     * @OneToMany(targetEntity="models\Entities\Product\Filter", mappedBy="specification", cascade={"remove"})
	 * @OrderBy({"position" = "ASC"})
     */
	private $filters;
	
	/**
     * @ManyToOne(targetEntity="models\Entities\Product\Subcategory")
     * @JoinColumn(name="subcategory_id", referencedColumnName="id")
     **/
	private $subcategory;
    
    /**
     * @OneToMany(targetEntity="models\Entities\Product\TextFilter", mappedBy="specification", cascade={"remove"})
     */
    private $products_text_filter;
	
	public function __construct() {
		
        $this->filters = new \Doctrine\Common\Collections\ArrayCollection();
    }
	
    public function getID() { return $this->id; }
	public function setName( $value ) { $this->name = $value; }
    public function getName() { return $this->name; }
	public function setPosition( $value ) { $this->position = $value; }
    public function getPosition() { return $this->position; }
    public function setPositionInfo( $value ) { $this->position_info = $value; }
    public function getPositionInfo() { return $this->position_info; }
	public function setPositionKlirit( $value ) { $this->position_klirit = $value; }
	public function getPositionKlirit() { return $this->position_klirit; }
	public function setStatus( $value ) { $this->status = $value; }
    public function getStatus() { return $this->status; }
    
    public function setVisibility( $value ) { $this->visibility = $value; }
    public function getVisibility() { return $this->visibility; }
	public function setFilterVisibility( $value ) { $this->filter_visibility = $value; }
    public function getFilterVisibility() { return $this->filter_visibility; }
	public function setBundleVisibility( $value ) { $this->bundle_visibility = $value; }
	public function getBundleVisibility() { return $this->bundle_visibility; }
	public function setTechIconVisibility($value) { $this->techicon_visibility = $value; }
	public function getTechIconVisibility() { return $this->techicon_visibility; }
	
	public function getFilters() { return $this->filters; }
	public function setSubcategory( $value ) { $this->subcategory= $value; }
    public function getSubcategory() { return $this->subcategory; }
    public function setTypeID( $value ) { $this->type_id = $value; }
    public function getTypeID() { return $this->type_id; }
 }
 
 /* End of file Specification.php */
 /* Location: ./system/applications/_frontend/models/Entities/Product/Specification.php */