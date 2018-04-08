<?php

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */

 namespace models\Entities\User;
 
 use models\Entities\User;
 use models\Entities\AskCustomer;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity(repositoryClass="models\UserRepository")
  * @Table(name="ecom_users")
  * @InheritanceType("SINGLE_TABLE")
  * @DiscriminatorColumn(name="type_id", type="integer")
  * @DiscriminatorMap({
  * "0" = "models\Entities\User\Customer",
  * "1" = "models\Entities\User\Customer\Personal",
  * "2" = "models\Entities\User\Customer\Business"
  * })
  */
 class Customer extends User {
 	
	/**
	 * @Id
	 * @Column(type="integer", length=10, nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/** @Column(type="string", length=240, nullable=false) */
	private $address;
	/** @Column(type="string", length=60, nullable=false) */
	private $phone;
	/** @Column(type="smallint", length=2, nullable=true) */
	private $newsletter;
    /** @Column(type="datetime") */
    protected $last_login_date;
	/** @Column(type="string", length=32, nullable=true) */
	protected $request_token;
    
    /**
     * @OneToOne(targetEntity="models\Entities\PostalCode")
     * @JoinColumn(name="postal_code", referencedColumnName="postal_code")
     */
    private $postal_code;
    
    /**
     * @OneToMany(targetEntity="models\Entities\AskCustomer", mappedBy="user", cascade={"remove"})
     */
    private $questions;

	public function __construct() {

        $this->questions			= new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getID() { return $this->id; }
	public function setAddress( $value ) { $this->address = $value; }
    public function getAddress() { return $this->address; }
	public function setPhone( $value ) { $this->phone = $value; }
    public function getPhone() { return $this->phone; }
    public function setNewsletter( $value ) { $this->newsletter = $value; }
    public function getNewsletter() { return $this->newsletter; }
    public function setPostalCode( $value ) { $this->postal_code = $value; }
    public function getPostalCode() { return $this->postal_code; }
    public function getLastLoginDate() { return $this->last_login_date; } 
    public function getFormatedLastLoginDate() { return $this->last_login_date->format('d/m/Y H:i'); }
    
    public function getCityName() {
        try {
            return $this->getPostalCode()->getCity();
        }
        catch( \Doctrine\ORM\EntityNotFoundException $e ) {
            
            return '';
        }
    }
    
    public function getCityCode() {
        try {
            return $this->getPostalCode()->getPostalCode();
        }
        catch( \Doctrine\ORM\EntityNotFoundException $e ) {
            
            return '';
        }
    }
 }
 
 /* End of file Customer.php */
 /* Location: ./system/applications/_backend/models/entities/User/Customer.php */