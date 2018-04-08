<?php

/**
 * ...
 * @author Marko Stepanovic [ Codeion ]
 */

 namespace models\Entities\Product;
 
 use models\Entities\Product;
 use models\Entities\Product\Color;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity(repositoryClass="models\ColorRepository") 
  * @Table(name="ecom_product_colors")
  */
 class ProductColor {
 	
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
     * @ManyToOne(targetEntity="models\Entities\Product\Color", inversedBy="product_colors")
     * @JoinColumn(name="color_id", referencedColumnName="id")
     */
	private $color;
	
	/** @Column(type="integer", length=4, nullable=false) */
	private $position;
	/** @Column(type="smallint", length=2, nullable=false) */
	private $status;
	
    public function getID() { return $this->id; }
	public function setProduct( $value ) { $this->product = $value; }
    public function getProduct() { return $this->product; }
	public function setColor( $value ) { $this->color = $value; }
    public function getColor() { return $this->color; }
	public function setPosition( $value ) { $this->position = $value; }
    public function getPosition() { return $this->position; }
	public function setStatus( $value ) { $this->status = $value; }
    public function getStatus() { return $this->status; }
    
 }
 
 /* End of file ProductColor.php */
 /* Location: ./system/applications/_backend/models/Entities/Product/ProductColor.php */