<?php

/**
 * ...
 * @author Aleksandar Milas [ Codeion ]
 */

 namespace models\Entities;
 
 //use models\Entities\CarrerJob;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity(repositoryClass="models\CareerAdsRepository")
  * @Table(name="ecom_career_ads")
  */
 class CareerAd {
 	
	/**
	 * @Id
	 * @Column(type="integer", length=10, nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/** @Column(type="integer", length=10, nullable=false) */
	private $career_job_id;

    /** @Column(type="text", nullable=false) */
	private $text;

	/** @Column(type="smallint", length=2, nullable=false) */
	private $status;

	public function getID() { return $this->id; }
	public function setCareerJobID( $value ) { $this->career_job_id = $value; }
    public function getCareerJobID() { return $this->career_job_id; }
	public function setText( $value ) { $this->text = $value; }
    public function getText() { return $this->text; }
	public function setStatus( $value ) { $this->status = $value; }
    public function getStatus() { return $this->status; }

 }
 
 /* End of file CareerAd.php */
 /* Location: ./system/applications/_backend/models/Entities/CareerAd.php */