<?php

/**
 * ...
 * @author Aleksandar Milas [ Codeion ]
 */

 namespace models\Entities;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity(repositoryClass="models\CareerJobsRepository")
  * @Table(name="ecom_career_jobs")
  */
 class CareerJob {
 	
	/**
	 * @Id
	 * @Column(type="integer", length=10, nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/** @Column(type="string", length=120, nullable=false) */
	private $name;

	public function getID() { return $this->id; }
    public function setName( $value ) { $this->name = $value; }
    public function getName() { return $this->name; }
	    
 }
 
 /* End of file CareerJob.php */
 /* Location: ./system/applications/_backend/models/Entities/CareerJob.php */