<?php

/**
 * ...
 * @author Andrej The Savic [ Codeion SA ]
 */

 namespace models\Entities\User\Customer;

 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity
  * @Table(name="ecom_users_instant")
  */
 class Instant {
     
    /**
     * @Id
     * @Column(type="integer", length=10, nullable=false)
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /** @Column(type="string", length=60, nullable=false) */
    private $first_name;
    /** @Column(type="string", length=60, nullable=false) */
    private $last_name;
    /** @Column(type="string", length=240, nullable=false) */
    private $address;
    /** @Column(type="string", length=60, nullable=false) */
    private $phone;
    /** @Column(type="string", length=60, nullable=false) */
    private $email;
    
    /**
     * @OneToOne(targetEntity="models\Entities\PostalCode")
     * @JoinColumn(name="postal_code", referencedColumnName="postal_code")
     */
    private $postal_code;
    
    public function getID() { return $this->id; }
    public function setFirstName( $value ) { $this->first_name = $value; }
    public function getFirstName() { return $this->first_name; }
    public function setLastName( $value ) { $this->last_name = $value; }
    public function getLastName() { return $this->last_name; }
    public function setAddress( $value ) { $this->address = $value; }
    public function getAddress() { return $this->address; }
    public function setPhone( $value ) { $this->phone = $value; }
    public function getPhone() { return $this->phone; }
    public function setEmail( $value ) { $this->email = $value; }
    public function getEmail() { return $this->email; }
    public function getPostalCode() { return $this->postal_code; } 
 }
 
 /* End of file Instant.php */
 /* Location: ./system/application/models/Entities/User/Customer/Instant.php */