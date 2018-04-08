<?php

/**
 * ...
 * @author Ivan Despic [ Codeion ]
 */

 namespace models\Entities\Product;
 use models\Entities\Product;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity(repositoryClass="models\ProductFilterRepository")  
  * @Table(name="ecom_product_specifications")
  */
 class TextFilter {
    
    /**
     * @Id
     * @Column(type="integer", length=10, nullable=false)
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;
  
    /**
     * @ManyToOne(targetEntity="models\Entities\Product\Specification")
     * @JoinColumn(name="specification_id", referencedColumnName="id")
     **/
    private $specification;
  
    /**
     * @ManyToOne(targetEntity="models\Entities\Product", inversedBy="text_filter")
     * @JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;
    
    /** @Column(type="text", nullable=false) */
    private $description;
    
    public function getID() { return $this->id; }
    public function setProduct( $value ) { $this->product = $value; }
    public function getProduct() { return $this->product; }
    public function setSpecification( $value ) { $this->specification = $value; }
    public function getSpecification() { return $this->specification; }
    public function setDescription( $value ) { $this->description = $value; }
    public function getDescription() { return $this->description; }    
 }
 
 /* End of file TextFilter.php */
 /* Location: ./system/applications/_backend/models/Entities/Product/TextFilter.php */