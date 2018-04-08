<?php

/**
 * ...
 * @author Aleksandar Milas [ Codeion ]
 */

 namespace models\Entities;
 
 use models\Entities\User\Customer;
 use models\Entities\Preorder;
 use models\Entities\PreorderItem;
 use models\Entities\LocationData;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity(repositoryClass="models\PreorderRepository")
  * @Table(name="ecom_preorder_users")
  */
 class PreorderUser {
 	
	/**
	 * @Id
	 * @Column(type="integer", length=10, nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/**
	 * @OneToOne(targetEntity="models\Entities\User\Customer")
	 * @JoinColumn(name="user_id", referencedColumnName="id")
	 */
	private $user;
	
	/**
	 * @OneToOne(targetEntity="models\Entities\Preorder")
	 * @JoinColumn(name="preorder_id", referencedColumnName="id")
	 */
	private $preorder;
	
	/** @Column(type="integer", length=4, nullable=false) */
	private $quantity;
	
	/**
	 * @OneToOne(targetEntity="models\Entities\LocationData")
	 * @JoinColumn(name="location_id", referencedColumnName="location_id")
	 */
	private $location;
	
	/**
	 * @OneToOne(targetEntity="models\Entities\PreorderItem")
	 * @JoinColumn(name="preorder_item_id", referencedColumnName="id")
	 */
	private $item;
	
	public function getID() { return $this->id; }
	
	public function setUser( Customer $value ) { $this->user = $value; }
	public function getUser() { return $this->user; }
	
	public function setPreorder( Preorder $value ) { $this->preorder = $value; }
	public function getPreorder() { return $this->preorder; }
	
	public function setQuantity( $value ) { $this->quantity = $value; }
    public function getQuantity() { return $this->quantity; }
	
	public function setLocation( LocationData $value ) { $this->location = $value; }
	public function getLocation() { return $this->location; }
	
	public function setPreorderItem( PreorderItem $value ) { $this->item = $value; }
	public function getPreorderItem() { return $this->item; }
 }
 
 /* End of file PreorderUser.php */
 /* Location: ./system/applications/_backend/models/Entities/PreorderUser.php */