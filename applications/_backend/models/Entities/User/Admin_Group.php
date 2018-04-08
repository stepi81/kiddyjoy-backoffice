<?php

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */

 namespace models\Entities\User;
 
 use models\Entities\Section;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity
  * @Table(name="backoffice_user_groups")
  */
 class Admin_Group {
 	
	/**
	 * @Id
	 * @Column(type="integer", nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/** @Column(type="string", length=60, nullable=false) */
	private $name;
	
	/**
     * @ManyToMany(targetEntity="models\Entities\Section")
     * @JoinTable(name="backoffice_user_groups_sections",
     *      joinColumns={@JoinColumn(name="group_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@JoinColumn(name="section_id", referencedColumnName="id", onDelete="CASCADE")}
     *      )
     */
	private $sections;
	
	public function __construct() {
        $this->sections = new \Doctrine\Common\Collections\ArrayCollection();
    }
	
    public function getID() { return $this->id; }
	public function setName($value) { $this->name = $value; }
	public function getName() { return $this->name; }
	public function addSection( Section $value ) { $this->sections->add($value); }
	public function getSections() { return $this->sections; }
	public function deleteSections() { $this->sections->clear(); }
 }
 
 /* End of file Admin_Group.php */
 /* Location: ./system/applications/_backend/models/entities/user/Admin_Data.php */