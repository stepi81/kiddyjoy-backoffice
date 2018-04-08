<?php

/**
 * ...
 * @author Marko Stepanovic [ Codeion ]
 */

 namespace models\Entities;
 
 use models\Entities\Product;
 use models\Entities\Benchmark\Category;
 use models\Entities\Benchmark\Image;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity(repositoryClass="models\BenchmarkRepository")
  * @Table(name="ecom_benchmarks")
  */
 class Benchmark {
     
    /**
     * @Id
     * @Column(type="integer", length=10, nullable=false)
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;
    /** @Column(type="string") */
    private $title;
    /** @Column(type="string") */
    private $short_info;
    /** @Column(type="string") */
    private $description;
    /** @Column(type="datetime") */
    private $benchmark_date;
    /** @Column(type="string", length=120, nullable=false) */
    private $thumb;
    /** @Column(type="smallint", length=2, nullable=false) */
    private $status;
    
    /**
     * @ManyToOne(targetEntity="models\Entities\Benchmark\Category")
     * @JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;
    
    /**
     * @ManyToOne(targetEntity="models\Entities\Product")
     * @JoinColumn(name="product_id", referencedColumnName="id")
     **/
    private $product;
    
    /**
     * @OneToMany(targetEntity="models\Entities\Benchmark\Image", mappedBy="benchmark", cascade={"remove"})
     */
    private $images;
    
    public function __construct() {

        $this->description = ''; 
        $this->status = 0;
        
        $this->images = new \Doctrine\Common\Collections\ArrayCollection();    
    }
    
    public function setID( $value ) { $this->id = $value; }
    public function getID() { return $this->id; }
    public function setTitle( $value ) { $this->title = $value; }
    public function getTitle() { return $this->title; }
    public function setShortInfo( $value ) { $this->short_info = $value; }
    public function getShortInfo() { return $this->short_info; }
    public function setDescription( $value ) { $this->description = $value; }
    public function getDescription() { return $this->description; }
    public function setCategory( $value ) { $this->category = $value; }
    public function getCategory() { return $this->category; }
    public function setProduct( $value ) { $this->product = $value; }
    public function getProduct() { return $this->product; }    
    public function setDate ( $value ) { $this->benchmark_date = new \DateTime("now"); }
    public function getDate() { return $this->benchmark_date; }
    public function getFormatedDate() { return $this->benchmark_date->format('d.m.Y'); }
    public function setThumb( $value ) { $this->thumb = $value; }
    public function getThumb() { return $this->thumb; }
    public function getThumbURL() { return assets_url('img/benchmark/'.$this->thumb); }
    public function setStatus( $value ) { $this->status = $value; }
    public function getStatus() { return $this->status; }
    
    public function setImage( Image $value ) { $this->images[] = $value; }
    public function getImages() { return $this->images; }

 }
 
 /* End of file Benchmark.php */
 /* Location: ./system/applications/_frontend/models/Entities/Benchmark.php */