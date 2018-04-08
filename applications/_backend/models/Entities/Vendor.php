<?php

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */

 namespace models\Entities;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity
  * @Table(name="ecom_vendors")
  */
 class Vendor {
     
    /**
     * @Id
     * @Column(type="string", length=16, nullable=false)
     */
    private $id;
    
     /**
     * @OneToMany(targetEntity="models\Entities\Advertising\Ad", mappedBy="vendor_id")
     */
    private $ads;
    
	public function setID( $value) { $this->id = $value; }
    public function getID() { return $this->id; }
 }
 
 /* End of file Vendor.php */
 /* Location: ./system/applications/_frontend/models/Entities/Vendor.php */