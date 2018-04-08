<?php

/**
 * ...
 * @author Marko Stepanovic [ Codeion ]
 */

 namespace models\Entities\ShoppingGuide;
 use models\Entities\ShoppingGuide\GuideImage;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity(repositoryClass="models\ShoppingGuideRepository")
  * @Table(name="ecom_shopping_guides")
  */
 class Guide {
     
    /**
     * @Id
     * @Column(type="integer", nullable=false)
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;

    /** @Column(type="string", length=120, nullable=false) */
    private $title;
    /** @Column(type="text", nullable=false) */
    private $description;
    /** @Column(type="smallint", length=2, nullable=false) */
    private $status;
    
    /**
     * @OneToMany(targetEntity="GuideImage", mappedBy="guide", cascade={"remove"})
     */
    private $images;
    
    public function __construct() {
        
        $this->description = '';
        $this->status = 0;
        
        $this->images = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function getID() { return $this->id; }
    public function setTitle( $value ) { $this->title = $value; }
    public function getTitle() { return $this->title; }
    public function setDescription( $value ) { $this->description = $value; }
    public function getDescription() { return $this->description; }
    public function setStatus( $value ) { $this->status = $value; }
    public function getStatus() { return $this->status; }
    public function setImage( GuideImage $value ) { $this->images[] = $value; }
    public function getImages() { return $this->images; }
 }
 
 /* End of file Guide.php */
 /* Location: ./system/applications/_backend/models/entities/Guide.php */