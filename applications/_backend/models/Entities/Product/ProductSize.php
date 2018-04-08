<?php

/**
 * ...
 * @author Marko Stepanovic [ Codeion ]
 */

 namespace models\Entities\Product;
 
 use models\Entities\Product;
 use models\Entities\Product\Size;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity(repositoryClass="models\SizeRepository") 
  * @Table(name="ecom_product_sizes")
  */
 class ProductSize {
 	
	/**
	 * @Id
	 * @Column(type="integer", length=4, nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/**
     * @ManyToOne(targetEntity="models\Entities\Product", inversedBy="product_colors")
     * @JoinColumn(name="product_id", referencedColumnName="id")
     */
	private $product;
	
	/**
     * @ManyToOne(targetEntity="models\Entities\Product\Size", inversedBy="product_colors")
     * @JoinColumn(name="size_id", referencedColumnName="id")
     */
	private $size;
	
	/** @Column(type="integer", length=4, nullable=false) */
	private $position;
	/** @Column(type="smallint", length=2, nullable=false) */
	private $status;
	
    public function getID() { return $this->id; }
	public function setProduct( $value ) { $this->product = $value; }
    public function getProduct() { return $this->product; }
	public function setSize( $value ) { $this->size = $value; }
    public function getSize() { return $this->size; }
	public function setPosition( $value ) { $this->position = $value; }
    public function getPosition() { return $this->position; }
	public function setStatus( $value ) { $this->status = $value; }
    public function getStatus() { return $this->status; }
    
 }
 
 /* End of file ProductSize.php */
 /* Location: ./system/applications/_backend/models/Entities/Product/ProductSize.php */