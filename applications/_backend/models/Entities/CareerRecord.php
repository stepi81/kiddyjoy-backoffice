<?php

/**
 * ...
 * @author Aleksandar Milas [ Codeion ]
 */

 namespace models\Entities;
 
 //use models\Entities\CarrerJob;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity(repositoryClass="models\CareersRepository")
  * @Table(name="ecom_careers")
  */
 class CareerRecord {
 	
	/**
	 * @Id
	 * @Column(type="integer", length=10, nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/** @Column(type="string", length=120, nullable=false) */
	private $name;
	/** @Column(type="string", length=120, nullable=false) */
	private $email;
	/** @Column(type="string", length=120, nullable=false) */
	private $phone;
	/** @Column(type="string", length=120, nullable=false) */
	private $url;

	/** @Column(type="integer", length=10, nullable=false) */
	private $career_job_id;
    /** @Column(type="text", nullable=false) */
	private $message;

	/** @Column(type="string", length=120, nullable=false) */
    private $cv;
	/** @Column(type="datetime") */	
	private $registration_date;

	public function getID() { return $this->id; }
    public function setName( $value ) { $this->name = $value; }
    public function getName() { return $this->name; }
	
	public function setEmail( $value ) { $this->email = $value; }
    public function getEmail() { return $this->email; }
	public function setPhone( $value ) { $this->phone = $value; }
    public function getPhone() { return $this->phone; }
	public function setUrl( $value ) { $this->url = $value; }
    public function getUrl() { return $this->url; }
	public function setCareerJobID( $value ) { $this->career_job_id = $value; }
    public function getCareerJobID() { return $this->career_job_id; }
	public function setMessage( $value ) { $this->message = $value; }
    public function getMessage() { return $this->message; }
	public function setCv( $value ) { $this->cv = $value; }
    public function getCv() { return $this->cv; }
	public function setRegistrationDate( $value ) { $this->registration_date = $value; }
    public function getRegistrationDate() { return $this->registration_date; }
	public function getFormatedDate() { return $this->registration_date->format('d/m/Y'); }
	    
 }
 
 /* End of file CareerRecord.php */
 /* Location: ./system/applications/_backend/models/Entities/CareerRecord.php */