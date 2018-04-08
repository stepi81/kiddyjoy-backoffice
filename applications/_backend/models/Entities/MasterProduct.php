<?php

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */

 namespace models\Entities;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity
  * @Table(name="db7380_kiddyjoy_replica.products")
  */
 class MasterProduct {
 	
	/**
	 * @Id
	 * @Column(type="integer", nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/** @Column(type="string", length=60, nullable=false) */
    private $name;
	
	/**
     * @OneToOne(targetEntity="Product", mappedBy="master")
     **/
	private $data;
	
    public function getID() { return $this->id; }
	public function setName( $value ) { $this->name = $value; }
	public function getName() { return $this->name; }
 }
 
 /* End of file MasterProduct.php */
 /* Location: ./system/applications/_backend/models/Entities/MasterProduct.php */