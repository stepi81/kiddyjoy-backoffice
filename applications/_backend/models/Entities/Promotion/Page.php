<?php

/**
 * ...
 * @author Marko Stepanovic [ Codeion ]
 */

 namespace models\Entities\Promotion;
 
 use models\Entities\Product;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity(repositoryClass="models\PromotionRepository")
  * @Table(name="ecom_promotions")
  */
 class Page {
     
    /**
     * @Id
     * @Column(type="integer", nullable=false)
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;

    /** @Column(type="string", length=120, nullable=false) */
    private $title;
    /** @Column(type="text", nullable=false) */
    private $content;
    /** @Column(type="smallint", length=2, nullable=false) */
    private $status;
    
    /**
     * @OneToMany(targetEntity="PageImage", mappedBy="page", cascade={"remove"})
     * @JoinColumn(name="id", referencedColumnName="promotion_id")
     */
    private $images;
    
     /**
     * @ManyToMany(targetEntity="models\Entities\Product")
     * @JoinTable(name="ecom_promotions_products",
     *      joinColumns={@JoinColumn(name="promotion_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="product_id", referencedColumnName="id")}
     *      )
     */
    private $products;
    
    /**
     * @ManyToOne(targetEntity="models\Entities\Vendor")
     * @JoinColumn(name="vendor_id", referencedColumnName="id")
     **/
    private $vendor;
    
    public function __construct() {
        
        $this->content = '';
        $this->status = 0;
        
        $this->products = new \Doctrine\Common\Collections\ArrayCollection();
        $this->images = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function getID() { return $this->id; }
    public function setVendor( $value ) { $this->vendor = $value; } 
    public function getVendor() { return $this->vendor; }
    public function setTitle( $value ) { $this->title = $value; }
    public function getTitle() { return $this->title; }
    public function setContent( $value ) { $this->content = $value; }
    public function getContent() { return $this->content; }
    public function setStatus( $value ) { $this->status = $value; }
    public function getStatus() { return $this->status; }
    public function setImage( PageImage $value ) { $this->images[] = $value; }
    public function getImages() { return $this->images; }
    public function setProduct( Product $value ) { $this->products[] = $value; }
    public function getProducts() { return $this->products; }
   // public function getURL() { return "http://95.211.222.98/" . (url_title($this->title, 'underscore', TRUE)); }
 }
 
 /* End of file Page.php */
 /* Location: ./system/applications/_backend/models/entities/Promotion/Page.php */