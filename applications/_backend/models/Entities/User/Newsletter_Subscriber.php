<?php

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */

 namespace models\Entities\User;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity(repositoryClass="models\UserRepository")
  * @Table(name="ecom_users_newsletter")
  */
 class Newsletter_Subscriber {
 	
	
    /** 
     * @Id
     * @Column(type="string", length=60, nullable=false) */
    private $email;
	/** @Column(type="string", length=60, nullable=false) */
	private $first_name;
	/** @Column(type="string", length=60, nullable=false) */
	private $last_name;

	
	public function setFirstName( $value ) { $this->first_name = $value; }
	public function getFirstName() { return $this->first_name; }
	public function setLastName( $value ) { $this->last_name = $value; }
	public function getLastName() { return $this->last_name; }
	public function setEmail( $value ) { $this->email = $value; }
    public function getEmail() { return $this->email; }
 }
 
 /* End of file Newsletter_Subscriber.php */
 /* Location: ./system/applications/_backend/models/Entities/User/Newsletter_Subscriber.php */