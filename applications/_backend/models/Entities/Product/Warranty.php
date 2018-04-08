<?php

/**
 * ...
 * @author Ivan Despic [ Codeion ]
 */

 namespace models\Entities\Product;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity(repositoryClass="models\WarrantyRepository")
  * @Table(name="ecom_product_warranties")
  */
 class Warranty {
    
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
	
    /**
     * @OneToMany(targetEntity="models\Entities\Product", mappedBy="warranty")
     * @JoinColumn(name="id", referencedColumnName="warranty_id")
     */
    private $products;
    
    public function getID() { return $this->id; }
    public function setName ( $value ) { $this->name = $value; }
    public function getName() { return $this->name; }
	public function setPosition( $value ) { $this->position = $value; }
    public function getPosition() { return $this->position; }
 }
 
 /* End of file Warranty.php */
 /* Location: ./system/applications/_backend/models/Entities/Product/Warranty.php */