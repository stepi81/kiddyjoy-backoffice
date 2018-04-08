<?php

/**
 * ...
 * @author Ivan Despic [ Codeion ]
 */

 namespace models\Entities\Product;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity(repositoryClass="models\ProductFilterRepository")
  * @Table(name="ecom_specification_filters")
  */
 class Filter {
    
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
	/** @Column(type="smallint", length=2, nullable=false) */
    private $status;
    
    /**
     * @ManyToMany(targetEntity="models\Entities\Product")
     * @JoinTable(name="ecom_products_filters",
     *      joinColumns={@JoinColumn(name="filter_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="product_id", referencedColumnName="id")}
     *      )
     */
    private $products;
    
    /**
     * @ManyToOne(targetEntity="models\Entities\Technology")
     * @JoinColumn(name="technology_id", referencedColumnName="id")
     */
    private $technology;
    
    /**
     * @ManyToOne(targetEntity="models\Entities\Product\Specification")
     * @JoinColumn(name="specification_id", referencedColumnName="id")
     **/
    private $specification;
    
    public function __construct() {
        
		$this->status = 0;
        $this->products= new \Doctrine\Common\Collections\ArrayCollection();
    }
    public function getID() { return $this->id; }
    public function setName( $value ) { $this->name = $value; }
    public function getName() { return $this->name; }
    public function setTechnology( $value ) { $this->technology = $value; }
    public function getTechnology() { return $this->technology; }
    public function setPosition( $value ) { $this->position = $value; }
    public function getPosition() { return $this->position; }
    public function setSpecification( $value ) { $this->specification = $value; }
    public function getSpecification() { return $this->specification; }
    //public function setProductFilter( Filter $value ) { $this->filters[] = $value; }
    public function getFilterProducts() { return $this->products; }
    public function setStatus( $value ) { $this->status = $value; }
    public function getStatus() { return $this->status; }

 }
 
 /* End of file Filter.php */
 /* Location: ./system/applications/_frontend/models/Entities/Product/Filter.php */