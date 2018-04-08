<?php

/**
 * ...
 * @author Andrej The Savic [ ABC Design ]
 */

 namespace models\Entities\User;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity
  * @Table(name="backoffice_user_activities")
  * @InheritanceType("SINGLE_TABLE")
  * @DiscriminatorColumn(name="type_id", type="integer")
  * @DiscriminatorMap({"1" = "models\Entities\User\Activities\Activity_Product", "2" = "models\Entities\User\Activities\Activity_Bundle"})
  */
 class Admin_Activity {
 	
	/**
	 * @Id
	 * @Column(type="integer", length=10, nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/** @Column(type="integer", length=10, nullable=false) */
	private $operation_id;
	/** @Column(type="integer", length=10, nullable=false) */
	private $process_id;
	/** @Column(type="datetime") */
	private $date;
	
    /**
     * @ManyToOne(targetEntity="Admin", inversedBy="activities")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     **/
    private $admin;
	
	public function __construct() {
        
		// TODO
    }
	
    public function getID() { return $this->id; }
	public function setOperation( $value ) { $this->operation_id = $value; }
 	public function setProcess( $value ) { $this->process_id = $value; }
 	public function setDate() { $this->date = new \DateTime("now"); }
    public function getDate( $format = FALSE ) { return $format ? $this->date->format('d.m.Y. H:i:s') : $this->date; }
    public function setAdmin( Admin $value ) { $this->admin = $value; }
    public function getAdmin() { return $this->admin; }
    public function getAdminName() { return $this->admin->getFirstName().' '.$this->admin->getLastName(); }
    public function getAdminGroupName() { return $this->admin->getGroup()->getName(); }
    
 	public function getOperation() {
 		$data = unserialize(ADMIN_ACTIVITY_OPERATIONS);
 		return $data[$this->operation_id];
 	}
 	
 	public function getProcess() {
 		$data = unserialize(ACTIVITY_PROCESS);
 		return $data[$this->process_id];
 	}
 }
 
 /* End of file Admin_Activity.php */
 /* Location: ./system/applications/_backend/models/Entities/User/Admin_Activity.php */