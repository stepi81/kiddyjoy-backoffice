<?php

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */

 namespace models\Entities\User;
 
 use models\Entities\User;
 use models\Entities\User\Admin_Group;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity(repositoryClass="models\UserRepository")
  * @Table(name="backoffice_users")
  */
 class Admin extends User {
 	
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
	/** @Column(type="string", length=60, nullable=false) */
	private $phone;
	/** @Column(type="smallint", length=2, nullable=false) */
	private $status;
	
	/**
     * @OneToOne(targetEntity="Admin_Group")
     * @JoinColumn(name="group_id", referencedColumnName="id")
     */
	private $group;
	
	/**
     * @OneToMany(targetEntity="Admin_Activity", mappedBy="admin")
     **/
	private $activities;
	
 	public function __construct() {
        
		$this->activities = new \Doctrine\Common\Collections\ArrayCollection();
    }
	
	public function getID() { return $this->id; }
	public function setFirstName( $value ) { $this->first_name = $value; }
	public function getFirstName() { return $this->first_name; }
	public function setLastName( $value ) { $this->last_name = $value; }
	public function getLastName() { return $this->last_name; }
	public function setPhone( $value ) { $this->phone = $value; }
	public function getPhone() { return $this->phone; }
	public function setStatus( $value ) { $this->status = $value; }
	public function getStatus() { return $this->status; }
	public function setGroup( Admin_Group $value ) { $this->group = $value; }
	public function getGroup() { return $this->group; }
	public function setActivity( Admin_Activity $value ) { $this->activities[] = $value; }
	public function getActivities() { return $this->activities; }
 }
 
 /* End of file Admin.php */
 /* Location: ./system/applications/_backend/models/Entities/User/Admin.php */