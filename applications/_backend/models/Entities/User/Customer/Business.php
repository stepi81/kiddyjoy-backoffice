<?php

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */

 namespace models\Entities\User\Customer;
 
 use models\Entities\User\Customer;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity(repositoryClass="models\UserRepository")
  */
 class Business extends Customer {
 	
     /** @Column(type="string", length=20, nullable=false) */
    private $master_id;
	/** @Column(type="string", length=60, nullable=false) */
	private $company_name;
	/** @Column(type="integer", length=10, nullable=true) */
	private $tax_number;
    /** @Column(type="string"), length=20, nullable=true */
    private $bank_account;
	/** @Column(type="string", length=120, nullable=false) */
	private $contact_person;
	/** @Column(type="string", length=60, nullable=false) */
	private $fax;
	
    public function setMasterID($value) { $this->master_id = $value; }
    public function getMasterID() { return $this->master_id; }
	public function setCompanyName($value) { $this->company_name = $value; }
	public function getCompanyName() { return $this->company_name; }
	public function setTaxNumber($value) { $this->tax_number = $value; }
	public function getTaxNumber() { return $this->tax_number; }
    public function setCurrentAccount( $value ) { $this->bank_account = $value; }
    public function getCurrentAccount() { return $this->bank_account; }
	public function setContactPerson($value) { $this->contact_person = $value; }
	public function getContactPerson() { return $this->contact_person; }
	public function setFax($value) { $this->fax = $value; }
	public function getFax() { return $this->fax; }
 }
 
 /* End of file Business.php */
 /* Location: ./system/applications/_backend/models/Entities/User/Customer/Business.php */