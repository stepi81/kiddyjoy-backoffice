<?php

/**
 * ...
 * @author Marko Stepanovic [ Codeion ]
 */

 namespace models\Entities\Promotion;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity
  * @Table(name="ecom_promotion_images")
  */
 class PageImage {
     
    /**
     * @Id
     * @Column(type="integer", nullable=false)
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /** @Column(type="string", length=60, nullable=false) */
    private $name;
    
    /**
     * @ManyToOne(targetEntity="Page", inversedBy="images")
     * @JoinColumn(name="promotion_id", referencedColumnName="id")
     */
    private $page;
    
    public function getID() { return $this->id; }
    public function setName( $value ) { $this->name = $value; }
    public function getName() { return $this->name; }
    public function setPage( Info $value ) { $this->page = $value; }
    public function getPage() { return $this->page; }
    public function getURL() { return assets_url('img/promotions/pages/'.$this->name); }
 }
 
 /* End of file PageImage.php */
 /* Location: ./system/applications/_backend/models/Entities/Promotion/PageImage.php */