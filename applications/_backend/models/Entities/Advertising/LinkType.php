<?php

/**
 * ...
 * @author Ivan Despic [ Codeion ]
 */
 
 namespace models\Entities\Advertising;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity(repositoryClass="models\AdvertisingRepository")
  * @Table(name="ecom_ad_link_types")
  */
 class LinkType {
     
    /**
     * @Id
     * @Column(type="integer", nullable=false)
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;

    /** @Column(type="string", length=120, nullable=false) */
    private $name;

    
    public function getID() { return $this->id; }
    public function setName( $value ) { $this->name = $value; }
    public function getName() { return $this->name; }
 }
 
 /* End of file Location.php */
 /* Location: ./system/applications/_backend/models/Entities/Advertising/LinkType.php */