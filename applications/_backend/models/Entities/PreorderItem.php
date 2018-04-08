<?php

/**
 * ...
 * @author Aleksandar Milas [ Codeion ]
 */

 namespace models\Entities;
 
 use models\Entities\Product;
 use models\Entities\Preorder;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity
  * @Table(name="ecom_preorder_items")
  */
 class PreorderItem {
     
    /**
     * @Id
     * @Column(type="integer", length=10, nullable=false)
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /** @Column(type="integer", length=10, nullable=true) */
    private $product_id;

    /**
     * @OneToOne(targetEntity="models\Entities\Product")
     * @JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;//TODO ManyToOne?
    
    /**
     * @ManyToOne(targetEntity="models\Entities\Preorder", inversedBy="items")
     * @JoinColumn(name="preorder_id", referencedColumnName="id")
     */
    private $preorder;
    
	/** @Column(type="string", length=240, nullable=false) */        
    private $name;
	
    public function getID() { return $this->id; }
    public function setProductID( $value ) { $this->product_id = $value; }
    public function getProductID() { return $this->product_id; } 
    public function setProduct( Product $value ) { $this->product = $value; }
    public function getProduct() { return $this->product; }
    public function setPreorder( Preorder $value ) { $this->preorder = $value; }
    public function getPreorder() { return $this->preorder; }
	public function setName( $value ) { $this->name = $value; }
    public function getName() { return $this->name; }
	public function getProductName() { return $this->name ? $this->name : ($this->product ? $this->product->getName() : '-'); }
	
 }
 
 /* End of file PreorderItem.php */
 /* Location: ./system/applications/_backend/models/Entities/PreorderItem.php */