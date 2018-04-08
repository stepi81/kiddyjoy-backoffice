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
 class NewsletterUser {
 	
	
    /** 
     * @Id
     * @Column(type="string", length=60, nullable=false) */
    private $email;
	
	/** @Column(type="smallint", length=2, nullable=true) */
	private $status;
	/** @Column(type="string", length=32, nullable=true) */
	private $request_token;

	public function setEmail( $value ) { $this->email = $value; }
    public function getEmail() { return $this->email; }
	public function setStatus( $value ) { $this->status = $value; }
    public function getStatus() { return $this->status; }
	public function setRequestToken( $value ) { $this->request_token = $value; }
    public function getRequestToken() { return $this->request_token; }
 }
 
 /* End of file NewsletterUser.php */
 /* Location: ./system/applications/_backend/models/Entities/User/NewsletterUser.php */