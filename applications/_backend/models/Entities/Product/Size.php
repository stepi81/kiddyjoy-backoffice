<?php

/**
 * ...
 * @author Marko Stepanovic [ Codeion ]
 */

 namespace models\Entities\Product;
 
 use models\Entities\Product\Subcategory; 
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity(repositoryClass="models\SizeRepository") 
  * @Table(name="ecom_sizes")
  */
 class Size {
 	
	/**
	 * @Id
	 * @Column(type="integer", length=4, nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/** @Column(type="string", length=250, nullable=false) */
	private $name;
	/** @Column(type="integer", length=4, nullable=false) */
	private $position;
	/** @Column(type="smallint", length=2, nullable=false) */
	private $status;
	
	/**
     * @ManyToOne(targetEntity="models\Entities\Product\Subcategory", inversedBy="sizes")
     * @JoinColumn(name="subcategory_id", referencedColumnName="id")
     */
    private $subcategory;
	
	/**
     * @OneToMany(targetEntity="models\Entities\Product\ProductSize", mappedBy="size", cascade={"remove"})
     * @OrderBy({"position" = "ASC"})
     */
    private $product_sizes;
	
	public function __construct() {
        
		$this->product_sizes	= new \Doctrine\Common\Collections\ArrayCollection();
    }
	
    public function getID() { return $this->id; }
	public function setName( $value ) { $this->name = $value; }
    public function getName() { return $this->name; }
	public function setPosition( $value ) { $this->position = $value; }
    public function getPosition() { return $this->position; }
	public function setStatus( $value ) { $this->status = $value; }
    public function getStatus() { return $this->status; }
	
	public function getProductSizes() { return $this->product_sizes; }
	public function setSubcategory( Subcategory $value ) { $this->subcategory = $value; }   
	public function getSubcategory() { return $this->subcategory; }
    
 }
 
 /* End of file Size.php */
 /* Location: ./system/applications/_backend/models/Entities/Product/Size.php */