<?php

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */

 namespace models\Entities;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /** @MappedSuperclass */
 class User {

	/** @Column(type="string", length=60, nullable=false) */
	protected $email;
	/** @Column(type="string", length=60, nullable=false) */
	protected $password;
	/** @Column(type="datetime") */
	protected $registration_date;
	
    public function setEmail( $value ) { $this->email = $value; }
    public function getEmail() { return $this->email; }
    public function setPassword( $value ) { $this->password = md5("#*KiDdYjOy*#".$value); }
    public function getPassword() { return $this->password; }
    public function setRegistrationDate() { $this->registration_date = new \DateTime("now"); }
    public function getRegistrationDate() { return $this->registration_date; }
	public function getFormatedRegistrationDate() { return $this->registration_date->format('d/m/Y'); }
    
 }
 
 /* End of file User.php */
 /* Location: ./system/applications/_backend/models/entities/User.php */