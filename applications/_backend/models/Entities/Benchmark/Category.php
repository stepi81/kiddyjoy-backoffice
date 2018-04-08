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
  * @Table(name="ecom_benchmark_categories")
  */
 class Category {
     
    /**
     * @Id
     * @Column(type="integer", length=10, nullable=false)
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /** @Column(type="string", length=40, nullable=false) */
    private $name;

    public function getID() { return $this->id; }
    public function setName( $value ) { $this->name = $value; }
    public function getName() { return  $this->name; }
 }
 
 /* End of file Category.php */
 /* Location: ./system/applications/_backend/models/Entities/Benchmark/Category.php */