<?php

/**
 * ...
 * @author Marko Stepanovic [ Codeion ]
 */

 namespace models\Entities\Benchmark;
 use models\Entities\Benchmark;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity
  * @Table(name="ecom_benchmark_images")
  */
 class Image {
     
    /**
     * @Id
     * @Column(type="integer", nullable=false)
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /** @Column(type="string", length=60, nullable=false) */
    private $name;
    
    /**
     * @ManyToOne(targetEntity="models\Entities\Benchmark", inversedBy="images")
     * @JoinColumn(name="benchmark_id", referencedColumnName="id")
     */
    private $benchmark;
    
    public function getID() { return $this->id; }
    public function setName( $value ) { $this->name = $value; }
    public function getName() { return $this->name; }
    public function setBenchmark( Benchmark $value ) { $this->benchmark = $value; }
    public function getBenchmark() { return $this->benchmark; }
    public function getURL() { return assets_url('img/benchmark/pages/'.$this->name); }
 }
 
 /* End of file Image.php */
 /* Location: ./system/applications/_backend/models/Entities/Benchmark/Image.php */