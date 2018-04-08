<?php

/**
 * ...
 * @author Marko Stepanovic [ Codeion ]
 */

 namespace models\Entities\Images;
 use models\Entities\InfoDesk;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity
  * @Table(name="ecom_page_images")
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
     * @ManyToOne(targetEntity="models\Entities\InfoDesk", inversedBy="images")
     * @JoinColumn(name="page_id", referencedColumnName="id")
     */
    private $page;
    
    public function getID() { return $this->id; }
    public function setName( $value ) { $this->name = $value; }
    public function getName() { return $this->name; }
    public function setPage( InfoDesk $value ) { $this->page = $value; }
    public function getPage() { return $this->page; }
    public function getURL() { return assets_url('img/info_desk/'.$this->name); }
 }
 
 /* End of file PageImage.php */
 /* Location: ./system/applications/_backend/models/Entities/Images/PageImage.php */